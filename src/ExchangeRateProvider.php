<?php
declare(strict_types=1);

namespace App;

use GuzzleHttp\Exception\RequestException;

class ExchangeRateProvider extends BaseProvider
{
    private const string EXCHANGE_RATES_URL = 'https://api.exchangeratesapi.io/latest';

    public function getExchangeRate(string $currency): float
    {
        try {
            $response = $this->client->get(self::EXCHANGE_RATES_URL);
            $rates = json_decode($response->getBody()->getContents(), true);

            return $rates['rates'][$currency] ?? 0;
        } catch (RequestException $e) {
            error_log("Error fetching exchange rate: " . $e->getMessage());
        }

        return 0.0;
    }
}
