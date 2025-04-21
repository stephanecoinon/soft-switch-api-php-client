<?php

namespace StephaneCoinon\SoftSwitch;

use GuzzleHttp\Client as Guzzle;
use Psr\Http\Message\ResponseInterface;
use StephaneCoinon\SoftSwitch\Exceptions\MalformedJson;

class HttpClient
{
    /** @var string  Root/base URI for all endpoints */
    protected string $baseUri;

    /** @var string  API username */
    protected string $username;

    /** @var string  API key */
    protected string $key;

    /** @var \GuzzleHttp\Client Underlying HTTP client */
    protected Guzzle $http;

    /** @var string|bool|null  debug HTTP client requests? */
    protected $debug;


    /**
     * Get a new Api instance.
     *
     * @param string $baseUri  Endpoint root URI
     * @param string $username  API username
     * @param string $key  API key
     */
    public function __construct(string $baseUri, string $username, string $key)
    {
        $this->baseUri = $baseUri;
        $this->username = $username;
        $this->key = $key;

        $this->setClient(new Guzzle([
            'base_uri' => $this->baseUri,
            'verify' => false, // Disable SSL verification for testing purposes
        ]));
    }

    public function getBaseUri(): string
    {
        return $this->baseUri;
    }

    /**
     * Set the underlying HTTP client.
     */
    public function setClient(Guzzle $httpClient): self
    {
        $this->http = $httpClient;

        return $this;
    }

    /**
     * Get the underlying HTTP client instance.
     */
    public function getClient(): Guzzle
    {
        return $this->http;
    }

    /**
     * Turn debug/verbose on/off.
     */
    public function debug(bool $debug = true): self
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
     */
    public function request(string $method, string $type, array $parameters = [], string $format = 'json'): ResponseInterface
    {
        $query = array_merge([
            'reqtype' => $type,
            'tenant' => $this->username,
            'key' => $this->key,
            'format' => $format,
        ], $parameters);

        $options = [
            'debug' => $this->debug,
            'query' => $query,
        ];

        if ($format === 'json') {
            $options['headers'] = ['Accept' => 'application/json'];
        }

        return $this->http->request($method, '', $options);
    }


    /**
     * Send a GET request to an API endpoint.
     *
     * @param  string $type        request type
     * @param  array  $parameters  request parameters
     * @param  string $format      'json' or 'plain'
     */
    public function get(string $type, array $parameters = [], string $format = 'json'): ResponseInterface
    {
        return $this->request('GET', $type, $parameters, $format);
    }

    /**
     * Send a GET request to an API endpoint and get the decoded JSON response.
     *
     * @param  string  $type        request type
     * @param  array   $parameters  request parameters
     * @param  boolean $assoc       When TRUE, returned objects will be converted into associative arrays.
     * @throws MalformedJson
     */
    public function getJson(string $type, array $parameters = [], bool $assoc = true): array
    {
        $response = $this->get($type, $parameters, 'json');

        $json = json_decode($response->getBody(), $assoc);

        if (json_last_error() !== JSON_ERROR_NONE) {
            // dump([
            //     'HttpClient::getJson()' => [
            //         'type' => $type,
            //         'parameters' => $parameters,
            //         'response' => (string) $response->getBody(),
            //     ],
            //     'json_last_error()' => json_last_error(),
            //     'json_last_error_msg()' => json_last_error_msg(),
            //     'json' => $json,
            // ]);

            throw new MalformedJson('Malformed JSON response', json_last_error());
        }

        return $json;
    }
}
