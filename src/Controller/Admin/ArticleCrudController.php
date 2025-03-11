<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Controller\Admin\AccountCrudController;
use App\Controller\Admin\CategoryCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;

class ArticleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Article::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id', 'ID')->hideOnForm(),
            TextField::new('title', 'Titre'),
            TextEditorField::new('content', 'Contenu'),
            DateTimeField::new('createAt', 'Date de création'),
            AssociationField::new('author', 'Auteur')->hideOnIndex()->setCrudController(AccountCrudController::class)
                ->autocomplete(),
            AssociationField::new('categories', 'Catégorie(s)')->hideOnIndex()->setCrudController(CategoryCrudController::class)
                ->autocomplete()
        ];
    }
}
