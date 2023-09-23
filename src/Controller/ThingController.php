<?php

namespace App\Controller;

use App\Entity\AvailableThing;
use App\Entity\Thing;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ThingController extends AbstractController
{
    #[Route('/shipThing/{id}', name: 'app_thing_ship_to_user')]
    public function shipThing(Request $request, EntityManagerInterface $entityManager, string $id): Response
    {
        /** @var null|string $submittedToken */
        $submittedToken = $request->request->get('token');

        if ($this->isCsrfTokenValid('ship-thing-to-user', $submittedToken)) {
            $thing = $entityManager->getRepository(Thing::class)->findOneBy(['id' => $id]);
            $user = $this->getUser();

            if ($thing && $thing->getUser() === $user && $thing->getIsShipped() === false) {
                $thing->setIsShipped(true);

                $entityManager->persist($thing);
                $entityManager->flush();

                return $this->render('thing/shipped.html.twig', [
                    'prize' => $thing,
                ]);
            }
        }

        return $this->redirectToRoute('app_user_profile');
    }

    #[Route('/rejectThing/{id}', name: 'app_thing_reject')]
    public function rejectThing(Request $request, EntityManagerInterface $entityManager, string $id): Response
    {
        /** @var null|string $submittedToken */
        $submittedToken = $request->request->get('token');

        if ($this->isCsrfTokenValid('reject-thing', $submittedToken)) {
            $thing = $entityManager->getRepository(Thing::class)->findOneBy(['id' => $id]);
            $user = $this->getUser();

            if ($thing && $thing->getUser() === $user && $thing->getIsShipped() === false) {
                $availableThing = new AvailableThing();
                $availableThing->setName($thing->getName());

                $entityManager->persist($availableThing);
                $entityManager->remove($thing);
                $entityManager->flush();
            }
        }

        return $this->redirectToRoute('app_user_profile');
    }
}
