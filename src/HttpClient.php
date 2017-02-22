<?php

namespace StephaneCoinon\SoftSwitch;

use GuzzleHttp\Client as Guzzle;

class HttpClient
{
    /** @var string  Root/base URI for all endpoints */
    protected $baseUri;

    /** @var string  API username */
    protected $username;

    /** @var string  API key */
    protected $key;

    /** @var [type] HTTP client */
    protected $http;

    /** @var string  debug HTTP client requests? */
    protected $debug = false;


    /**
     * Get a new Api instance.
     *
     * @param string $baseUri  Endpoint root URI
     * @param string $username  API username
     * @param string $key  API key
     */
    public function __construct($baseUri, $username, $key)
    {
        $this->baseUri = $baseUri;
        $this->username = $username;
        $this->key = $key;

        $this->http = new Guzzle([
            'base_uri' => $this->baseUri,
        ]);
    }


    /**
     * Turn debug/verbose on/off.
     *
     * @param  boolean $debug
     * @return static
     */
    public function debug($debug = true)
    {
        $this->debug = $debug;

        return $this;
    }


    /**
     * Request an API endpoint.
     *
     * @param  string $method      HTTP method
     * @param  string $type        Soft-Switch request type ('HELP', 'COUNTPEERS'...)
     * @param  array  $parameters  request parameters
     * @param  string $format      'json' or 'plain'
     * @return GuzzleHttp\Psr7\Response
     */
    public function request($method, $type, $parameters = [], $format = 'json')
    {
        $query = array_merge([
            'reqtype' => $type,
            'tenant' => $this->username,
            'key' => $this->key,
            'format' => $format,
        ], $parameters);

        return $this->http->request($method, '', [
            'debug' => $this->debug,
            'query' => $query,
        ]);
    }


    /**
     * Send a GET request to an API endpoint.
     *
     * @param  string $type        request type
     * @param  array  $parameters  request parameters
     * @param  string $format      'json' or 'plain'
     * @return GuzzleHttp\Psr7\Response
     */
    public function get($type, $parameters = [], $format = 'json')
    {
        return $this->request('GET', $type, $parameters, $format);
    }
}
