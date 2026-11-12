<?php

namespace App\Form;

use App\Entity\Artist;
use App\Entity\Release;
use App\Enum\ReleaseType as EnumReleaseType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReleaseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type', ChoiceType::class, [
                'expanded' => true,
                'multiple' => false,
                'choices' => [
                    'Album' => EnumReleaseType::Album,
                    'EP' => EnumReleaseType::EP,
                    'Single' => EnumReleaseType::Single,
                ],
            ])
            ->add('title')
            ->add('releasedAt', null, [
                'widget' => 'single_text'
            ])
            ->add('thumbnail')
            ->add('artist', EntityType::class, [
                'class' => Artist::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Release::class,
        ]);
    }
}
