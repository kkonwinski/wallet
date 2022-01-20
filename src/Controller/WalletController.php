<?php

namespace App\Controller;

use App\Entity\Wallet;
use App\Service\WalletService;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }

    /**
     * @Route("/create", name="create_wallet",methods={"POST"})
     * @param ManagerRegistry $doctrine
     * @return Response
     */
    public function create(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $wallet = new Wallet();
        $entityManager->persist($wallet);
        $entityManager->flush();
        return new JsonResponse('New wallet created', Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/addMoney", name="add_money_to_wallet",methods={"POST"})
     * @throws Exception
     */
    public function addMoneyToWallet(Request $request)
    {

        $walletId = $this->getDataFromRequest($request, 'walletId');
        $amount = $this->getDataFromRequest($request, 'amount');
        $transactionType = $this->getDataFromRequest($request, 'transactionType');
        $this->walletService->addToWallet($walletId, $amount, $transactionType);
    }

    /**
     * @throws Exception
     */
    private function getDataFromRequest(Request $request, string $data)
    {

        if ($request->request->get($data)) {
            return $request->request->get($data);
        }
        throw new Exception(new JsonResponse(sprintf("Missing data %s", $data), Response::HTTP_BAD_REQUEST));
    }
}
