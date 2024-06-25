<?php
declare(strict_types=1);

namespace App;

use GuzzleHttp\Client;

abstract class BaseProvider
{
    public function __construct(
        readonly protected Client $client
    ) {
    }
}
