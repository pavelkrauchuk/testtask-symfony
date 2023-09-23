<?php

namespace App\Controller;

use App\Entity\Bonus;
use App\Entity\Money;
use App\Entity\Thing;
use App\Entity\User;
use App\Factory\PrizeFactory;
use App\Repository\ParametersRepository;
use App\Service\RandomPrizeValueGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PrizeController extends AbstractController
{
    /** @var PrizeFactory $prizeFactory */
    private PrizeFactory $prizeFactory;

    /** @var RandomPrizeValueGenerator $prizeValueGenerator */
    private RandomPrizeValueGenerator $prizeValueGenerator;

    /** @var ParametersRepository $parameters */
    private ParametersRepository $parameters;

    /** @var EntityManagerInterface $entityManager */
    private EntityManagerInterface $entityManager;
    public function __construct(
        PrizeFactory $prizeFactory,
        RandomPrizeValueGenerator $prizeValueGenerator,
        ParametersRepository $parameters,
        EntityManagerInterface $entityManager
    ) {
        $this->prizeFactory = $prizeFactory;
        $this->prizeValueGenerator = $prizeValueGenerator;
        $this->parameters = $parameters;
        $this->entityManager = $entityManager;
    }

    /**
     * @throws \Exception
     */
    #[Route('/getprize', name: 'app_get_prize')]
    public function getPrize(): Response
    {
        $prize = $this->prizeFactory->create();

        /** @var User $currentUser */
        $currentUser = $this->getUser();

        if ($prize instanceof Money) {
            $moneyValue = $this->prizeValueGenerator->getRandomMoneyValue();
            $prize->setAmount($moneyValue)
                ->setIsTransferred(false)
                ->setIsConverted(false)
                ->setUser($currentUser);

            $availableMoney = $this->parameters->findByName('available_money');

            if ($availableMoney && $amount = filter_var($availableMoney->getValue(), FILTER_VALIDATE_FLOAT)) {
                $availableMoney->setValue((string) ($amount - $moneyValue));

                $this->entityManager->persist($availableMoney);
            }
        }

        if ($prize instanceof Bonus) {
            $bonusValue = $this->prizeValueGenerator->getRandomBonusValue();
            $prize->setAmount($bonusValue)
                ->setIsAdmissed(false)
                ->setUser($currentUser);
        }

        if ($prize instanceof Thing) {
            $thing = $this->prizeValueGenerator->getRandomAvailableThing();
            $prize->setName($thing->getName())
                ->setIsShipped(false)
                ->setUser($currentUser);

            $this->entityManager->remove($thing);
        }

        $this->entityManager->persist($prize);
        $this->entityManager->flush();

        return $this->redirectToRoute('app_user_profile');
    }
}
