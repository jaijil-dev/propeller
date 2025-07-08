<?php

namespace App\Controller;

use App\DTO\ApiRequest as SignupRequest;
use App\Service\SubscriberService as SignupService;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * ApiController
 */
class ApiController extends AbstractController
{
    #[Route('/submit', name: 'submit', methods: ['POST'])]   
    public function submit(Request $request, SignupService $signupService): JsonResponse {
        $data = json_decode($request->getContent(), true);

        if ($error = $this->validateInput($data)) {
            return $this->json(['error' => $error], 400);
        }
        
        $subscriberId = $signupService->createSubscriber((array) $data);

        return $this->json([
            'success' => true,
            'subscriber_id' => $subscriberId,
            'message' => 'Subscriber and enquiry submitted successfully.',
        ]);       
    }
    
    /**
     * method to validate Input
     *
     * @param  mixed $data
     * @return string
     */
    private function validateInput(array $data): ?string
    {
        if (empty($data['email'])) {
            return 'Email is required.';
        }

        if (empty($data['date_of_birth'])) {
            return 'Date of birth is required.';
        }

        if (empty($data['message'])) {
            return 'Enquiry message is required.';
        }

        try {
            $dob = new \DateTime($data['date_of_birth']);
            $age = (new \DateTime())->diff($dob)->y;
            if ($age < 18) {
                return 'Subscriber must be at least 18 years old.';
            }
        } catch (\Exception $e) {
            return 'Invalid date format for date of birth.';
        }

        return null;
    }
}
