<?php
// src/Command/CreateAdminCommand.php
namespace App\Command;
 
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
 
#[AsCommand(name: 'app:create-admin', description: 'Cree un compte administrateur')]
class CreateAdminCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $em,
        private UserPasswordHasherInterface $hasher
    ) { parent::__construct(); }
 
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $admin = new User();
        $admin->setEmail('admin@example.com');
        $admin->setPassword($this->hasher->hashPassword($admin, 'admin123'));
        $admin->setRoles(['ROLE_ADMIN']);
 
        $this->em->persist($admin);
        $this->em->flush();
 
        $output->writeln('<info>Admin cree : admin@example.com / admin123</info>');
        $output->writeln('<comment>Pensez a changer le mot de passe !</comment>');
        return Command::SUCCESS;
    }
}
