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

namespace Apisearch\SyliusApisearchPlugin\Populate;

use Apisearch\Repository\TransformableRepository;
use Apisearch\SyliusApisearchPlugin\Transformer\ProductTransformer;
use Sylius\Bundle\CoreBundle\Doctrine\ORM\ProductRepository;
use Sylius\Component\Core\Model\ProductInterface;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\OutputInterface;
use Webmozart\Assert\Assert;

class Populate implements PopulateInterface
{
    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * @var TransformableRepository
     */
    private $transformableRepository;

    /**
     * @var ProductTransformer
     */
    private $productTransformer;

    /**
     * @var array
     */
    private $searchProductAttribute = [
        'enabled' => true,
    ];

    /**
     * Populate constructor.
     *
     * @param ProductRepository $productRepository
     * @param TransformableRepository $transformableRepository
     * @param ProductTransformer $productTransformer
     */
    public function __construct(
        ProductRepository $productRepository,
        TransformableRepository $transformableRepository,
        ProductTransformer $productTransformer
    ) {
        $this->productRepository = $productRepository;
        $this->transformableRepository = $transformableRepository;
        $this->productTransformer = $productTransformer;
    }

    /**
     * @param OutputInterface $output
     * @param int $perPage
     *
     * @throws \Exception
     */
    public function populate(OutputInterface $output, int $perPage): void
    {
        $countProductsToIndex = $this->productRepository->count(
            $this->searchProductAttribute
        );

        Assert::greaterThan($countProductsToIndex, 0);

        $progressBar = new ProgressBar($output, $countProductsToIndex);

        $page = 1;
        while (true) {
            $offset = ($page - 1) * $perPage;
            $model = $this->productRepository->findBy(
                $this->searchProductAttribute,
                [],
                $perPage,
                $offset
            );

            if (null === $model) {
                $progressBar->finish();

                return;
            }

            /** @var ProductInterface $product */
            foreach ($model as $product) {
                $this->populateSingle($product, false);
                $progressBar->advance();
            }

            $this->transformableRepository->flush();
            ++$page;
        }
    }

    /**
     * @param ProductInterface $product
     * @param bool $flush
     *
     * @throws \Exception
     */
    public function populateSingle(ProductInterface $product, bool $flush = true): void
    {
        $product = $this->productTransformer->toItem($product);
        $this->transformableRepository->addItem($product);

        if ($flush) {
            $this->transformableRepository->flush();
        }
    }
}
