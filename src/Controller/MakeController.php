<?php

namespace App\Controller;

use App\Entity\Make;
use App\Repository\MakeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use OpenApi\Attributes as AT;

class MakeController extends AbstractController
{
    #[Route('/makes', name: 'app_make_get_all', methods: ['GET'])]
    #[AT\Tag(name: 'Makes')]
    public function getAll(MakeRepository $makeRepository, SerializerInterface $serializer): JsonResponse
    {
        return $this->json([
            'message' => 'All makes fetched successfully',
            'data' => $serializer->normalize($makeRepository->findAll(), 'json', ['groups' => 'model']),
        ]);
    }

    #[Route('/makes/{name}', name: 'app_make_create', methods: ['POST'])]
    #[AT\Tag(name: 'Makes')]
    public function create(Request $request ,MakeRepository $makeRepository, SerializerInterface $serializer): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $make = new Make();
        $make->setName($data['name']);
        $makeRepository->create($make);
        return $this->json([
            'message' => 'New make created!',
            'name' => $serializer->normalize($make, 'json', ['groups' => 'model']),
        ]);
    }
}
