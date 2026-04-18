<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentGateway;

class PaymentGatewaySeeder extends Seeder
{
    public function run(): void
    {
        $gateways = [
            [
                'name' => 'Paystack',
                'slug' => 'paystack',
                'config' => [
                    'public_key' => '',
                    'secret_key' => '',
                ],
                'is_active' => true
            ],
            [
                'name' => 'Flutterwave',
                'slug' => 'flutterwave',
                'config' => [
                    'public_key' => '',
                    'secret_key' => '',
                    'encryption_key' => '',
                ],
                'is_active' => false
            ],
            [
                'name' => 'Bank Transfer',
                'slug' => 'bank_transfer',
                'config' => [
                    'bank_name' => 'Access Bank',
                    'account_number' => '0123456789',
                    'account_name' => 'The Curated Archive',
                ],
                'is_active' => true
            ],
        ];

        foreach ($gateways as $gateway) {
            PaymentGateway::updateOrCreate(['slug' => $gateway['slug']], $gateway);
        }
    }
}
