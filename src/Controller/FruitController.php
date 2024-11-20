<?php

namespace App\Controller;

use App\DTO\FilterDTO;
use App\DTO\FruitRequest;
use App\Service\FruitService;
use App\Service\ValidationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/api/fruits", name="fruit_")
 */
class FruitController extends AbstractController
{
    private FruitService $fruitService;
    private ValidationService $validationService;
    private $serializer;

    public function __construct(FruitService $fruitService, ValidationService $validationService, SerializerInterface $serializer)
    {
        $this->fruitService = $fruitService;
        $this->validationService = $validationService;
        $this->serializer = $serializer;
    }

    /**
     * @Route("/api/fruits", name="get_fruits", methods={"GET"})
     */
    public function getFruits(Request $request): JsonResponse
    { 
        $queryParams = $request->query->all();

        $filterDTO = new FilterDTO($queryParams);

        $validationResponse = $this->validationService->validate($filterDTO);
        if ($validationResponse) {
            return $validationResponse;
        }
    
        $filters = $filterDTO->toFilters();
        $fruits = $this->fruitService->listFruits($filters);

        $data = $this->serializer->normalize($fruits, null, ['groups' => 'fruit']);
        
        return new JsonResponse($data);
    }

    /**
     * @Route("/api/fruits", methods={"POST"})
     */
    public function addFruit(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $fruitRequest = new FruitRequest(
            $data['name'] ?? '',
            $data['quantity'] ?? 0,
            $data['unit'] ?? ''
        );

        $validationResponse = $this->validationService->validate($fruitRequest);
        if ($validationResponse) {
            return $validationResponse;
        }

        $fruit = $this->fruitService->addFruit($data['name'], $data['quantity'], $data['unit']);
        return new JsonResponse($this->serializer->normalize($fruit), JsonResponse::HTTP_CREATED);
    }

    /**
     * @Route("/api/fruits/{id}", methods={"DELETE"})
     */
    public function removeFruit(int $id): JsonResponse
    {
        $this->fruitService->removeFruit($id);
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
