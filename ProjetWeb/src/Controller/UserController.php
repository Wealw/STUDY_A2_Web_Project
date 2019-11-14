<?php


namespace App\Controller;


use App\Repository\ParticipationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{

    /**
     * @Route("/profile/events", name="profile.events")
     * @param ParticipationRepository $participationRepository
     * @return Response
     * @throws \Exception
     */
    public function events(ParticipationRepository $participationRepository)
    {
        $participations = $participationRepository->findBy([
            'participation_user_id' => $this->getUser()->getUserId()
        ]);

        return $this->render("user/events.html.twig", [
            'participations' => $participations,
        ]);
    }

}