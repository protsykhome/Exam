<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Entity\Company;
use App\Repository\EmployeeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/employee')]
class EmployeeController extends AbstractController
{
    private $entityManager;
    private $employeeRepository;

    public function __construct(EntityManagerInterface $entityManager, EmployeeRepository $employeeRepository)
    {
        $this->entityManager = $entityManager;
        $this->employeeRepository = $employeeRepository;
    }

    #[Route('/', name: 'employee_index', methods: ['GET'])]
    public function index(SerializerInterface $serializer): JsonResponse
    {
        $employees = $this->employeeRepository->findAll();
        $jsonContent = $serializer->serialize($employees, 'json', [
            AbstractNormalizer::ATTRIBUTES => ['id', 'firstName', 'lastName', 'email', 'company' => ['id', 'name', 'address', 'website']]
        ]);

        return new JsonResponse($jsonContent, 200, [], true);
    }

    #[Route('/{id}', name: 'employee_show', methods: ['GET'])]
    public function show(Employee $employee): JsonResponse
    {
        return $this->json($employee);
    }

    #[Route('/', name: 'employee_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $employee = new Employee();
        $employee->setFirstName($data['firstName']);
        $employee->setLastName($data['lastName']);
        $employee->setEmail($data['email']);
        $employee->setCompany($this->getDoctrine()->getRepository(Company::class)->find($data['companyId']));

        $this->entityManager->persist($employee);
        $this->entityManager->flush();

        return $this->json($employee);
    }

    #[Route('/{id}', name: 'employee_update', methods: ['PUT'])]
    public function update(Request $request, $id): JsonResponse
    {
        $employee = $this->employeeRepository->find($id);

        if (!$employee) {
            return $this->json(['message' => 'Employee not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['firstName'])) {
            $employee->setFirstName($data['firstName']);
        }
        if (isset($data['lastName'])) {
            $employee->setLastName($data['lastName']);
        }
        if (isset($data['email'])) {
            $employee->setEmail($data['email']);
        }
        if (isset($data['companyId'])) {
            $company = $this->getDoctrine()->getRepository(Company::class)->find($data['companyId']);
            if ($company) {
                $employee->setCompany($company);
            } else {
                return $this->json(['message' => 'Company not found'], JsonResponse::HTTP_NOT_FOUND);
            }
        }

        $this->entityManager->flush();

        return $this->json($employee);
    }

    #[Route('/{id}', name: 'employee_delete', methods: ['DELETE'])]
    public function delete($id): JsonResponse
    {
        $employee = $this->employeeRepository->find($id);

        if (!$employee) {
            return $this->json(['message' => 'Employee not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($employee);
        $this->entityManager->flush();

        return $this->json(['message' => 'Employee deleted successfully']);
    }
}
