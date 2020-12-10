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

namespace Apisearch\SyliusApisearchPlugin\Indexing;

use Apisearch\Repository\TransformableRepository;
use Apisearch\SyliusApisearchPlugin\Transformer\ProductTransformer;
use Exception;
use Sylius\Component\Core\Model\ProductInterface;

class Populate implements PopulateInterface
{
    /** @var TransformableRepository */
    private $transformableRepository;

    /** @var ProductTransformer */
    private $productTransformer;

    /**
     * Populate constructor.
     */
    public function __construct(
        TransformableRepository $transformableRepository,
        ProductTransformer $productTransformer
    ) {
        $this->transformableRepository = $transformableRepository;
        $this->productTransformer = $productTransformer;
    }

    /**
     * @throws Exception
     */
    public function populateSingle(ProductInterface $product, ?string $localeCode = null, bool $flush = true): void
    {
        $transformer = $this->productTransformer;
        $transformer->setLocaleCode($localeCode);

        $product = $transformer->toItem($product);
        $this->transformableRepository->addItem($product);

        if ($flush) {
            $this->flush();
        }
    }

    /**
     * @throws Exception
     */
    public function removeSingle(ProductInterface $product, ?string $localeCode = null, bool $flush = true): void
    {
        $transformer = $this->productTransformer;
        $transformer->setLocaleCode($localeCode);

        $product = $transformer->toItem($product);
        $this->transformableRepository->deleteItem($product->getUUID());

        if ($flush) {
            $this->flush();
        }
    }

    /**
     * @throws Exception
     */
    public function updateSingle(ProductInterface $product, ?string $localeCode = null, bool $flush = true): void
    {
        $transformer = $this->productTransformer;
        $transformer->setLocaleCode($localeCode);

        $product = $transformer->toItem($product);
        $this->transformableRepository->deleteItem($product->getUUID());
        $this->transformableRepository->addItem($product);

        if ($flush) {
            $this->flush();
        }
    }

    /**
     * @throws Exception
     */
    public function flush(): void
    {
        $this->transformableRepository->flush();
    }
}
