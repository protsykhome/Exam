<?php

namespace App\DataFixtures;

use App\Entity\Company;
use App\Entity\Employee;
use App\Entity\Project;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        // Створення компаній
        for ($i = 0; $i < 10; $i++) {
            $company = new Company();
            $company->setName($faker->company);
            $company->setAddress($faker->address);
            $company->setWebsite($faker->url);

            $manager->persist($company);

            // Створення працівників для кожної компанії
            for ($j = 0; $j < 10; $j++) {
                $employee = new Employee();
                $employee->setFirstName($faker->firstName);
                $employee->setLastName($faker->lastName);
                $employee->setEmail($faker->email);
                $employee->setCompany($company);

                $manager->persist($employee);
            }

            // Створення проектів для кожної компанії
            for ($k = 0; $k < 5; $k++) {
                $project = new Project();
                $project->setName($faker->sentence);
                $project->setDescription($faker->paragraph);
                $project->setCompany($company);

                $manager->persist($project);
            }
        }

        $manager->flush();
    }
}
