<?php


namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class PromoteAdmin extends Command
{
    private $em;

    protected static $defaultName = "user:make:admin";

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        parent::__construct();

    }

    protected function configure()
    {
        $this
            ->setDescription('Promote a user to role Admin')
            ->addOption(
             'user',
             'u',
             InputOption::VALUE_REQUIRED,
             'user mail '

            )
            ;
    }

    protected function execute(InputInterface $input , OutputInterface $output){
        try{
            $userEmail = $input->getOption('user');
            /** @var User $user */
            $user = $this->em->getRepository(User::class)->findOneBy([
                'email' => $userEmail
            ]);
            if(!$user){
                $output->writeln("<bg=red>impossible de trouver l'utilisateur</>");
                return Command::SUCCESS;
            }

            $user->setRoles([
               'ROLE_ADMIN'
            ]);
            $this->em->persist($user);
            $this->em->flush();

            $output->writeln("<bg=blue>Utilisateur $userEmail a bien été promu <fg=green>ADMIN</></>");

           return Command::SUCCESS;
        }
        catch(\Exception $e){
            return Command::FAILURE;
        }
    }


}