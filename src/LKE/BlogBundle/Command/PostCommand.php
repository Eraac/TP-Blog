<?php

namespace LKE\BlogBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use LKE\BlogBundle\Entity\Post;

class PostCommand extends ContainerAwareCommand
{
    const FILE = "https://docs.google.com/spreadsheets/d/1bL3B-rZNxRNUJ7le_m-i_b8hHPk9odxSgUcRYuubaVk/pub?gid=656321218&single=true&output=csv";
    const TITLE = 0;
    const CONTENT = 1;
    const PUBLISHED_AT = 2;
    const AUTHOR = 3;
    const IMAGE = 4;
    const TAGS = 5;
    const CATEGORY = 6;

    protected function configure()
    {
        $this
            ->setName('load:posts')
            ->setDescription('Load posts from spreadhseet to database')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $parser = $this->getContainer()->get('lke_core.parse_csv');

        $rows = $parser->parse(self::FILE);

        foreach($rows as $row)
        {
            $post = new Post();

            $post->setTitle($row[self::TITLE]);
            $post->setContent($row[self::CONTENT]);
            $post->setPublishedAt($this->getDateTime($row[self::PUBLISHED_AT]));
            $post->setAuthor($this->getAuthor($row[self::AUTHOR]));
            $post->setCategory($this->getCategory($row[self::CATEGORY]));
            $post->setImage($this->getImage($row[self::IMAGE]));

            $tagsId = explode(';', $row[self::TAGS]);

            foreach($tagsId as $tagId)
            {
                $tag = $this->getTag($tagId);

                if (!is_null($tag)) {
                    $post->addTag($this->getTag($tagId));
                }
            }

            $em->persist($post);
        }

        $em->flush();

        $output->writeln("Posts loaded");
    }

    private function getDateTime($datetime)
    {
        return (empty($datetime)) ? null : \DateTime::createFromFormat("d/m/Y H:i:s", $datetime);
    }

    private function getTag($id)
    {
        return $this->getContainer()->get('doctrine')->getRepository("LKEBlogBundle:Tag")->find($id);
    }

    private function getCategory($id)
    {
        return $this->getContainer()->get('doctrine')->getRepository("LKEBlogBundle:Category")->find($id);
    }

    private function getAuthor($id)
    {
        return $this->getContainer()->get('doctrine')->getRepository("LKEUserBundle:User")->find($id);
    }

    private function getImage($id)
    {
        return $this->getContainer()->get('doctrine')->getRepository("LKECoreBundle:Image")->find($id);
    }
}
