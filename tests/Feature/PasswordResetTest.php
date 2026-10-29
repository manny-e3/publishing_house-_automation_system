<?php

namespace Tests\Feature;

use App\Models\User;
use App\Mail\ResetPasswordMail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_forgot_password_screen_can_be_rendered(): void
    {
        $response = $this->get('/forgot-password');

        $response->assertStatus(200);
        $response->assertSee('Forgot Password');
    }

    public function test_reset_link_fails_for_non_existent_email(): void
    {
        $response = $this->from('/forgot-password')->post('/forgot-password', [
            'email' => 'doesnotexist@example.com',
        ]);

        $response->assertRedirect('/forgot-password');
        $response->assertSessionHasErrors(['email']);
    }

    public function test_reset_link_can_be_requested_for_existing_user(): void
    {
        Mail::fake();

        $user = User::factory()->create();

        $response = $this->from('/forgot-password')->post('/forgot-password', [
            'email' => $user->email,
        ]);

        $response->assertRedirect('/forgot-password');
        $response->assertSessionHas('success');

        Mail::assertSent(ResetPasswordMail::class, function ($mail) use ($user) {
            return $mail->user->id === $user->id;
        });
    }

    public function test_reset_password_screen_can_be_rendered(): void
    {
        $user = User::factory()->create();
        $token = Password::broker()->createToken($user);

        $response = $this->get("/reset-password/{$token}?email=" . urlencode($user->email));

        $response->assertStatus(200);
        $response->assertSee('Reset Password');
        $response->assertSee($user->email);
    }

    public function test_password_can_be_reset_with_valid_token(): void
    {
        $user = User::factory()->create();
        $token = Password::broker()->createToken($user);

        $response = $this->post('/reset-password', [
            'token' => $token,
            'email' => $user->email,
            'password' => 'NewSecretPassword123!',
            'password_confirmation' => 'NewSecretPassword123!',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHas('success');

        $user->refresh();
        $this->assertTrue(Hash::check('NewSecretPassword123!', $user->password));
    }
}

