<?php
// src/Controller/AdminController.php
namespace App\Controller;
 
use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
 
#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')]   // Toute cette classe necessite ROLE_ADMIN
class AdminController extends AbstractController
{
    /**
     * GET /admin — Tableau de bord avec la liste des evenements
     */
    #[Route('', name: 'admin_dashboard', methods: ['GET'])]
    public function dashboard(EventRepository $repo): Response
    {
        return $this->render('admin/dashboard.html.twig', [
            'events' => $repo->findAll()
        ]);
    }
 
    /**
     * GET+POST /admin/event/new — Creer un nouvel evenement
     */
    #[Route('/event/new', name: 'admin_event_new', methods: ['GET','POST'])]
    public function newEvent(Request $request, EntityManagerInterface $em): Response
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);
 
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($event);
            $em->flush();
            $this->addFlash('success', 'Evenement cree avec succes !');
            return $this->redirectToRoute('admin_dashboard');
        }
 
        return $this->render('admin/event_form.html.twig', [
            'form' => $form->createView(),
            'event' => $event,
            'title' => 'Creer un evenement'
        ]);
    }
 
    /**
     * GET+POST /admin/event/{id}/edit — Modifier un evenement
     */
    #[Route('/event/{id}/edit', name: 'admin_event_edit', requirements: ['id'=>'\d+'], methods: ['GET','POST'])]
    public function editEvent(Event $event, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);
 
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();  // Pas besoin de persist() : Doctrine suit deja cet objet
            $this->addFlash('success', 'Evenement modifie !');
            return $this->redirectToRoute('admin_dashboard');
        }
 
        return $this->render('admin/event_form.html.twig', [
            'form' => $form->createView(),
            'event' => $event,
            'title' => 'Modifier : ' . $event->getTitle()
        ]);
    }
 
    /**
     * POST /admin/event/{id}/delete — Supprimer un evenement
     * On utilise POST (pas DELETE) pour les formulaires HTML standards
     */
    #[Route('/event/{id}/delete', name: 'admin_event_delete', requirements: ['id'=>'\d+'], methods: ['POST'])]
    public function deleteEvent(Event $event, EntityManagerInterface $em, Request $request): Response
    {
        // Verifier le token CSRF pour eviter les suppressions malveillantes
        if ($this->isCsrfTokenValid('delete'.$event->getId(), $request->request->get('_token'))) {
            $em->remove($event);
            $em->flush();
            $this->addFlash('success', 'Evenement supprime.');
        } else {
            $this->addFlash('error', 'Action non autorisee.');
        }
 
        return $this->redirectToRoute('admin_dashboard');
    }
 
    /**
     * GET /admin/event/{id}/reservations — Voir les reservations d'un evenement
     */
    #[Route('/event/{id}/reservations', name: 'admin_event_reservations', requirements: ['id'=>'\d+'], methods: ['GET'])]
    public function eventReservations(Event $event, ReservationRepository $repo): Response
    {
        return $this->render('admin/event_reservations.html.twig', [
            'event'        => $event,
            'reservations' => $repo->findBy(['event' => $event], ['createdAt' => 'DESC'])
        ]);
    }
}
