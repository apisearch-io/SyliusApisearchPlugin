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

namespace Apisearch\SyliusApisearchPlugin\Transformer\Metadata;

use Apisearch\SyliusApisearchPlugin\Configuration\ApisearchConfigurationInterface;
use Apisearch\SyliusApisearchPlugin\Element;
use function array_column;
use Generator;
use function in_array;
use Sylius\Component\Core\Model\ProductInterface;

class AttributeMetadata implements MetadataInterface
{
    /** @var ApisearchConfigurationInterface */
    private $configuration;

    /**
     * AttributeMetadata constructor.
     */
    public function __construct(ApisearchConfigurationInterface $configuration)
    {
        $this->configuration = $configuration;
    }

    public function getMetadata(ProductInterface $product): Generator
    {
        $attributes = $product->getAttributes();
        if (null === $attributes) {
            return;
        }

        $allowedFields = array_column(
            $this->configuration->getFilters(Element::FILTER_ATTRIBUTE),
            'field'
        );

        foreach ($attributes as $attribute) {
            $code = $attribute->getCode();
            if (in_array($code, $allowedFields)) {
                yield $code => $attribute->getValue();
            }
        }
    }
}
