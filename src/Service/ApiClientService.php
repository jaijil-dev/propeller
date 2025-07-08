<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

/**
 * ApiClientService class
 */
class ApiClientService
{    
    /**
     * __construct
     *
     * @return void
     */
    public function __construct(
        private HttpClientInterface $client,
        private string $baseUrl,
        private string $token
    ) {}
    
    /**
     * request method
     *
     * @param  mixed $method
     * @param  mixed $endpoint
     * @param  mixed $data
     * @return array
     */
    private function request(string $method, string $endpoint, array $data = []): array
    {
        try {
            $response = $this->client->request($method, rtrim($this->baseUrl, '/') . '/' . ltrim($endpoint, '/'), [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->token,
                    'Content-Type' => 'application/json',
                ],
                'json' => $data,
            ]);

            return [
                'status' => $response->getStatusCode(),
                'body' => $response->toArray(false),
            ];
        } catch (TransportExceptionInterface $e) {
            return [
                'status' => 500,
                'body' => ['error' => $e->getMessage()],
            ];
        }
    }
    
    /**
     * method to create new subscriber
     *
     * @param  mixed $subscriberData
     * @return string|null
     */
    public function createSubscriber(array $subscriberData): ?string
    {
        try {
            $response = $this->request('POST', '/api/subscriber', $subscriberData);print_r($response);
        } catch (TransportExceptionInterface $e) {
            throw new \RuntimeException('Failed to create subscriber: ' . $e->getMessage());
        }

        $subscriber = $response['body']['subscriber'] ?? null;

        if (!$subscriber || !isset($subscriber['id'])) {
            throw new \RuntimeException('Missing subscriber ID in API response.');
        }

        return $subscriber['id'];
    }
    
    /**
     * method to create an enquiry
     *
     * @param  mixed $subscriberId
     * @param  mixed $message
     * @return mixed
     * @throws \RuntimeException
     * @throws TransportExceptionInterface
     */
    public function createEnquiry(string $subscriberId, string $message): mixed
    {
        $endpoint = sprintf('/api/subscriber/%s/enquiry', $subscriberId);

        try {
            $response = $this->request('POST', $endpoint, ['message' => $message]);
        } catch (TransportExceptionInterface $e) {
            throw new \RuntimeException('Failed to create enquiry: ' . $e->getMessage());
        } 

        if (empty($response)) {
            throw new \RuntimeException('Invalid response from API: ' . json_encode($response));
        }

        $enquiry = $response['body']['enquiry']['createdAt'] ?? null;

        return $enquiry;
    }
}