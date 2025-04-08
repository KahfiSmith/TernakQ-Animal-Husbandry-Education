<?php

namespace App\Livewire\Forms;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Validate;
use Livewire\Form;

class LoginForm extends Form
{
    #[Validate('required|email:rfc,dns|string|max:255')]
    public string $email = '';

    #[Validate('required|string|min:8')]
    public string $password = '';

    #[Validate('boolean')]
    public bool $remember = false;

    /**
     * Get custom validation messages.
     */
    protected function messages(): array
    {
        return [
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
            'email.max' => 'Alamat email tidak boleh lebih dari 255 karakter.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal terdiri dari 8 karakter.',
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->validate();
        $this->ensureIsNotRateLimited();

        // Check if the email exists first
        $user = \App\Models\User::where('email', $this->email)->first();
        
        if (!$user) {
            RateLimiter::hit($this->throttleKey());
            
            throw ValidationException::withMessages([
                'form.email' => 'Email tidak terdaftar.',
            ]);
        }
        
        // If email exists but password is wrong
        if (!Auth::attempt($this->only(['email', 'password']), $this->remember)) {
            RateLimiter::hit($this->throttleKey());
            
            throw ValidationException::withMessages([
                'form.password' => 'Kata sandi yang Anda masukkan salah.',
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'form.email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email).'|'.request()->ip());
    }
}
