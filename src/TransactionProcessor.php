<?php
declare(strict_types=1);

namespace App;

class TransactionProcessor
{
    public function __construct (
        private string $filePath
    ) {
    }

    public function getTransactions(): array
    {
        return array_filter(explode("\n", file_get_contents($this->filePath)));
    }

    public function parseTransaction(string $transaction): array
    {
        return json_decode($transaction, true);
    }
}
