<?php

namespace App\Controller;

use App\Entity\Company;
use App\Repository\CompanyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

#[Route('/api/company')]
class CompanyController extends AbstractController
{
    private $entityManager;
    private $companyRepository;

    public function __construct(EntityManagerInterface $entityManager, CompanyRepository $companyRepository)
    {
        $this->entityManager = $entityManager;
        $this->companyRepository = $companyRepository;
    }

    #[Route('/', name: 'company_index', methods: ['GET'])]
    public function index(SerializerInterface $serializer): JsonResponse
    {
        $companies = $this->companyRepository->findAll();
        $jsonContent = $serializer->serialize($companies, 'json', [
            AbstractNormalizer::ATTRIBUTES => ['id', 'name', 'address', 'website']
        ]);

        return new JsonResponse($jsonContent, 200, [], true);
    }

    /**
     * @Route("/companies/{id}", name="company_show", methods={"GET"})
     */
    public function show(CompanyRepository $companyRepository, SerializerInterface $serializer, $id): JsonResponse
    {
        $company = $companyRepository->find($id);

        $companyClone = clone $company;

        $jsonData = $serializer->serialize($companyClone, 'json', ['groups' => 'company', 'maxDepth' => 1]);

        return new JsonResponse($jsonData, 200, [], true);
    }

    #[Route('/', name: 'company_create', methods: ['POST'])]
    public function create(Request $request, LoggerInterface $logger): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $logger->info('Received data: ', $data);

        $company = new Company();
        $company->setName($data['name']);
        $company->setAddress($data['address']);
        $company->setWebsite($data['website']);

        $this->entityManager->persist($company);
        $this->entityManager->flush();

        return new JsonResponse([
            'success' => true,
            'message' => 'Company created successfully!',
            'company' => [
                'id' => $company->getId(),
                'name' => $company->getName(),
                'address' => $company->getAddress(),
                'website' => $company->getWebsite(),
            ],
        ], JsonResponse::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'company_update', methods: ['PUT'])]
    public function update(Request $request, Company $company): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $company->setName($data['name']);
        $company->setAddress($data['address']);
        $company->setWebsite($data['website']);

        $this->entityManager->flush();

        return $this->json($company);
    }

    #[Route('/{id}', name: 'company_delete', methods: ['DELETE'])]
    public function delete(Company $company): JsonResponse
    {
        $this->entityManager->remove($company);
        $this->entityManager->flush();

        return $this->json(['message' => 'Company deleted successfully']);
    }
}
