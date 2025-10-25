<?php

namespace App\Controller\Admin;

use App\Entity\Books;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class BooksCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Books::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title'),
            AssociationField::new('author')
                ->formatValue(
                    static fn ($value, $book) => $book->getAuthor()->getName()
                )
                ->setFormTypeOptions([
                    'by_reference' => true,
                    'choice_label' => 'name',
                ]),
        ];
    }
}
