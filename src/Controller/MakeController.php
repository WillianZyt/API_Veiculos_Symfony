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
#[Route('/makes')]
class MakeController extends AbstractController
{
    #[Route('/', name: 'app_make_get_all', methods: ['GET'])]
    #[AT\Get(summary: 'Fetch all makes')]
    #[AT\Tag(name: 'Makes')]
    public function getAll(MakeRepository $makeRepository, SerializerInterface $serializer): JsonResponse
    {
        return $this->json([
            'message' => 'All makes fetched successfully',
            'data' => $serializer->normalize($makeRepository->findAll(), 'json', ['groups' => 'model']),
        ]);
    }

    #[Route('/{id}', name: 'app_make_get', methods: ['GET'])]
    #[AT\Get(summary: 'Fetch a make')]
    #[AT\Tag(name: 'Makes')]
    public function get(int $id, MakeRepository $makeRepository, SerializerInterface $serializer): JsonResponse
    {
        return $this->json([
            'message' => 'Make fetched successfully',
            'data' => $serializer->normalize($makeRepository->find($id), 'json', ['groups' => 'model']),
        ]);
    }

    #[Route('/', name: 'app_make_create', methods: ['POST'])]
    #[AT\RequestBody(
        content: new AT\JsonContent(
            example: ['name' => 'Volkswagen']
        )
    )]
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

    #[Route('/{id}', name: 'app_make_update', methods: ['PUT'])]
    #[AT\Put(summary: 'Update a make')]
    #[AT\RequestBody(
        content: new AT\JsonContent(
            example: ['name' => 'Volkswagen']
        )
    )]
    #[AT\Tag(name: 'Makes')]
    public function update(int $id, Request $request, MakeRepository $makeRepository, SerializerInterface $serializer): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $make = $makeRepository->findOneBy(['id' => $id]);
        $make->setName($data['name']);

        $makeRepository->update($make);
        return $this->json([
            'message' => 'Make updated!',
            'name' => $serializer->normalize($make, 'json', ['groups' => 'model']),
        ]);
    }

    #[Route('/{id}', name: 'app_make_delete', methods: ['DELETE'])]
    #[AT\Delete(summary: 'Delete a make')]
    #[AT\Tag(name: 'Makes')]
    public function delete(int $id, MakeRepository $makeRepository): JsonResponse
    {
        $make = $makeRepository->findOneBy(['id' => $id]);
        $makeRepository->remove($make);
        return $this->json(['message' => 'Make deleted!']);
    }
}
