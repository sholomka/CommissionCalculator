<?php
declare(strict_types=1);

namespace App;

use GuzzleHttp\Exception\GuzzleException;

class BinInfoProvider extends BaseProvider
{
    private const string BINLIST_URL = 'https://lookup.binlist.net/';

    public function getBinInfo(string $bin): object
    {
        try {
            $response = $this->client->get(self::BINLIST_URL . $bin);
            $data = json_decode($response->getBody()->getContents());

            return isset($data->country->alpha2) ? $data : $this->defaultBinInfo();

        } catch (GuzzleException $e) {
            error_log("Error fetching BIN info: " . $e->getMessage());
        }

        return $this->defaultBinInfo();
    }

    private function defaultBinInfo(): object
    {
        return (object) ['country' => (object) ['alpha2' => 'Unknown']];
    }
}
