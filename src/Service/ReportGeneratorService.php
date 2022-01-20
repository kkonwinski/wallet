<?php

namespace App\Service;

use App\Entity\Wallet;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;

class ReportGeneratorService implements ReportGeneratorInterface
{
    private ManagerRegistry $doctrine;
    private $targetDirectory;

    public function __construct(ManagerRegistry $doctrine, $targetDirectory)
    {
        $this->doctrine = $doctrine;
        $this->targetDirectory = $targetDirectory;
    }

    /**
     * @param int $walletId
     * @return void
     */
    public function generateTransactionsReport(int $walletId): void
    {

        $fileName = sprintf('%sreport.csv', date('YmdHis'));
        $walletTransactionsData = $this->getTransactionHistoryByWallet($walletId);

        $this->createDirNotExist();
        $fp = fopen($this->getTargetDirectory() . '/' . $fileName, 'w');
        fputcsv($fp, $this->getHeaders());
        foreach ($walletTransactionsData as $walletTransactionsDatum) {
            $walletTransactionsDatum['createdAt'] = $this->convertDateTime($walletTransactionsDatum['createdAt']);
            fputcsv($fp, $walletTransactionsDatum);
        }
        fclose($fp);
    }


    /**
     * @param $dateTime
     * @return string
     */
    private function convertDateTime($dateTime): string
    {
        if ($dateTime) {
            return $this->convertDateTimeObjToString($dateTime);
        }
    }

    /**
     * @return string[]
     */
    public function getHeaders(): array
    {
        return array(
            'Actually Amount',
            'Transaction Amount',
            'Amount Before Transaction',
            'Date Transaction',
            'Transaction Type'
        );
    }

    /**
     * @return void
     */
    public function createDirNotExist(): void
    {
        if (!file_exists($this->targetDirectory)) {
            mkdir($this->targetDirectory, 0777, true);
        }
    }


    /**
     * @param DateTime $dateTime
     * @return string
     */
    private function convertDateTimeObjToString(DateTime $dateTime): string
    {
        return $dateTime->format('Y-m-d H:i:s');
    }

    /**
     * @param $wid
     * @return array
     */
    public function getTransactionHistoryByWallet($wid): array
    {
        return $this->doctrine->getRepository(Wallet::class)->findWalletTransactions($wid);
    }


    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }
}
