<?php

namespace App\Service;

use App\Entity\TypeTransaction;
use App\Entity\Wallet;

interface WalletServiceInterface
{
    public function updateWallet(int $walletId, float $amount, string $transactionType): array;

    public function recalculateWallet(Wallet $wallet, $amount, TypeTransaction $typeTransaction): float;
}
