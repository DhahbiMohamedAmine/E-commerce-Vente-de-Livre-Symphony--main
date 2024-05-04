<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Livres;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChercherType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Rechercher par titre',
                'required' => false, // Allow the search field to be empty
            ])
            ->add('auteur', TextType::class, [
                'label' => 'Rechercher par auteur',
                'required' => false, // Allow the search field to be empty
            ])

            ->add('categorie', EntityType::class, [
                'class' => Categorie::class,
'choice_label' => 'libelle',
                'required' => false, // Allow the category field to be empty
                'placeholder' => 'Choisir une catégorie',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Livres::class,
        ]);
    }
}
