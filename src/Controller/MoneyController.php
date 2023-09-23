<?php

namespace App\Controller;

use App\Entity\Money;
use App\Entity\User;
use App\MoneyTransfer;
use App\Repository\ParametersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MoneyController extends AbstractController
{
    /** @var ParametersRepository $parameter */
    private ParametersRepository $parameters;

    /** @var EntityManagerInterface $entityManager */
    private EntityManagerInterface $entityManager;

    public function __construct(ParametersRepository $parameters, EntityManagerInterface $entityManager)
    {
        $this->parameters = $parameters;
        $this->entityManager = $entityManager;
    }

    /**
     * @throws NonUniqueResultException
     */
    #[Route('/convertMoney/{id}', name: 'app_money_to_bonus')]
    public function convertMoney(Request $request, string $id): Response
    {
        /** @var null|string $submittedToken */
        $submittedToken = $request->request->get('token');

        if ($this->isCsrfTokenValid('convert-money-to-bonus', $submittedToken)) {
            $money = $this->entityManager->getRepository(Money::class)->findOneBy(['id' => $id]);
            /** @var User $user */
            $user = $this->getUser();

            if ($money && !$money->getIsConverted() && !$money->getIsTransferred() && $money->getUser() === $user) {
                $conversionRate = $this->parameters->findByName('bonus_to_money_conversion_rate');

                if (!$conversionRate) {
                    throw new \LogicException();
                }

                if ($rate = filter_var($conversionRate->getValue(), FILTER_VALIDATE_FLOAT)) {
                    $newBonusValue = $user->getBonusCount() + ($rate * $money->getAmount());
                    $user->setBonusCount((int) $newBonusValue);
                    $this->entityManager->persist($user);

                    $money->setIsConverted(true);
                    $this->entityManager->persist($money);

                    $this->entityManager->flush();
                }
            }
        }

        return $this->redirectToRoute('app_user_profile');
    }

    /**
     * @throws NonUniqueResultException
     */
    #[Route('/rejectMoney/{id}', name: 'app_money_reject')]
    public function rejectMoney(Request $request, string $id): Response
    {
        /** @var null|string $submittedToken */
        $submittedToken = $request->request->get('token');

        if ($this->isCsrfTokenValid('reject-money', $submittedToken)) {
            $money = $this->entityManager->getRepository(Money::class)->findOneBy(['id' => $id]);
            $user = $this->getUser();

            if ($money && !$money->getIsConverted() && !$money->getIsTransferred() && $money->getUser() === $user) {
                $availableMoney = $this->parameters->findByName('available_money');

                if (!$availableMoney) {
                    throw new \LogicException('This parameter must not be empty');
                }

                if ($amount = filter_var($availableMoney->getValue(), FILTER_VALIDATE_FLOAT)) {
                    $newAvailableMoney = $amount + $money->getAmount();
                    $availableMoney->setValue((string) $newAvailableMoney);
                    $this->entityManager->persist($availableMoney);
                    $this->entityManager->remove($money);
                    $this->entityManager->flush();
                }
            }
        }

        return $this->redirectToRoute('app_user_profile');
    }

    #[Route('/transferMoney/{id}', name: 'app_money_to_bank')]
    public function transferToBank(Request $request, string $id): Response
    {
        /** @var null|string $submittedToken */
        $submittedToken = $request->request->get('token');

        if ($this->isCsrfTokenValid('transfer-money', $submittedToken)) {
            $money = $this->entityManager->getRepository(Money::class)->findOneBy(['id' => $id]);
            $user = $this->getUser();

            if (
                $money &&
                $money->getUser() === $user &&
                $money->getIsConverted() === false &&
                $money->getIsTransferred() === false
            ) {
                MoneyTransfer::transfer($money);

                $money->setIsTransferred(true);
                $this->entityManager->persist($money);
                $this->entityManager->flush();

                return $this->render('money/transferred.html.twig', ['prize' => $money]);
            }
        }

        return $this->redirectToRoute('app_user_profile');
    }
}
