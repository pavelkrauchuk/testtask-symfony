<?php

namespace App\Controller;

use App\Entity\Money;
use App\Entity\Parameters;
use App\MoneyTransfer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MoneyController extends AbstractController
{
    #[Route('/convertMoney/{id}', name: 'app_money_to_bonus')]
    public function convertMoney(Request $request, EntityManagerInterface $entityManager, string $id): Response
    {
        $submittedToken = $request->request->get('token');

        if ($this->isCsrfTokenValid('convert-money-to-bonus', $submittedToken)) {
            $money = $entityManager->getRepository(Money::class)->findOneBy(array('id' => $id));
            $user = $this->getUser();

            if ($money?->getUser() == $user && $money->getIsConverted() == false && $money->getIsTransferred() == false) {
                $conversionRate = $entityManager->getRepository(Parameters::class)->findOneBy(array(
                    'paramName' => 'bonus_to_money_conversion_rate'
                ));

                $user->setBonusCount($user->getBonusCount() + ($conversionRate->getValue() * $money->getAmount()));
                $entityManager->persist($user);

                $money->setIsConverted(true);
                $entityManager->persist($money);

                $entityManager->flush();
            }
        }

        return $this->redirectToRoute('app_user_profile');
    }

    #[Route('/rejectMoney/{id}', name: 'app_money_reject')]
    public function rejectMoney(Request $request, EntityManagerInterface $entityManager, string $id) : Response
    {
        $submittedToken = $request->request->get('token');

        if ($this->isCsrfTokenValid('reject-money', $submittedToken)) {
            $money = $entityManager->getRepository(Money::class)->findOneBy(array('id' => $id));
            $user = $this->getUser();

            if ($money?->getUser() == $user && $money->getIsConverted() == false && $money->getIsTransferred() == false) {
                $availableMoney = $entityManager->getRepository(Parameters::class)->findOneBy(array(
                    'paramName' => 'available_money'
                ));

                $availableMoney->setValue($availableMoney->getValue() + $money->getAmount());
                $entityManager->persist($availableMoney);
                $entityManager->remove($money);
                $entityManager->flush();
            }
        }

        return $this->redirectToRoute('app_user_profile');
    }

    #[Route('/transferMoney/{id}', name: 'app_money_to_bank')]
    public function transferToBank(Request $request, EntityManagerInterface $entityManager, string $id) : Response
    {
        $submittedToken = $request->request->get('token');

        if ($this->isCsrfTokenValid('transfer-money', $submittedToken)) {
            $money = $entityManager->getRepository(Money::class)->findOneBy(array('id' => $id));
            $user = $this->getUser();

            if ($money?->getUser() == $user && $money->getIsConverted() == false && $money->getIsTransferred() == false) {
                MoneyTransfer::transfer($money);

                $money->setIsTransferred(true);
                $entityManager->persist($money);
                $entityManager->flush();

                return $this->render('money/transferred.html.twig', array('prize' => $money));
            }
        }

        return $this->redirectToRoute('app_user_profile');
    }
}
