<?php
// src/Controller/EventController.php
namespace App\Controller;
 
use App\Entity\Event;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
 
#[Route('/events')]
class EventController extends AbstractController
{
    /**
     * GET /events
     * Affiche la liste de tous les evenements disponibles
     */
    #[Route('', name: 'app_events_index', methods: ['GET'])]
    public function index(EventRepository $eventRepo): Response
    {
        // findBy() : recupere les evenements tries par date croissante
        $events = $eventRepo->findBy([], ['date' => 'ASC']);
 
        // render() : affiche le template Twig en passant les donnees
        return $this->render('events/index.html.twig', [
            'events' => $events
        ]);
    }
 
    /**
     * GET /events/{id}
     * Affiche le detail d'un evenement
     * Symfony injecte automatiquement l'objet Event correspondant a {id}
     */
    #[Route('/{id}', name: 'app_events_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(Event $event): Response
    {
        return $this->render('events/show.html.twig', [
            'event' => $event
        ]);
    }
}
