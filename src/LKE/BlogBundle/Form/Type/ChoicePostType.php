<?php

namespace LKE\BlogBundle\Form\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use LKE\BlogBundle\Repository\PostRepository;

class ChoicePostType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('post', EntityType::class, [
                'placeholder' => 'lkeblog.form.post.choice_placeholder',
                'label' => 'lkeblog.form.post.choice',
                'class' => 'LKEBlogBundle:Post',
                'choice_label' => 'title',
                'query_builder' => function (PostRepository $pr) {
                    return $pr->createQueryBuilder('p')
                        ->orderBy('p.title');
                },
            ])
            ->add('edit', 'submit', [
                'label' => 'lkeblog.form.edit',
                'attr' => ['class' => 'orange']
            ])
            ->add('remove', 'submit', [
                'label' => 'lkeblog.form.remove',
                'attr' => ['class' => 'red'] // TODO class does not work
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'LKE\BlogBundle\Model\ChoicePost',
            'translation_domain' => 'form',
        ]);
    }
}
