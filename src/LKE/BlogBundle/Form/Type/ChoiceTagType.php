<?php

namespace LKE\BlogBundle\Form\Type;

use LKE\BlogBundle\Repository\TagRepository;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ChoiceTagType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('tag', EntityType::class, [
                'placeholder' => 'lkeblog.form.tag.choice_placeholder',
                'label' => 'lkeblog.form.tag.choice',
                'class' => 'LKEBlogBundle:Tag',
                'choice_label' => 'name',
                'query_builder' => function (TagRepository $tr) {
                    return $tr->createQueryBuilder('t')
                        ->orderBy('t.name');
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
            'data_class' => 'LKE\BlogBundle\Model\ChoiceTag',
            'translation_domain' => 'form',
        ]);
    }
}
