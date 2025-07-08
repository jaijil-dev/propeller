<?php

namespace App\Service;

use App\Service\ApiClientService as ApiClient;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * SubscriberService class
 */
class SubscriberService
{    
    private const CITY_LIST_MAP = [
        'London' => '01JY4Q9EKKCMXSVB7M682S2RNQ',
        'Birmingham' => '01JY4Q9EMQ6H2ZWA9N61XQZ5RB',
        'Edinburgh' => '01JY4Q9ENKX4XQ9Z61PA1WSM2R',
    ];
    
    /**
     * __construct
     *
     * @param  mixed $apiClient
     * @return void
     */
    public function __construct(private ApiClient $apiClient) {}
    
    /**
     * method to create a subscriber
     *
     * @param  mixed $data
     * @return string
     */
    public function createSubscriber(array $data): string
    {
        $data['marketing_lists'] = [];

        if (!empty($data['marketing_consent'])) {
            $validCities = array_keys(self::CITY_LIST_MAP);
            $selectedCities = array_intersect($data['marketing_lists_raw'] ?? [], $validCities);

            foreach ($selectedCities as $city) {
                $data['marketing_lists'][] = self::CITY_LIST_MAP[$city];
            }
        }

        // Create subscriber
        
        $subscriberId = $this->apiClient->createSubscriber([
            'emailAddress' => $data['email'],
            'firstName' => $data['first_name'] ?? null,
            'lastName' => $data['last_name'] ?? null,
            'dateOfBirth' => $data['date_of_birth'],
            'marketingConsent' => $data['marketing_consent'] ?? false,
            'lists' => $data['marketing_lists'],
        ]);
    
    
        // Create enquiry
        if ($subscriberId){
            $response = $this->apiClient->createEnquiry($subscriberId, $data['message']);

            return $subscriberId;
        }       
    }
}
