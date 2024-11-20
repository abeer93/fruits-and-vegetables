<?php

namespace App\Controller;

use App\DTO\FilterDTO;
use App\DTO\VegetableRequest;
use App\Service\ValidationService;
use App\Service\VegetableService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class VegetableController extends AbstractController
{
    private VegetableService $vegetableService;
    private ValidationService $validationService;
    private $serializer;

    public function __construct(VegetableService $vegetableService, ValidationService $validationService, SerializerInterface $serializer)
    {
        $this->vegetableService = $vegetableService;
        $this->validationService = $validationService;
        $this->serializer = $serializer;
    }

    /**
     * @Route("/api/vegetables", methods={"GET"})
     */
    public function getVegetables(Request $request): JsonResponse
    {
        $queryParams = $request->query->all();

        $filterDTO = new FilterDTO($queryParams);

        $validationResponse = $this->validationService->validate($filterDTO);
        if ($validationResponse) {
            return $validationResponse;
        }
    
        $filters = $filterDTO->toFilters();
        $vegetables = $this->vegetableService->listVegetables($filters);

        $data = $this->serializer->normalize($vegetables, null, ['groups' => 'vegetable']);
        
        return new JsonResponse($data);
    }

    /**
     * @Route("/api/vegetables", methods={"POST"})
     */
    public function addVegetable(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $vegetableRequest = new VegetableRequest(
            $data['name'] ?? '',
            $data['quantity'] ?? 0,
            $data['unit'] ?? ''
        );

        $validationResponse = $this->validationService->validate($vegetableRequest);
        if ($validationResponse) {
            return $validationResponse;
        }

        $vegetable = $this->vegetableService->addVegetable($data['name'], $data['quantity'], $data['unit']);
        return new JsonResponse($vegetable, JsonResponse::HTTP_CREATED);
    }

    /**
     * @Route("/api/vegetables/{id}", methods={"DELETE"})
     */
    public function removeVegetable(int $id): JsonResponse
    {
        $this->vegetableService->removeVegetable($id);
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
