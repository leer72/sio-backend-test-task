<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class AbstractTest extends WebTestCase
{
    /**
     * Базовый урл
     */
    public const URL = '';

    /**
     * @var KernelBrowser
     */
    protected KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->client->disableReboot();

        parent::setUp();
    }

    protected function sendRequest(
        string $method,
        array $search = [],
        array $replacement = [],
        ?array $params = [],
        ?array $body = null,
        ?string $url = null,
        ?array $headers = [
            'CONTENT_TYPE' => 'application/json',
            'Accept' => 'application/json',
            ],
    ): Response {
        $url = !is_null($url) ? self::URL . $url : self::URL . static::URL;

        $this->client->request(
            $method,
            str_replace($search, $replacement, $url),
            $params,
            [],
            $headers,
            empty($body) ? $body : json_encode($body),
            false
        );

        return $this->client->getResponse();
    }
}
