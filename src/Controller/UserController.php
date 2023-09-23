<?php

namespace App\Controller;

use App\Entity\Prize;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/profile', name: 'app_user_profile')]
    public function getListPrizes(EntityManagerInterface $entityManager): Response
    {
        $prizes = $entityManager->getRepository(Prize::class)->findBy(['user' => $this->getUser()]);

        return $this->render('user/index.html.twig', [
            'prizes' => $prizes,
        ]);
    }
}
