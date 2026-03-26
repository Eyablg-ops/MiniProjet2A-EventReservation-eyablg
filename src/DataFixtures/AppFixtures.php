<?php

namespace App\DataFixtures;

use App\Entity\Event;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Créer un utilisateur admin
        $admin = new User();
        $admin->setEmail('admin@example.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'admin123'));
        $manager->persist($admin);

        // Créer des utilisateurs normaux
        $user1 = new User();
        $user1->setEmail('eya@example.com');
        $user1->setRoles(['ROLE_USER']);
        $user1->setPassword($this->passwordHasher->hashPassword($user1, 'eya123'));
        $manager->persist($user1);

        $user2 = new User();
        $user2->setEmail('sara@example.com');
        $user2->setRoles(['ROLE_USER']);
        $user2->setPassword($this->passwordHasher->hashPassword($user2, 'sara123'));
        $manager->persist($user2);

        // Créer des événements
        $event1 = new Event();
        $event1->setTitle('Conférence Symfony 7.4');
        $event1->setDescription('Découvrez les nouvelles fonctionnalités de Symfony 7.4');
        $event1->setDate(new \DateTime('2026-04-15 10:00:00'));
        $event1->setLocation('Salle A1, ISSAT');
        $event1->setSeats(50);
        $manager->persist($event1);

        $event2 = new Event();
        $event2->setTitle('Atelier Docker & Conteneurisation');
        $event2->setDescription('Apprenez à conteneuriser vos applications avec Docker');
        $event2->setDate(new \DateTime('2026-04-20 14:00:00'));
        $event2->setLocation('Salle B2, ISSAT');
        $event2->setSeats(30);
        $manager->persist($event2);

        $event3 = new Event();
        $event3->setTitle('Meetup WebAuthn & Sécurité');
        $event3->setDescription('Discussion sur l\'authentification moderne avec les Passkeys');
        $event3->setDate(new \DateTime('2026-04-25 16:00:00'));
        $event3->setLocation('Salle C3, ISSAT');
        $event3->setSeats(40);
        $manager->persist($event3);

        $manager->flush();
    }
}
