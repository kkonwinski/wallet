<?php

namespace App\Service;

use App\Entity\TypeTransaction;
use App\Entity\Wallet;
use Doctrine\Persistence\ManagerRegistry;

class WalletService
{
    private ManagerRegistry $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function addToWallet(int $walletId, float $amount, string $transactionType)
    {
        $wallet = $this->findWallet($walletId);
        $type = $this->findTransactionType($transactionType);
        $recalculatedWalletBalance = $this->recalculateWallet($wallet, $amount, $type);
    }


    public function recalculateWallet(Wallet $wallet, $amount, TypeTransaction $typeTransaction): float
    {
        if ($wallet > 0) {
            if ($typeTransaction->getName() === "payment") {
                return $wallet->getBalance() + $amount;
            }
        }
        if ($wallet->getBalance() >= $amount) {
            return $wallet->getBalance() - $amount;
        } else {
            throw new \LogicException("Salary amount is bigger than wallet balance");
        }
    }

    /**
     * @param int $id
     * @return Wallet
     */
    public function findWallet(int $id): Wallet
    {
        $walletObj = $this->doctrine->getRepository(Wallet::class)->findOneBy(array('id' => $id));
        if ($walletObj) {
            return $walletObj;
        }
        throw new \InvalidArgumentException("Undefined wallet id");
    }


    /**
     * @param string $transactionType
     * @return TypeTransaction
     */
    public function findTransactionType(string $transactionType): TypeTransaction
    {
        $transactionTypeObj = $this->doctrine->getRepository(TypeTransaction::class)
            ->findOneBy(array('name' => $transactionType));

        if ($transactionTypeObj) {
            return $transactionTypeObj;
        }
        throw new \InvalidArgumentException("Undefined transaction type");
    }
}
