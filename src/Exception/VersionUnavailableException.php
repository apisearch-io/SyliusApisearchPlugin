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

namespace Apisearch\SyliusApisearchPlugin\Exception;

class VersionUnavailableException extends \Exception
{
    /**
     * VersionUnavailableException constructor.
     */
    public function __construct(string $version)
    {
        $message = \sprintf(
            'Version "%s" is unavailable.',
            $version
        );

        parent::__construct($message);
    }
}
