<?php

namespace App\Form;

use App\Entity\Tweet;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints as Assert;

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
                    'class' => 'text-white border border-white rounded-lg p-3 bg-[#15202B]'
                ],
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[\p{L}\p{N}\s_\-.,!?()\'"]+$/u',
                        'message' => 'Le tweet doit contenir seulement des lettres, chiffres ou underscores.'
                    ]),
                    new Assert\NotBlank([
                        'message' => 'Le tweet ne peut pas être vide.'
                    ])
                ]
            ])
            ->add('media', FileType::class, [
                'label' => 'Média (image)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/webp',
                            'image/gif',
                        ],
                        'mimeTypesMessage' => 'Merci de choisir une image valide (jpeg, png, gif)',
                    ])
                ],
                'attr' => [
                    'class' => 'text-white'
                ]
            ]);;
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
