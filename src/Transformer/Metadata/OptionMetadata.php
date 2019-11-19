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

class OptionMetadata implements MetadataInterface
{
    /** @var ApisearchConfigurationInterface */
    private $configuration;

    /**
     * OptionMetadata constructor.
     */
    public function __construct(ApisearchConfigurationInterface $configuration)
    {
        $this->configuration = $configuration;
    }

    public function getMetadata(ProductInterface $product): Generator
    {
        $variants = $product->getVariants();
        if (null === $variants) {
            return;
        }

        $allowedFields = array_column(
            $this->configuration->getFilters(Element::FILTER_OPTION),
            'field'
        );

        $items = [];
        foreach ($variants as $variant) {
            $options = $variant->getOptionValues();
            if (0 === $options->count()) {
                continue;
            }

            foreach ($options as $option) {
                $optionCode = $option->getOptionCode();
                if (!in_array($optionCode, $allowedFields)) {
                    continue;
                }

                $items[] = $option->getValue();

                yield $optionCode => $items;
            }
        }
    }
}
