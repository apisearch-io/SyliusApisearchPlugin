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

namespace Apisearch\SyliusApisearchPlugin\EventListener;

use Apisearch\SyliusApisearchPlugin\Indexing\PopulateInterface;
use Exception;
use Sylius\Component\Core\Model\ProductInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class ProductListener
{
    /** @var PopulateInterface */
    private $populate;

    /**
     * ProductListener constructor.
     */
    public function __construct(PopulateInterface $populate)
    {
        $this->populate = $populate;
    }

    /**
     * @throws Exception
     */
    public function onCreate(GenericEvent $event): void
    {
        /** @var ProductInterface $product */
        $product = $event->getSubject();
        if (null === $product) {
            return;
        }

        $this->populate->populateSingle($product, null, true);
    }

    /**
     * @throws Exception
     */
    public function onUpdate(GenericEvent $event): void
    {
        /** @var ProductInterface $product */
        $product = $event->getSubject();
        if (null === $product) {
            return;
        }

        $this->populate->updateSingle($product, null, true);
    }

    /**
     * @throws Exception
     */
    public function onDelete(GenericEvent $event): void
    {
        /** @var ProductInterface $product */
        $product = $event->getSubject();
        if (null === $product) {
            return;
        }

        $this->populate->removeSingle($product, null, true);
    }
}
