<?php

namespace App\Service;

interface ReportGeneratorInterface
{
    public function generateTransactionsReport(int $walletId): void;

    public function getHeaders(): array;

    public function createDirNotExist(): void;

    public function getTargetDirectory();
}
