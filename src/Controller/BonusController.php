<?php

namespace App\Controller;

use App\Entity\Bonus;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BonusController extends AbstractController
{
    #[Route('/addToAccount/{id}', name: 'app_bonus_to_account')]
    public function addToAccount(Request $request, EntityManagerInterface $entityManager, string $id): Response
    {
        $submittedToken = $request->request->get('token');

        if ($this->isCsrfTokenValid('add-to-account', $submittedToken)) {
            $bonus = $entityManager->getRepository(Bonus::class)->findOneBy(array('id' => $id));

            /** @var User $user */
            $user = $this->getUser();

            if ($bonus?->getUser() == $user && $bonus->getIsAdmissed() == false) {
                $user->setBonusCount($user->getBonusCount() + $bonus->getAmount());
                $entityManager->persist($user);
                $bonus->setIsAdmissed(true);
                $entityManager->persist($bonus);

                $entityManager->flush();
            }
        }

        return $this->redirectToRoute('app_user_profile');
    }

    #[Route('/rejectBonus/{id}', name: 'app_bonus_reject')]
    public function rejectBonus(Request $request, EntityManagerInterface $entityManager, string $id): Response
    {
        $submittedToken = $request->request->get('token');

        if ($this->isCsrfTokenValid('reject-bonus', $submittedToken)) {
            $bonus = $entityManager->getRepository(Bonus::class)->findOneBy(array('id' => $id));
            $user = $this->getUser();

            if ($bonus?->getUser() == $user && $bonus->getIsAdmissed() == false) {
                $entityManager->remove($bonus);
                $entityManager->flush();
            }
        }

        return $this->redirectToRoute('app_user_profile');
    }
}
