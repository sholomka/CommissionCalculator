<?php
declare(strict_types=1);

namespace App;

readonly class CommissionCalculator
{
    public function __construct(
        private BaseProvider $binInfoProvider,
        private BaseProvider $exchangeRateProvider,
    ) {
    }

    public function calculateCommission(array $transaction): float
    {
        $binInfo = $this->binInfoProvider->getBinInfo($transaction['bin']);
        $isEu = EuChecker::isEu($binInfo->country->alpha2);

        $rate = $this->exchangeRateProvider->getExchangeRate($transaction['currency']);

        $amountFixed = $transaction['amount'];
        if ($transaction['currency'] !== 'EUR' && $rate > 0) {
            $amountFixed = $transaction['amount'] / $rate;
        }

        $commissionRate = $isEu ? 0.01 : 0.02;

        return ceil($amountFixed * $commissionRate * 100) / 100;
    }
}
