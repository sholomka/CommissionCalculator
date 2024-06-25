<?php
declare(strict_types=1);

namespace Tests;

use App\BinInfoProvider;
use App\CommissionCalculator;
use App\EuChecker;
use App\ExchangeRateProvider;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class CommissionCalculatorTest extends TestCase
{
    public function testIsEu(): void
    {
        $this->assertTrue(EuChecker::isEu('FR'));
        $this->assertFalse(EuChecker::isEu('US'));
    }

    public function testGetBinInfo(): void
    {
        $mock = new MockHandler([
            new Response(200, [], json_encode(['country' => ['alpha2' => 'FR']]))
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        $binInfoProvider = new BinInfoProvider($client);
        $binInfo = $binInfoProvider->getBinInfo('45717360', $client);
        $this->assertEquals('FR', $binInfo->country->alpha2);
    }

    public function testGetExchangeRate(): void
    {
        $mock = new MockHandler([
            new Response(200, [], json_encode(['rates' => ['USD' => 1.2]]))
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        $exchangeRateProvider = new ExchangeRateProvider($client);
        $rate = $exchangeRateProvider->getExchangeRate('USD');
        $this->assertEquals(1.2, $rate);
    }

    public function testCalculateCommission(): void
    {
        $mock = new MockHandler([
            new Response(200, [], json_encode(['country' => ['alpha2' => 'FR']])),
            new Response(200, [], json_encode(['rates' => ['EUR' => 1, 'USD' => 1.2]])),
            new Response(200, [], json_encode(['country' => ['alpha2' => 'US']])),
            new Response(200, [], json_encode(['rates' => ['EUR' => 1, 'USD' => 1.2]]))
        ]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        $binInfoProvider = new BinInfoProvider($client);
        $exchangeRateProvider = new ExchangeRateProvider($client);
        $euChecker = new EuChecker();
        $commissionCalculator = new CommissionCalculator($binInfoProvider, $exchangeRateProvider, $euChecker);

        $transaction = ['bin' => '45717360', 'amount' => 100, 'currency' => 'EUR'];
        $commission = $commissionCalculator->calculateCommission($transaction, $client);
        $this->assertEquals(1.0, $commission);

        $transaction = ['bin' => '45717360', 'amount' => 120, 'currency' => 'USD'];
        $commission = $commissionCalculator->calculateCommission($transaction, $client);
        $this->assertEquals(2.0, $commission);
    }
}
