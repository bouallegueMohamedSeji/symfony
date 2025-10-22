<?php

namespace App\Form;

use App\Entity\Author;
use App\Entity\Book;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('publicationDate', DateType::class, [
                'widget' => 'choice', // Style de l'atelier [cite: 37]
                'years' => range(date('Y') - 20, date('Y') + 5),
            ])
            ->add('category', ChoiceType::class, [ // Champ de l'atelier 
                'choices' => [
                    'Science-Fiction' => 'Science-Fiction',
                    'Mystery' => 'Mystery',
                    'Autobiography' => 'Autobiography',
                ],
                'placeholder' => 'Select a category'
            ])
            ->add('author', EntityType::class, [
                'class' => Author::class,
                'choice_label' => 'authorName', // Plus lisible que 'id'
                'placeholder' => 'Select an author'
            ])
            ->add('save', SubmitType::class, ['label' => 'Save']); // Bouton Save
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}