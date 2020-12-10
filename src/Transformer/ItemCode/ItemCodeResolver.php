<?php

declare(strict_types=1);

namespace Apisearch\SyliusApisearchPlugin\Transformer\ItemCode;

use function explode;
use function sprintf;

class ItemCodeResolver
{
    private const DELIMITER = '|';

    public static function encode(string $productCode, string $localeCode): string
    {
        return sprintf('%s%s%s', $localeCode, self::DELIMITER, $productCode);
    }

    public static function decode(string $code): ItemCodeModel
    {
        [$localCode, $productCode] = explode(self::DELIMITER, $code, 2);

        return new ItemCodeModel($localCode, $productCode);
    }
}
