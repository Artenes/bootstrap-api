<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Webpatser\Uuid\Uuid;

/**
 * Merchant is the application that consumes the api.
 *
 * @package App\Models
 */
class Merchant extends Model implements Authenticatable
{

    /**
     * The primary key.
     *
     * @var string
     */
    protected $primaryKey = 'key';

    /**
     * The primary key is not auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Don't protect against mass assignment.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Creates a new merchant.
     *
     * It returns the generated merchant key and secret in an array.
     *
     * @param string $description
     * @return array
     */
    public static function createNew($description)
    {

        $key = Uuid::generate(config('app.uuid_version'));
        $secret = str_random();

        static::create([
            'key' => $key,
            'secret' => Hash::make($secret),
            'description' => $description,
        ]);

        return compact('key', 'secret');

    }

    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName()
    {

        return $this->primaryKey;

    }

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {

        return $this->key;

    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {

        return $this->secret;

    }

    /**
     * Get the token value for the "remember me" session.
     *
     * @return string
     */
    public function getRememberToken()
    {

        return '';

    }

    /**
     * Set the token value for the "remember me" session.
     *
     * @param  string $value
     * @return void
     */
    public function setRememberToken($value)
    {

        return null;

    }

    /**
     * Get the column name for the "remember me" token.
     *
     * @return string
     */
    public function getRememberTokenName()
    {

        return '';

    }

}