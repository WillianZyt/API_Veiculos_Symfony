<?php

namespace App\Controller;

use App\Entity\Model;
use App\Repository\MakeRepository;
use App\Repository\ModelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use OpenApi\Annotations\Response;
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

    #[Route('/models/{name}', name: 'app_create_model_name', methods: ['POST'])]
    #[AT\Tag(name: 'Models')]
    public function create(
        Request $request,
        ModelRepository $modelRepository,
        MakeRepository $makeRepository,
        SerializerInterface $serializer
    ): JsonResponse {
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
}
