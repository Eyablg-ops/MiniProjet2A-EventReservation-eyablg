<?php
// src/Controller/ReservationController.php
namespace App\Controller;
 
use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
 
#[Route('/reservations')]
class ReservationController extends AbstractController
{
    /**
     * GET+POST /reservations/new/{id}
     * Formulaire de reservation pour l'evenement {id}
     */
    #[Route('/new/{id}', name: 'app_reservation_new', requirements: ['id' => '\d+'], methods: ['GET','POST'])]
    #[IsGranted('ROLE_USER')]   // Seuls les utilisateurs connectes peuvent reserver
    public function new(int $id, EventRepository $eventRepo,
                        Request $request, EntityManagerInterface $em): Response
    {
        $event = $eventRepo->find($id);
        if (!$event) {
            throw $this->createNotFoundException('Evenement non trouve.');
        }
 
        // Verifier si des places sont disponibles
        $reserved = $event->getReservations()->count();
        if ($event->getSeats() !== null && $reserved >= $event->getSeats()) {
            $this->addFlash('error', 'Desolé, cet evenement est complet.');
            return $this->redirectToRoute('app_events_show', ['id' => $id]);
        }
 
        $reservation = new Reservation();
        $reservation->setEvent($event);
        $reservation->setUser($this->getUser());
 
        // Cree le formulaire lie a l'objet $reservation
        $form = $this->createForm(ReservationType::class, $reservation);
 
        // handleRequest : si c'est un POST, remplit $reservation avec les donnees du formulaire
        $form->handleRequest($request);
 
        if ($form->isSubmitted() && $form->isValid()) {
            // persist : Doctrine 'connait' maintenant cet objet
            $em->persist($reservation);
            // flush : Doctrine execute le INSERT INTO en BDD
            $em->flush();
 
            // addFlash : message temporaire affiche une seule fois
            $this->addFlash('success', 'Reservation confirmee !');
 
            return $this->redirectToRoute('app_reservation_confirm', ['id' => $reservation->getId()]);
        }
 
        return $this->render('reservations/new.html.twig', [
            'form' => $form->createView(),
            'event' => $event
        ]);
    }
 
    /**
     * GET /reservations/confirm/{id}
     * Page de confirmation apres une reservation reussie
     */
    #[Route('/confirm/{id}', name: 'app_reservation_confirm', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function confirm(Reservation $reservation): Response
    {
        return $this->render('reservations/confirm.html.twig', [
            'reservation' => $reservation
        ]);
    }
}
