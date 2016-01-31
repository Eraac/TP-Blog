<?php

namespace LKE\BlogBundle\Command;

use LKE\BlogBundle\Entity\Tag;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TagCommand extends ContainerAwareCommand
{
    const FILE = "https://docs.google.com/spreadsheets/d/1bL3B-rZNxRNUJ7le_m-i_b8hHPk9odxSgUcRYuubaVk/pub?gid=0&single=true&output=csv";
    const NAME = 0;

    protected function configure()
    {
        $this
            ->setName('load:tags')
            ->setDescription('Load tags from spreadhseet to database')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();

        $parser = $this->getContainer()->get('lke_core.parse_csv');
        $rows = $parser->parse(self::FILE);

        foreach($rows as $row)
        {
            $tag = new Tag();
            $tag->setName($row[self::NAME]);

            $em->persist($tag);
        }

        $em->flush();

        $output->writeln("Tags loaded");
    }
}
