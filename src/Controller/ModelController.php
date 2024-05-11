<?php

namespace App\Controller;

use App\Entity\Model;
use App\Repository\MakeRepository;
use App\Repository\ModelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use OpenApi\Attributes as AT;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/models')]
class ModelController extends AbstractController
{
    #[Route('/', name: 'app_get_all_model', methods: ['GET'])]
    #[AT\Get(summary: 'Fetch all models')]
    #[AT\Response(
        response: 201,
        description: 'Models fetched successfully',
        content: new AT\JsonContent(
            type: 'object',
            example: [
                'name' => ['type' => 'string', 'example' => 'Fusca'],
                'year' => ['type' => 'integer', 'example' => 2021],
                'color' => ['type' => 'string', 'example' => 'red'],
                'fuel' => ['type' => 'string', 'example' => 'gasoline'],
                'category' => ['type' => 'object', 'properties' => ['name' => ['type' => 'string', 'example' => 'Volkswagen']]],
            ]
        )
    )]
    #[AT\Tag(name: 'Models')]
    public function getAll(ModelRepository $modelRepository, SerializerInterface $serializer): JsonResponse
    {
        return $this->json([
            'message' => 'Models fetched successfully',
            'data' => $serializer->normalize($modelRepository->findAll(), 'json', ['groups' => 'model']),
        ]);
    }

    #[Route('/{id}', name: 'app_get_model', methods: ['GET'])]
    #[AT\Get(summary: 'Fetch a model')]
    #[AT\Response(
        response: 201,
        description: 'Model fetched successfully',
        content: new AT\JsonContent(
            type: 'object',
            example: [
                'name' => ['type' => 'string', 'example' => 'Fusca'],
                'year' => ['type' => 'integer', 'example' => 2021],
                'color' => ['type' => 'string', 'example' => 'red'],
                'fuel' => ['type' => 'string', 'example' => 'gasoline'],
                'category' => ['type' => 'object', 'properties' => ['name' => ['type' => 'string', 'example' => 'Volkswagen']]],
            ]
        )
    )]
    #[AT\Tag(name: 'Models')]
    public function get(int $id, ModelRepository $modelRepository, SerializerInterface $serializer): JsonResponse
    {
        return $this->json([
            'message' => 'Model fetched successfully',
            'data' => $serializer->normalize($modelRepository->findOneBy(['id' => $id]), 'json', ['groups' => 'model']),
        ]);
    }


    #[Route('/{id}', name: 'app_create_model_name', methods: ['POST'])]
    #[AT\Post(summary: 'Create a model')]
    #[AT\RequestBody(
        description: 'Provide the name, year, color, fuel and category of the model',
        required: true,
        content: new AT\JsonContent(
            type: 'object',
            example: [
                'name' => 'Fusca',
                'year' => 2021,
                'color' => 'red',
                'fuel' => 'gasoline',
                'category' => 1,
            ]
        )
    )]
    #[AT\Tag(name: 'Models')]
    public function create( Request $request, ModelRepository $modelRepository, MakeRepository $makeRepository, SerializerInterface $serializer ): JsonResponse 
    {   
        $data = json_decode($request->getContent(), true);
        
        $model = new Model();
        $model->setName($data['name']);
        $model->setYear($data['year']);
        $model->setColor($data['color']);
        $model->setFuel($data['fuel']);
        $model->setCategory($makeRepository->findOneBy(['id' => $data['category']]));

        $modelRepository->create($model);
        return $this->json([
            'message' => 'New model created!',
            'data' => $serializer->normalize($model, 'json', ['groups' => 'model']),
        ]);
    }

    #[Route('/{id}', name: 'app_update_model', methods: ['PUT'])]
    #[AT\Put(summary: 'Update a model')]
    #[AT\RequestBody(
        description: 'Provide the name, year, color, fuel and category of the model',
        required: true,
        content: new AT\JsonContent(
            type: 'object',
            example: [
                'name' => 'Fusca',
                'year' => 2021,
                'color' => 'red',
                'fuel' => 'gasoline',
                'category' => 1,
            ]
        )
    )]
    #[AT\Tag(name: 'Models')]
    public function update(int $id, Request $request, ModelRepository $modelRepository, MakeRepository $makeRepository, SerializerInterface $serializer ): JsonResponse {
        
        $data = json_decode($request->getContent(), true);
        
        $model = $modelRepository->findOneBy(['id' => $id]);
        $model->setName($data['name']);
        $model->setYear($data['year']);
        $model->setColor($data['color']);
        $model->setFuel($data['fuel']);
        $model->setCategory($makeRepository->findOneBy(['id' => $data['category']]));

        $modelRepository->update($model);
        return $this->json([
            'message' => 'Model updated!',
            'data' => $serializer->normalize($model, 'json', ['groups' => 'model']),
        ]);
    }

    #[Route('/{id}', name: 'app_delete_model', methods: ['DELETE'])]
    #[AT\Delete(summary: 'Delete a model')]
    #[AT\Tag(name: 'Models')]
    public function delete(int $id, ModelRepository $modelRepository): JsonResponse
    {
        $model = $modelRepository->findOneBy(['id' => $id]);
        $modelRepository->remove($model);
        return $this->json(['message' => 'Model deleted!']);
    }
}
