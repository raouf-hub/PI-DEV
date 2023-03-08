<?php

namespace App\Form;

use App\Entity\Contrat;
use App\Entity\Voiture;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;


class ContratType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        
            // ->add('date_fin')
            ->add('periode',ChoiceType::class, [
                'choices'  => [
                    '3 mois' => '3M',
                    '6 mois' => '6M',
                    '12 mois' => '12M',
                ],
                'mapped'=>false,
            ])
            ->add('paymentLink', null, [
                'constraints' => [
                   
                   
                ],])
            // ->add('type_de_contrat'
            // ,TextType::class, [
    
            //     'constraints' => [
            //         new NotBlank(),
            //         new Regex([
            //             'pattern'=>'/^[a-zA-Z0-9 ]+$/',
            //             'message'=>'le lieux doit contenir que des nombres et caracteres'
                    
            //     ]),
            
            //     ]
               // ])
            //    $form->handleRequest($request);
            //    $badWords = $this->filterwords($Commentaire->getContenuCommentairer() . ' ' . $Commentaire->getContenuCommentairer());
            //    if (strpos($badWords, '**') !== false) {
            //     $this->addFlash('info', 'Faites attention a ce que vous tapez  ! un peu de respect !!');
            //    }
                ->add('type_de_contrat',ChoiceType::class, [
                    'choices'  => [
                        'assurance au tiers' => 'assurance au tiers',
                        'assurance au tiers plus' => 'assurance au tiers plus',
                        'assurance tous risques' => 'assurance tous risques',
                        
                        // $this->addFlash('info', 'Faites attention a ce que vous tapez  ! un peu de respect !!')


                    ],
                    //'mapped'=>false,
                ])
                
                // ->add('public', CheckboxType::class, [
                //     'label'    => 'Show this entry publicly?',
                //     'required' => false,
                // ])
            ->add('matricule',EntityType::class, [
                // looks for choices from this entity
                'class' => Voiture::class,
                // uses the User.username property as the visible option string
                'choice_label' => 'matricule',
                // used to render a select box, check boxes or radios
                'multiple' => false,
                'expanded' => false,
            ])
            ->add('image', FileType::class, [
                'label' => 'votre image de profil (Image file)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '100000k',
                        'mimeTypes' => [
                            'image/gif',
                            'image/jpeg',
                            'image/jpg',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid Image',
                    ])
                ],
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contrat::class,
        ]);
    }
}
