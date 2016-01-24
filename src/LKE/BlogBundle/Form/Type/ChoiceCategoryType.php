<?php

namespace LKE\BlogBundle\Form\Type;

use LKE\BlogBundle\Repository\CategoryRepository;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ChoiceCategoryType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('category', EntityType::class, [
                'placeholder' => 'lkeblog.form.category.choice_placeholder',
                'label' => 'lkeblog.form.category.choice',
                'class' => 'LKEBlogBundle:Category',
                'choice_label' => 'name',
                'query_builder' => function (CategoryRepository $cr) {
                    return $cr->createQueryBuilder('c')
                        ->orderBy('c.name');
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
            'data_class' => 'LKE\BlogBundle\Model\ChoiceCategory',
            'translation_domain' => 'form',
        ]);
    }
}
