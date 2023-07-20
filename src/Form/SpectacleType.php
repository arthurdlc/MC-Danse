<?php

namespace App\Form;

use App\Entity\Spectacle;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class SpectacleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('date', DateTimeType::class, [
                'date_label' => 'Starts On',
            ])
            ->add('lieu')
            ->add('tarif', MoneyType::class, [
                'divisor' => 1,
            ])
            ->add('image', FileType::class, [
                'label' => 'Illustration',
    
                // unmapped means that this field is not associated to any entity property
                'mapped' => false, 
    
                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => true,
    
                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '2048k',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpeg',
                            'image/jpg',
                            'image/webp',
                        ],
                        'mimeTypesMessage' => 'Image non valide, veuillez choisir une image valide',
                    ])
                ],
            ])
            ->add('contenue',CKEditorType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Spectacle::class,
        ]);
    }
}
