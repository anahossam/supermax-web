<?php

use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('user:reset-password {username}', function (string $username) {
    $user = User::where('username', $username)->first();
    if (! $user) {
        $this->error("No user named \"{$username}\".");

        return 1;
    }

    // Asked for with hidden input, never taken from the command line (it would stay in the shell history).
    $password = (string) $this->secret('New password (at least 8 characters)');
    if (mb_strlen($password) < 8) {
        $this->error('The password must be at least 8 characters.');

        return 1;
    }
    if ($this->secret('Repeat the new password') !== $password) {
        $this->error('The passwords do not match.');

        return 1;
    }

    $user->update(['password' => $password]);
    $this->info("Password changed for {$username}.");

    return 0;
})->purpose('Reset a user\'s password (prompts for it)');
