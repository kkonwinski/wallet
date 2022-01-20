<?php

namespace App\Controller;

use App\Entity\Transaction;
use App\Entity\Wallet;
use App\Service\WalletService;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/wallet")
 */
class WalletController extends AbstractController
{

    private WalletService $walletService;
    private ManagerRegistry $doctrine;

    public function __construct(WalletService $walletService, ManagerRegistry $doctrine)
    {
        $this->walletService = $walletService;
        $this->doctrine = $doctrine;
    }

    /**
     * @Route("/create", name="create_wallet",methods={"POST"})
     * @return Response
     */
    public function create(): Response
    {
        $entityManager = $this->doctrine->getManager();
        $wallet = new Wallet();
        $entityManager->persist($wallet);
        $entityManager->flush();
        return new JsonResponse('New wallet created', Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/updateWallet", name="update_wallet",methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function updateWallet(Request $request): JsonResponse
    {
        $walletId = $this->getDataFromRequest($request, 'walletId');
        $amount = $this->getDataFromRequest($request, 'amount');
        $transactionType = $this->getDataFromRequest($request, 'transactionType');
        $newTransactionArr = $this->walletService->updateWallet($walletId, $amount, $transactionType);

        $transaction = new Transaction();
        $transaction->setAmount($amount);
        $transaction->setTypeTransaction($newTransactionArr[1]);
        $transaction->setWallet($newTransactionArr[0]);

        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($transaction);
        $entityManager->flush();

        return new JsonResponse('Wallet updated', Response::HTTP_OK, [], true);
    }

    /**
     * @param Request $request
     * @param $data
     * @return bool|float|int|string|InputBag|null
     * @throws Exception
     */
    private function getDataFromRequest(Request $request, $data)
    {

        if ($request->request->get($data)) {
            return $request->request->get($data);
        }
        throw new Exception(new JsonResponse(sprintf("Missing data %s", $data), Response::HTTP_BAD_REQUEST));
    }
}
