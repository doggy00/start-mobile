<?php

namespace App\Controller\Admin;

use App\Entity\Authors;
use App\Repository\AuthorsRepository;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class AuthorsCrudController extends AbstractCrudController
{
    public function __construct(
        private readonly AuthorsRepository $authorsRepository
    )
    {
    }

    public static function getEntityFqcn(): string
    {
        return Authors::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            Field::new('booksCount')
                ->setSortable(true)
                ->formatValue(fn ($v, $author) => $this->authorsRepository->getBooksCount($author))
                ->onlyOnIndex()
        ];
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $qb = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);
        $sort = $searchDto->getSort();

        if (isset($sort['booksCount'])) {
            $qb
                ->addSelect('(SELECT COUNT(b.id) FROM \App\Entity\Books b WHERE b.author = entity) AS HIDDEN booksCount')
                ->orderBy('booksCount', $sort['booksCount'])
                ->groupBy('entity.id');
        }

        return $qb;
    }
}
