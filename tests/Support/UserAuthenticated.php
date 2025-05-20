<?php

namespace Tests\Support;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;

trait UserAuthenticated
{
    private User $user;

    public function setupUser(array $body = [])
    {
        $this->user = User::first();

        return $this->authenticated($this->user);
    }

    public function authenticated(Authenticatable $user)
    {
        return $this->actingAs($user ?? $this->user);
    }
}
