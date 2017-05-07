<?php

namespace App\app\Providers;

use App\Models\Merchant;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Support\Facades\Hash;

/**
 * Defines how to retrieve and authenticate a merchant.
 *
 * @package App\app\Providers
 */
class MerchantAuthProvider implements UserProvider
{

    /**
     * Retrieve a user by their unique identifier.
     *
     * @param  mixed $identifier
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveById($identifier)
    {

        return Merchant::find($identifier);

    }

    /**
     * Retrieve a user by their unique identifier and "remember me" token.
     *
     * @param  mixed $identifier
     * @param  string $token
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByToken($identifier, $token)
    {

        return $this->retrieveById($identifier);

    }

    /**
     * Update the "remember me" token for the given user in storage.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable $user
     * @param  string $token
     * @return void
     */
    public function updateRememberToken(Authenticatable $user, $token)
    {

        return null;

    }

    /**
     * Retrieve a user by the given credentials.
     *
     * @param  array $credentials
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByCredentials(array $credentials)
    {

        $key = $credentials['merchant-key'];

        return $this->retrieveById($key);

    }

    /**
     * Validate a user against the given credentials.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable $merchant
     * @param  array $credentials
     * @return bool
     */
    public function validateCredentials(Authenticatable $merchant, array $credentials)
    {

        $secret = $credentials['merchant-secret'];

        return Hash::check($secret, $merchant->secret);

    }

}