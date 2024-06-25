<?php
declare(strict_types=1);

namespace App;

class EuChecker
{
    public static function isEU(string $countryCode): bool
    {
        $euCountries = [
            'AT', 'BE', 'BG', 'CY', 'CZ', 'DE', 'DK', 'EE', 'ES', 'FI', 'FR','GR', 'HR',
            'HU', 'IE', 'IT', 'LT','LU', 'LV', 'MT', 'NL', 'PO', 'PT', 'RO', 'SE', 'SI', 'SK'
        ];

        return in_array($countryCode, $euCountries, true);
    }
}
