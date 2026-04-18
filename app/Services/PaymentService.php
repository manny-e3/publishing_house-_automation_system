<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Transaction;
use App\Models\PaymentGateway;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    /**
     * Initialize payment and get redirect URL
     */
    public function initializePayment(Invoice $invoice, string $gatewaySlug)
    {
        $gateway = PaymentGateway::where('slug', $gatewaySlug)->first();
        
        if (!$gateway || !$gateway->is_active) {
            throw new \Exception("Payment gateway not available.");
        }

        switch ($gatewaySlug) {
            case 'paystack':
                return $this->initializePaystack($invoice, $gateway->config);
            case 'flutterwave':
                return $this->initializeFlutterwave($invoice, $gateway->config);
            default:
                throw new \Exception("Gateway integration pending.");
        }
    }

private function initializePaystack(Invoice $invoice, array $config)
{
    $url = "https://api.paystack.co/transaction/initialize";

    if (empty($config['secret_key'])) {
        throw new \Exception("Paystack Secret Key is missing in Payment Settings.");
    }

    $reference = $invoice->invoice_number . '_' . time();

    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $config['secret_key'],
        'Content-Type'  => 'application/json',
        'User-Agent'    => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
        'Expect'        => '',
    ])
    ->withOptions([
        'verify' => false,
        'curl' => [
            CURLOPT_SSLVERSION      => CURL_SSLVERSION_TLSv1_2,
            CURLOPT_IPRESOLVE       => CURL_IPRESOLVE_V4,
            CURLOPT_HTTP_VERSION    => CURL_HTTP_VERSION_1_1,
        ]
    ])
    ->post($url, [
        'email'        => $invoice->prospect->email,
        'amount'       => $invoice->amount * 100,
        'reference'    => $reference,
        'callback_url' => route('payments.callback', ['gateway' => 'paystack']),
        'metadata'     => [
            'invoice_id'  => $invoice->id,
            'prospect_id' => $invoice->prospect_id,
        ],
    ]);

    if ($response->successful()) {
        $data = $response->json()['data'];

        Transaction::create([
            'invoice_id'            => $invoice->id,
            'gateway_slug'          => 'paystack',
            'transaction_reference' => $reference,
            'amount'                => $invoice->amount,
            'status'                => 'pending',
        ]);

        return $data['authorization_url'];
    }

    Log::error('Paystack Init Failed', [
        'status'  => $response->status(),
        'body'    => $response->body(),
        'invoice' => $invoice->id,
    ]);

    throw new \Exception("Could not initialize Paystack payment.");
}
    private function initializeFlutterwave(Invoice $invoice, array $config)
    {
        $url = "https://api.flutterwave.com/v3/payments";
        
        if (empty($config['secret_key'])) {
            throw new \Exception("Flutterwave Secret Key is missing in Payment Settings.");
        }
        
        $txRef = $invoice->invoice_number . '_' . time();

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $config['secret_key'],
            'Content-Type'  => 'application/json',
            'User-Agent'    => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
            'Expect'        => '',
        ])
        ->withOptions([
            'verify' => false,
            'curl' => [
                CURLOPT_SSLVERSION   => CURL_SSLVERSION_TLSv1_2,
                CURLOPT_IPRESOLVE    => CURL_IPRESOLVE_V4,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            ]
        ])
        ->post($url, [
            'tx_ref' => $txRef,
            'amount' => $invoice->amount,
            'currency' => "NGN",
            'redirect_url' => route('payments.callback', ['gateway' => 'flutterwave']),
            'customer' => [
                'email' => $invoice->prospect->email,
                'phonenumber' => $invoice->prospect->phone_number,
                'name' => $invoice->prospect->name,
            ],
            'meta' => [
                'invoice_id' => $invoice->id,
            ],
            'customizations' => [
                'title' => "The Curated Archive",
                'description' => "Payment for Invoice " . $invoice->invoice_number,
            ]
        ]);

        if ($response->successful()) {
            $data = $response->json()['data'];

            Transaction::create([
                'invoice_id' => $invoice->id,
                'gateway_slug' => 'flutterwave',
                'transaction_reference' => $txRef,
                'amount' => $invoice->amount,
                'status' => 'pending'
            ]);

            return $data['link'];
        }

        Log::error('Flutterwave Init Failed', [
            'status'  => $response->status(),
            'body'    => $response->body(),
            'invoice' => $invoice->id,
        ]);
        throw new \Exception("Could not initialize Flutterwave payment.");
    }

    /**
     * Handle Webhook Verification
     */
    public function verifyWebhook(string $gatewaySlug, $request)
    {
        $gateway = PaymentGateway::where('slug', $gatewaySlug)->first();
        if (!$gateway) return false;

        switch ($gatewaySlug) {
            case 'paystack':
                return $this->verifyPaystackWebhook($request, $gateway->config['secret_key']);
            case 'flutterwave':
                return $this->verifyFlutterwaveWebhook($request, $gateway->config['secret_key']);
            default:
                return false;
        }
    }

    private function verifyPaystackWebhook($request, $secretKey)
    {
        $signature = $request->header('x-paystack-signature');
        if (!$signature) return false;

        $computedSignature = hash_hmac('sha512', $request->getContent(), $secretKey);
        return hash_equals($signature, $computedSignature);
    }

    private function verifyFlutterwaveWebhook($request, $secretKey)
    {
        // Flutterwave uses a secret hash set in the dashboard
        $signature = $request->header('verif-hash');
        // For simplicity, we compare with secret_key or a dedicated webhook_hash
        return $signature === $secretKey; 
    }
}
