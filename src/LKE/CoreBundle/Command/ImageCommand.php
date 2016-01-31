<?php

namespace LKE\CoreBundle\Command;

use LKE\CoreBundle\Entity\Image;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImageCommand extends ContainerAwareCommand
{
    const FILE = "https://docs.google.com/spreadsheets/d/1bL3B-rZNxRNUJ7le_m-i_b8hHPk9odxSgUcRYuubaVk/pub?gid=1994578344&single=true&output=csv";
    const FILENAME = 0;
    const NAME = 1;

    protected function configure()
    {
        $this
            ->setName('load:images')
            ->setDescription('Load images from spreadhseet to database')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();

        $parser = $this->getContainer()->get('lke_core.parse_csv');
        $rows = $parser->parse(self::FILE);

        foreach($rows as $row)
        {
            $image = new Image();
            $image->setName($this->getImage($row[self::FILENAME], $row[self::NAME]));

            $em->persist($image);
        }

        $em->flush();

        $output->writeln("Images loaded");
    }

    private function getImage($filename, $name)
    {
        $path = $this->getContainer()->getParameter("upload_dir") . "images/post/";

        $file = new \SplFileObject($path . $name, "w");

        $file->fwrite(file_get_contents($filename));

        return $file->getFilename();
    }
}
