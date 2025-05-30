<?php

namespace StephaneCoinon\SoftSwitch;

use GuzzleHttp\Client as Guzzle;
use StephaneCoinon\SoftSwitch\Exceptions\MalformedJson;

class HttpClient
{
    /** @var string  Root/base URI for all endpoints */
    protected $baseUri;

    /** @var string  API username */
    protected $username;

    /** @var string  API key */
    protected $key;

    /** @var \GuzzleHttp\Client Underlying HTTP client */
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

        $this->setClient(new Guzzle([
            'base_uri' => $this->baseUri,
            'verify' => false, // Disable SSL verification for testing purposes
        ]));
    }

    /**
     * Set the underlying HTTP client.
     *
     * @param  \GuzzleHttp\Client $httpClient
     * @return self
     */
    public function setClient($httpClient)
    {
        $this->http = $httpClient;

        return $this;
    }

    /**
     * Get the underlying HTTP client instance.
     *
     * @return \GuzzleHttp\Client
     */
    public function getClient()
    {
        return $this->http;
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

    /**
     * Send a GET request to an API endpoint and get the decoded JSON response.
     *
     * @param  string  $type        request type
     * @param  array   $parameters  request parameters
     * @param  boolean $assoc       When TRUE, returned objects will be converted into associative arrays.
     * @return array
     * @throws \StephaneCoinon\SoftSwitch\Exceptions\MalformedJson
     */
    public function getJson($type, $parameters = [], $assoc = true)
    {
        $response = $this->get($type, $parameters, 'json');

        $json = json_decode($response->getBody(), $assoc);

        if (is_null($json)) {
            throw new MalformedJson('Malformed JSON response', json_last_error());
        }

        return $json;
    }
}
