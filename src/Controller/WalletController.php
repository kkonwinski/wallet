<?php

namespace App\Controller;

use App\Entity\Transaction;
use App\Entity\Wallet;
use App\Repository\WalletRepository;
use App\Service\WalletService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Route("/wallet")
 */
class WalletController extends AbstractController
{

    private WalletService $walletService;
    private EntityManagerInterface $entityManager;
    private ValidatorInterface $validator;


    public function __construct(
        WalletService          $walletService,
        EntityManagerInterface $entityManager,
        ValidatorInterface     $validator
    )
    {
        $this->walletService = $walletService;
        $this->entityManager = $entityManager;
        $this->validator = $validator;
    }

    /**
     * @Route("/create", name="create_wallet",methods={"POST"})
     * @return JsonResponse
     */
    public function create(): JsonResponse
    {
        $wallet = new Wallet();
        $this->entityManager->persist($wallet);
        $this->entityManager->flush();
        return new JsonResponse("New wallet created", Response::HTTP_OK, array(), true);
    }

    /**
     * @Route("/updateWallet", name="update_wallet",methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function updateWallet(Request $request): JsonResponse
    {

        $transaction = new Transaction();
        //TODO walidacja do poprawy
// $errors = $this->yourMethod($request->request);
//        if (count($errors) > 0) {
//            /*
//             * Uses a __toString method on the $errors variable which is a
//             * ConstraintViolationList object. This gives us a nice string
//             * for debugging.
//             */
//            $errorsString = (string) $errors;
//
//            return new Response($errorsString);
//        }

        $walletId = $this->getDataFromRequest($request, "wallet");
        $amount = $this->getDataFromRequest($request, "amount");
        $transactionType = $this->getDataFromRequest($request, "typeTransaction");
        $newTransactionArr = $this->walletService->updateWallet((int)$walletId, $amount, $transactionType);

        $transaction->setAmount($amount);
        $transaction->setAmountBefore($newTransactionArr[2]);
        $transaction->setTypeTransaction($newTransactionArr[1]);
        $transaction->setWallet($newTransactionArr[0]);


        $this->entityManager->persist($transaction);
        $this->entityManager->flush();

        return new JsonResponse("Wallet updated", Response::HTTP_OK, array(), true);
    }

    /**
     * @param Request $request
     * @param $data
     * @return bool|float|int|string|InputBag|null
     * @throws Exception
     */
    private function getDataFromRequest(Request $request, $data)
    {
        return $request->request->get($data);
    }

    /**
     * @Route("/showFundsWallet/{wid}", name="show_funds_wallet",methods={"GET"})
     * @param $wid
     * @param WalletRepository $walletRepository
     * @return JsonResponse
     */
    public function showFundsInWallet($wid, WalletRepository $walletRepository): JsonResponse
    {
        $wallet = $walletRepository->findOneBy(array("id" => (int)$wid));

        if (!$wallet) {
            throw $this->createNotFoundException(
                "No wallet found for id " . $wid
            );
        }
        return new JsonResponse(
            sprintf(
                "Funds on wallet id:%s are %s",
                $wid,
                $wallet[0]->getBalance()
            ),
            Response::HTTP_OK,
            array(),
            true
        );
    }

//
//    public function yourMethod($postData) {
//        // you can also create validator like that
//        // $validator = Validation::createValidator();
//        $constraints = new Assert\Collection([
//            'wallet' => [
//                new Assert\NotBlank()
//            ],
//            'typeTransaction' => [
//                new Assert\NotBlank(),
//            ],
//        ]);
//
//        return $this->validator->validate($postData, $constraints);
//    }
}
