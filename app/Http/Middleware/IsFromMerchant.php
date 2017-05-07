<?php

namespace App\Http\Middleware;

use App\Http\ApiResponse;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsFromMerchant
{

    /**
     * The merchant key to authenticate.
     *
     * @var
     */
    protected $key;

    /**
     * The merchant secret to authenticate.
     *
     * @var
     */
    protected $secret;

    /**
     * The current request.
     *
     * @var Request
     */
    protected $request;

    /**
     * IsFromMerchant constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {

        $this->request = $request;

        $this->extractCredentialsFromRequest();

    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if (!$this->hasCredentials())
            return ApiResponse::unauthorized(trans('messages.no_credentials_found'));

        $credentials = $this->getCredentials();

        if (!Auth::attempt($credentials))
            return ApiResponse::unauthorized(trans('messages.invalid_credentials'));

        return $next($request);

    }

    /**
     * Checks if the credentials were informed.
     *
     * @return bool
     */
    protected function hasCredentials()
    {

        return !empty($this->key) && !empty($this->secret);

    }

    /**
     * Formats the credentials as an array.
     *
     * @return array
     */
    protected function getCredentials()
    {

        return [

            'merchant-key' => $this->key,
            'merchant-secret' => $this->secret,

        ];

    }

    /**
     * Extracts the credentials to the properties.
     */
    protected function extractCredentialsFromRequest()
    {

        $this->key = $this->getDataFromRequest('merchant-key');
        $this->secret = $this->getDataFromRequest('merchant-secret');

    }

    /**
     * Gets an input from the request header or body.
     *
     * @param $data
     * @return null
     */
    protected function getDataFromRequest($data)
    {

        if ($this->request->hasHeader($data))
            return $this->request->header($data);

        if ($this->request->has($data))
            return $this->request->input($data);

        return null;

    }

}