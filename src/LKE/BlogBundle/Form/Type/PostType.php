<?php

namespace LKE\BlogBundle\Form\Type;

use LKE\CoreBundle\Form\Type\ImageType;
use LKE\BlogBundle\Repository\CategoryRepository;
use LKE\BlogBundle\Repository\TagRepository;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class PostType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, ['label' => 'lkeblog.form.post.title'])
            ->add('content', TextareaType::class, ['label' => 'lkeblog.form.post.content']) // TODO Add wysiwyg

            ->add('publishedAt', DateTimeType::class, [
                'label' => 'lkeblog.form.post.publishedAt',
                'required' => false,
            ])

            ->add('category', EntityType::class, [
                'placeholder' => 'lkeblog.form.post.category_placeholder',
                'label' => 'lkeblog.form.post.category',
                'class' => 'LKEBlogBundle:Category',
                'choice_label' => 'name',
                'query_builder' => function (CategoryRepository $cr) {
                    return $cr->createQueryBuilder('c')
                        ->orderBy('c.name', 'ASC');
                },
            ])

            ->add('image', new ImageType(), [
                'label' => false,
                'required' => false,
            ])

            ->add('tags', EntityType::class, [
                'placeholder' => false, // Bug avec le framework CSS (materialize.css), il affiche toujours le 1er élément de la liste (sauf si on le coche/décoche)
                'label' => 'lkeblog.form.post.tags',
                'class' => 'LKEBlogBundle:Tag',
                'choice_label' => 'name',
                'multiple' => true,
                'required' => false,
                'query_builder' => function (TagRepository $tr) {
                    return $tr->createQueryBuilder('t')
                        ->orderBy('t.name', 'ASC');
                },
            ])

            ->add('submit', 'submit', ['label' => 'lkeblog.form.submit'])
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'LKE\BlogBundle\Entity\Post',
            'translation_domain' => 'form',
        ]);
    }
}
