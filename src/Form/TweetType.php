<?php

namespace App\Form;

use App\Entity\Tweet;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TweetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('content', TextareaType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Qu\'est ce qui se passe ?',
                    'maxlength' => 280,
                    'style' => 'resize: none;',
                    'rows' => 4,
                    'cols' => 60,
                    'class' => 'text-neutral-400 border-1 border-neutral-600 rounded-lg p-3'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tweet::class,
            'crsf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id'   => 'tweet_item',
        ]);
    }
}
