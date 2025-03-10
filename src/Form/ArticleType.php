<?php

namespace App\Form;

use App\Entity\Account;
use App\Entity\Article;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('title', TextType::class, [
            'label' => 'Titre',
            'attr' => [
                'placeholder' => 'Saisir le titre de l\'article'
            ]
        ])
        ->add('content', TextareaType::class, [
            'label' => 'Contenu',
            'attr' => [
                'placeholder' => 'Saisir le contenu de l\'article'
            ]
        ])
        ->add('author', EntityType::class, [
            'class' => Account::class,
            'choice_label' => 'username',
            'label' => 'Auteur',
        ])
        ->add('categories', EntityType::class, [
            'class' => Category::class,
            'choice_label' => 'name',
            'multiple' => true,
            'label' => 'Catégories',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
