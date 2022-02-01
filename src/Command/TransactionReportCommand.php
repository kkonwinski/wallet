<?php

declare(strict_types=1);

namespace App\Command;

use App\Repository\WalletRepository;
use App\Service\ReportGeneratorService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

class TransactionReportCommand extends Command
{
    protected static $defaultName = "transaction:generate-report";
    protected static $defaultDescription = "Generate CSV File with transaction reports";
    private ReportGeneratorService $reportGeneratorService;
    private WalletRepository $walletRepository;


    public function __construct(
        ReportGeneratorService $reportGeneratorService,
        WalletRepository $walletRepository,
        string $name = null
    ) {
        $this->reportGeneratorService = $reportGeneratorService;
        $this->walletRepository = $walletRepository;
        parent::__construct($name);
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper("question");
        $question = new Question("Please enter wallet id: ");
        $walletId = $helper->ask($input, $output, $question);
        $io = new SymfonyStyle($input, $output);

        if (is_numeric($walletId) && $this->walletRepository->findOneBy(["id" => $walletId])) {
            $this->reportGeneratorService->generateTransactionsReport((int)$walletId);
            $io->success("Report for wallet id: " . (int)$walletId . " create successfully!!!");
            return Command::SUCCESS;
        }

        $io->error("Incorrect wallet id!!!");
        return Command::FAILURE;
    }
}
