<?php

namespace Sulu\Bundle\ProductBundle\Repository;

use Doctrine\ORM\QueryBuilder;
use Sylius\Bundle\ProductBundle\Doctrine\ORM\ProductRepository as BaseProductRepository;

class ProductRepository extends BaseProductRepository
{
    public function createListQueryBuilder(string $locale): QueryBuilder
    {
        return $this->createQueryBuilder('o')
            ->select('o.id')
            ->addSelect('o.code')
            ->addSelect('translation.name')
            ->addSelect('translation.description')
            ->innerJoin('o.translations', 'translation', 'WITH', 'translation.locale = :locale')
            ->setParameter('locale', $locale);
    }
}
