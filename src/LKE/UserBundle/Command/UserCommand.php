<?php

namespace LKE\UserBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UserCommand extends ContainerAwareCommand
{
    const FILE = "https://docs.google.com/spreadsheets/d/1bL3B-rZNxRNUJ7le_m-i_b8hHPk9odxSgUcRYuubaVk/pub?gid=78338165&single=true&output=csv";
    const USERNAME = 0;
    const EMAIL = 1;
    const PASSWORD = 2;
    const ROLES = 3;

    protected function configure()
    {
        $this
            ->setName('load:users')
            ->setDescription('Load users from spreadhseet to database')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $userManager = $this->getContainer()->get('fos_user.user_manager');
        $em = $this->getContainer()->get('doctrine')->getManager();

        $parser = $this->getContainer()->get('lke_core.parse_csv');
        $rows = $parser->parse(self::FILE);

        foreach($rows as $row)
        {
            $user = $userManager->createUser();

            $user->setEmail($row[self::EMAIL]);
            $user->setUsername($row[self::USERNAME]);
            $user->setPlainPassword($row[self::PASSWORD]);
            $user->setEnabled(true);

            $roles = explode(';', $row[self::ROLES]);

            foreach($roles as $role) {
                $user->addRole($role);
            }

            $userManager->updateUser($user, false);
        }

        $em->flush();

        $output->writeln("Users loaded");
    }
}
