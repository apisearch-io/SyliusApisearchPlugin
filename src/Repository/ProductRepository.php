<?php

/*
 * This file is part of the Sylius Apisearch Plugin
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 */

declare(strict_types=1);

namespace Apisearch\SyliusApisearchPlugin\Repository;

use Sylius\Bundle\CoreBundle\Doctrine\ORM\ProductRepository as BaseProductRepository;
use Sylius\Component\Core\Model\ProductInterface;

final class ProductRepository implements ProductRepositoryInterface
{
    /** @var BaseProductRepository */
    private $productRepository;

    public function __construct(BaseProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function findOneByCodeAndLocale(string $code, string $locale): ?ProductInterface
    {
        return $this->productRepository
            ->createQueryBuilder('o')
            ->where('o.code = :code')
            ->innerJoin('o.translations', 'translation', 'WITH', 'translation.locale = :locale')
            ->setParameter('code', $code)
            ->setParameter('locale', $locale)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function findEnabledByLocale(string $locale, int $limit, int $offset): array
    {
        return $this->productRepository
            ->createListQueryBuilder($locale)
            ->where('o.enabled = true')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult()
        ;
    }

    public function countEnabledByLocale(string $locale): int
    {
        return (int) $this->productRepository
            ->createListQueryBuilder($locale)
            ->select('count(o.id)')
            ->where('o.enabled = true')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }
}
