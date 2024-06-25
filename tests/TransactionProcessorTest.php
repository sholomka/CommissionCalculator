<?php
declare(strict_types=1);

namespace Tests;

use App\TransactionProcessor;
use PHPUnit\Framework\TestCase;

class TransactionProcessorTest extends TestCase
{
    private string $filePath;

    protected function setUp(): void
    {
        $this->filePath = __DIR__ . '/../input.txt';
    }

    public function testGetTransactions(): void
    {
        $transactionProcessor = new TransactionProcessor($this->filePath);
        $transactions = $transactionProcessor->getTransactions();

        $this->assertCount(5, $transactions);
    }

    public function testParseTransactions(): void
    {
        $transaction = "{\"bin\":\"45717360\",\"amount\":\"100.00\",\"currency\":\"EUR\"}";
        $transactionProcessor = new TransactionProcessor($this->filePath);
        $parsedTransaction = $transactionProcessor->parseTransaction($transaction);

        $this->assertIsArray($parsedTransaction);
        $this->assertArrayHasKey('bin', $parsedTransaction);
        $this->assertArrayHasKey('amount', $parsedTransaction);
        $this->assertArrayHasKey('currency', $parsedTransaction);
    }
}
