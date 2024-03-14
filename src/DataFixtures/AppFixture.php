<?php
namespace App\DataFixtures;

use App\Entity\CustomerEntity;
use App\Entity\ReservationEntity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixture extends Fixture
{
    // Arrays of real first names and surnames
    private $firstNames = ['John', 'Mary', 'James', 'Elizabeth', 'William', 'Emma', 'David', 'Sarah', 'Michael', 'Emily', 'Joseph', 'Samantha', 'Charles', 'Olivia', 'Robert', 'Sophia', 'Thomas', 'Ava', 'Daniel', 'Isabella'];
    private $lastNames = ['Smith', 'Johnson', 'Williams', 'Jones', 'Brown', 'Davis', 'Miller', 'Wilson', 'Moore', 'Taylor', 'Anderson', 'Thomas', 'Jackson', 'White', 'Harris', 'Martin', 'Thompson', 'Garcia', 'Martinez', 'Robinson'];

    public function load(ObjectManager $manager)
    {
        // Create 20 customers
        for ($i = 0; $i < 20; $i++) {
            $customer = new CustomerEntity();
            $customer->setFirstName($this->firstNames[array_rand($this->firstNames)]); // Random first name from the array
            $customer->setLastName($this->lastNames[array_rand($this->lastNames)]); // Random last name from the array
            $customer->setEmail(strtolower($customer->getFirstName() . '.' . $customer->getLastName() . '@example.com')); // Generate email based on first and last name

            // Generate date_created in range from 2022 to 2023
            $customer->setDateCreated($this->generateRandomDate('2022-01-01', '2023-12-31'));

            $manager->persist($customer);

            // Create reservations for each customer
            for ($j = 0; $j < mt_rand(1, 5); $j++) {
                $reservation = new ReservationEntity();
                $reservation->setAdults(mt_rand(1, 4)); // Random number of adults (1-4)
                $reservation->setStatus(mt_rand(0, 2)); // Random status (0-2)
                $reservation->setNotice($this->generateRandomText(50)); // Random notice text
                $reservation->setChildren(mt_rand(0, 3)); // Random number of children (0-3)
                $reservation->setBreakfast((bool)mt_rand(0, 1)); // Random boolean value for breakfast
                $reservation->setDateFrom($this->generateRandomDate('2023-01-01', '2024-12-31')); // Random date from 2023-2024
                $reservation->setDateTo($this->generateRandomDate($reservation->getDateFrom()->format('Y-m-d'), '2024-12-31')); // Random date to after date from
                $reservation->setDateCreated($this->generateRandomDate('2022-01-01', '2023-12-31'));

                $manager->persist($reservation);
                
                // Associate reservation with the customer
                $customer->addReservation($reservation);
            }
        }

        $manager->flush();
    }

    // Function to generate random text
    private function generateRandomText($length = 100)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ ';
        $text = '';
        for ($i = 0; $i < $length; $i++) {
            $text .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $text;
    }

    // Function to generate random date
    private function generateRandomDate($startDate, $endDate)
    {
        $randomTimestamp = mt_rand(strtotime($startDate), strtotime($endDate));
        return new \DateTime(date('Y-m-d', $randomTimestamp));
    }
}
