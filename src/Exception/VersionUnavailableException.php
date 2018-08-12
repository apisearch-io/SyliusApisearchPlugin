<?php

declare(strict_types=1);

namespace Apisearch\SyliusApisearchPlugin\Exception;

class VersionUnavailableException extends \Exception
{
    /**
     * VersionUnavailableException constructor.
     *
     * @param string $version
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
