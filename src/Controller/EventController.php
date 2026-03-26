<?php
namespace App\Controller;

use App\Entity\Event;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/events')]
class EventController extends AbstractController
{
    // SUPPRIME la méthode home() — elle causait la boucle

    #[Route('', name: 'app_events_index', methods: ['GET'])]
    public function index(EventRepository $eventRepo): Response
    {
        $events = $eventRepo->findBy([], ['date' => 'ASC']);
        return $this->render('events/index.html.twig', [
            'events' => $events
        ]);
    }

    #[Route('/{id}', name: 'app_events_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(Event $event): Response
    {
        return $this->render('events/show.html.twig', [
            'event' => $event
        ]);
    }
}