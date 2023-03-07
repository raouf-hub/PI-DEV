<?php

namespace App\Form;

use App\Entity\Marques;
use App\Entity\Voiture;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class VoitureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('matricule',TextType::class, [
                
                'constraints' => [
                    new NotBlank(),
                    new Regex([
                        'pattern'=>'/^\d{1,3}TU\d{1,4}$/',
                        'message'=>'le matricule doit contenir ***TU****'
                    
                ]),
            
                ]
                ])
            ->add('couleur',TextType::class, [
                
                'constraints' => [
                    new NotBlank(),
                    new Regex([
                        'pattern'=>'/^[a-zA-Z]+$/',
                        'message'=>'le nom doit contenir que des caracteres'
                    
                ]),
            
                ]
                ])
            ->add('Nombre_de_places',NumberType::class,[
    
                'constraints' => [
                    new NotBlank([
                        'message' => 'ce champ ne doit pas etre vide',
                    ]),
                    
                    new Regex([
                        'pattern'=>'/^[0-9]+$/',
                        'message'=>'veuillez entrer que des nombres'
                    
                ]),
            ]])
            ->add('Puissance',NumberType::class,[
    
                'constraints' => [
                    new NotBlank([
                        'message' => 'ce champ ne doit pas etre vide',
                    ]),
                    
                    new Regex([
                        'pattern'=>'/^[0-9]+$/',
                        'message'=>'veuillez entrer que des nombres'
                    
                ]),
            ]])
            ->add('Energie',TextType::class, [
    
                'constraints' => [
                    new NotBlank(),
                    new Regex([
                        'pattern'=>'/^[a-zA-Z0-9 ]+$/',
                        'message'=>'le lieux doit contenur que des nombres et caracteres'
                    
                ]),
            
                ]
                ])
            ->add('name',EntityType::class, [
                // looks for choices from this entity
                'class' => Marques::class,
                // uses the User.username property as the visible option string
                'choice_label' => 'nom',
                // used to render a select box, check boxes or radios
                'multiple' => false,
                'expanded' => false,
            ])

        

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Voiture::class,
        ]);
    }
}
