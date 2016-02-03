<?php

namespace LKE\BlogBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use LKE\BlogBundle\Entity\Comment;

class CommentCommand extends ContainerAwareCommand
{
    const FILE = "https://docs.google.com/spreadsheets/d/1bL3B-rZNxRNUJ7le_m-i_b8hHPk9odxSgUcRYuubaVk/pub?gid=695020760&single=true&output=csv";
    const USERNAME = 0;
    const CONTENT = 1;
    const POST_ID = 2;

    protected function configure()
    {
        $this
            ->setName('load:comments')
            ->setDescription('Load comments from spreadhseet to database')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $parser = $this->getContainer()->get('lke_core.parse_csv');

        $rows = $parser->parse(self::FILE);

        foreach($rows as $row)
        {
            $comment = new Comment();

            $comment->setUsername($row[self::USERNAME]);
            $comment->setContent($row[self::CONTENT]);
            $comment->setPost($this->getPost($row[self::POST_ID]));

            $em->persist($comment);
        }

        $em->flush();

        $output->writeln("Comments loaded");
    }

    /**
     * @param $id
     * @return \LKE\BlogBundle\Entity\Post|null
     */
    private function getPost($id)
    {
        return $this->getContainer()->get('doctrine')->getRepository("LKEBlogBundle:Post")->find($id);
    }
}
