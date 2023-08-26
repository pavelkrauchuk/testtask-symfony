<?php

namespace App\Controller;

use App\Entity\AvailableThing;
use App\Entity\Bonus;
use App\Entity\Money;
use App\Entity\Parameters;
use App\Entity\Prize;
use App\Entity\Thing;
use App\RandomPrizeGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PrizeController extends AbstractController
{
    #[Route('/getprize', name: 'app_get_prize')]
    public function getPrize(EntityManagerInterface $entityManager): Response
    {
        $availableTypes = Prize::getAvailableTypes($entityManager);
        $prize = RandomPrizeGenerator::generate($availableTypes);
        $currentUser = $this->getUser();

        if ($prize instanceof Money) {
            $moneyValue = RandomPrizeGenerator::getRandomMoneyValue($entityManager);
            $prize->setAmount($moneyValue)
                ->setIsTransferred(false)
                ->setIsConverted(false)
                ->setUser($currentUser);

            $entityManager->persist($prize);

            $availableMoney = $entityManager->getRepository(Parameters::class)->findOneBy(array(
                'paramName' => 'available_money'
            ));

            $availableMoney->setValue($availableMoney->getValue() - $moneyValue);
            $entityManager->persist($availableMoney);
        }

        if ($prize instanceof Bonus) {
            $bonusValue = RandomPrizeGenerator::getRandomBonusValue($entityManager);
            $prize->setAmount($bonusValue)
                ->setIsAdmissed(false)
                ->setUser($currentUser);

            $entityManager->persist($prize);
        }

        if ($prize instanceof Thing) {
            $thing = RandomPrizeGenerator::getRandomAvailableThing($entityManager);
            $prize->setName($thing->getName())
                ->setIsShipped(false)
                ->setUser($currentUser);

            $entityManager->persist($prize);
            $entityManager->remove($thing);
        }

        $entityManager->flush();

        return $this->redirectToRoute('app_user_profile');
    }
}
