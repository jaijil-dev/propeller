<?php

namespace App\Tests\Service;

use App\Service\ApiClientService as ApiClient;
use App\Service\SubscriberService;
use PHPUnit\Framework\TestCase;

/**
 * SubscriberServiceTest
 */
class SubscriberServiceTest extends TestCase
{    
    /**
     * testCreateSubscriberWithMarketingConsentAddsToListsAndCreatesEnquiry
     *
     * @return void
     */
    public function testCreateSubscriberWithMarketingConsentAddsToListsAndCreatesEnquiry(): void
    {
        $data = [
            'emailAddress' => 'testjp@example.com',
            'firstName' => 'Jaijil',
            'lastName' => 'Peter',
            'dateOfBirth' => '1988-10-11',
            'marketingConsent' => true,
            'lists' => ['London', 'InvalidCity', 'Birmingham']
        ];

        $crm = $this->createMock(ApiClient::class);
        $crm->expects($this->once())
            ->method('createSubscriber')
            ->with($this->arrayHasKey('email'))
            ->willReturn(123);
        
        $crm->expects($this->once())
            ->method('createEnquiry')
            ->with(123, 'Hello from test!');

        $signupService = new SubscriberService($crm);
        $subscriberId = $signupService->createSubscriber($data);

        $this->assertEquals(123, $subscriberId);
    }
    
    /**
     * testCreateSubscriberWithoutMarketingConsentSkipsListAddition
     *
     * @return void
     */
    public function testCreateSubscriberWithoutMarketingConsentSkipsListAddition(): void
    {
        $data = [
            'emailAddress' => 'testjp1@example.com',
            'firstName' => 'Jaijil1',
            'lastName' => 'Peter1',
            'dateOfBirth' => '1988-10-11',
            'marketingConsent' => false,
            'lists' => ['London', 'InvalidCity']
        ];

        $crm = $this->createMock(ApiClient::class);
        $crm->expects($this->once())
            ->method('createSubscriber')
            ->willReturn(456);

        $crm->expects($this->once())
            ->method('createEnquiry')
            ->with(456, 'No marketing, thanks.');

        $signupService = new SubscriberService($crm);
        $subscriberId = $signupService->createSubscriber($data);

        $this->assertEquals(456, $subscriberId);
    }
}
