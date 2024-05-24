<?php

namespace App\Controller;

use App\Entity\Project;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Company;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/project')]
class ProjectController extends AbstractController
{
    private $entityManager;
    private $projectRepository;

    public function __construct(EntityManagerInterface $entityManager, ProjectRepository $projectRepository)
    {
        $this->entityManager = $entityManager;
        $this->projectRepository = $projectRepository;
    }


    #[Route('/', name: 'project_index', methods: ['GET'])]
    public function index(SerializerInterface $serializer): JsonResponse
    {
        $projects = $this->projectRepository->findAll();
        $jsonContent = $serializer->serialize($projects, 'json', [
            AbstractNormalizer::ATTRIBUTES => ['id', 'name', 'description', 'company' => ['id', 'name', 'address', 'website']],
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);

        return new JsonResponse($jsonContent, 200, [], true);
    }

    #[Route('/{id}', name: 'project_show', methods: ['GET'])]
    public function show(Project $project): JsonResponse
    {
        return $this->json($project);
    }

    #[Route('/', name: 'project_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $project = new Project();
        $project->setName($data['name']);
        $project->setDescription($data['description']);
        $project->setCompany($this->getDoctrine()->getRepository(Company::class)->find($data['companyId']));

        $this->entityManager->persist($project);
        $this->entityManager->flush();

        return $this->json($project);
    }

    #[Route('/{id}', name: 'project_update', methods: ['PUT'])]
    public function update(Request $request, Project $project): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $project->setName($data['name']);
        $project->setDescription($data['description']);
        if (isset($data['companyId'])) {
            $company = $this->getDoctrine()->getRepository(Company::class)->find($data['companyId']);
            if (!$company) {
                return $this->json(['error' => 'Company not found'], 404);
            }
            $project->setCompany($company);
        }

        $this->entityManager->flush();

        return $this->json($project);
    }

    #[Route('/{id}', name: 'project_delete', methods: ['DELETE'])]
    public function delete($id): JsonResponse
    {
        $project = $this->projectRepository->find($id);

        if (!$project) {
            return $this->json(['message' => 'Project not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        try {
            $this->entityManager->remove($project);
            $this->entityManager->flush();
        } catch (\Doctrine\ORM\ORMException $e) {
            return $this->json(['error' => 'ORM Exception: ' . $e->getMessage()], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return $this->json(['error' => 'General Exception: ' . $e->getMessage()], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->json(['message' => 'Project deleted successfully']);
    }

}
