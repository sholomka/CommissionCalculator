<?php
declare(strict_types=1);

namespace App;

use GuzzleHttp\Client;

class App
{
    public function run(string $filePath): void
    {
        $client = new Client();
        $binInfoProvider = new BinInfoProvider($client);
        $exchangeRateProvider = new ExchangeRateProvider($client);
        $commissionCalculator = new CommissionCalculator($binInfoProvider, $exchangeRateProvider);
        $transactionProcessor = new TransactionProcessor($filePath);

        $this->processTransactions($transactionProcessor, $commissionCalculator);
    }

    private function processTransactions(TransactionProcessor $transactionProcessor, CommissionCalculator $commissionCalculator): void
    {
        $transactions = $transactionProcessor->getTransactions();
        foreach ($transactions as $transaction) {
            $parsedTransaction = $transactionProcessor->parseTransaction($transaction);
            $commission = $commissionCalculator->calculateCommission($parsedTransaction);

            echo $commission . "\n";
        }
    }
}
