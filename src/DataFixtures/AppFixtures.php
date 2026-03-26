<?php
namespace App\DataFixtures;

use App\Entity\Event;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $hasher
    ) {}

    public function load(ObjectManager $manager): void
    {
        // Créer l'admin
        $admin = new User();
        $admin->setEmail('admin@example.com');
        $admin->setPassword($this->hasher->hashPassword($admin, 'admin123'));
        $admin->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);

        // Créer des utilisateurs normaux pour tester la réservation
        $user1 = new User();
        $user1->setEmail('eya@example.com');
        $user1->setPassword($this->hasher->hashPassword($user1, 'eya123'));
        $user1->setRoles(['ROLE_USER']);
        $manager->persist($user1);

        $user2 = new User();
        $user2->setEmail('sara@example.com');
        $user2->setPassword($this->hasher->hashPassword($user2, 'sara123'));
        $user2->setRoles(['ROLE_USER']);
        $manager->persist($user2);

        // Créer les événements
        $events = [
            ['Concert de Jazz', 'Un magnifique concert en plein air.', '2026-06-15 20:00:00', 'Amphithéâtre de Sousse', 100],
            ['Festival du Film', 'Projection de films indépendants.', '2026-07-01 18:00:00', 'Cinéma de Monastir', 200],
            ['Hackathon ISSAT 2026', 'Concours de programmation 24h.', '2026-05-20 09:00:00', 'ISSAT Sousse', 50],
        ];

        foreach ($events as [$title, $desc, $date, $location, $seats]) {
            $event = new Event();
            $event->setTitle($title);
            $event->setDescription($desc);
            $event->setDate(new \DateTime($date));
            $event->setLocation($location);
            $event->setSeats($seats);
            $manager->persist($event);
        }

        $manager->flush();
    }
}