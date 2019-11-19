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

namespace Apisearch\SyliusApisearchPlugin;

use Apisearch\ApisearchBundle;
use Sylius\Bundle\CoreBundle\Application\SyliusPluginTrait;
use Mmoreram\BaseBundle\BaseBundle;
use Symfony\Component\HttpKernel\KernelInterface;

final class SyliusApisearchPlugin extends BaseBundle
{
    use SyliusPluginTrait;

    public static function getBundleDependencies(KernelInterface $kernel): array
    {
        return [
            ApisearchBundle::class,
        ];
    }
}
