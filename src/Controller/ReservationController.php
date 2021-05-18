<?php

namespace App\Controller;

use App\Entity\Evenements;
use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\LocationRepository;
use App\Repository\ReservationRepository;
use phpDocumentor\Reflection\Location;
use phpDocumentor\Reflection\Types\Null_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("home/reservation")
 */
class ReservationController extends AbstractController
{
    /**
     * ReservationController constructor.
     */
    public function __construct()
    {
    }

    /**
     * @Route("/api/show", name="reservation_show_api", methods={"GET"})
     */
    public function showMyReservation(ReservationRepository $reservationRepository): Response
    {
        $eve=$this->getDoctrine()->getManager()
            ->getRepository(Reservation::class)
            ->findAll();
        $SerializerInterface= new Serializer([new DateTimeNormalizer(),new ObjectNormalizer()]);
        $formatted = $SerializerInterface->normalize($eve);
        return new JsonResponse($formatted,200);
    }
    /**
     * @Route("/", name="reservation_index", methods={"GET"})
     */
    public function index(ReservationRepository $reservationRepository): Response
    {
        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservationRepository->findAll(),
        ]);
    }

// Method reserver !!
// add here !!
/**
 * @Route ("/reserve", name="reserver", methods={"GET"})
*/
public function reserver(Request $request, LocationRepository  $locationRepository): Response{
//    <a href="{{ path('reservation_show', {'id': reservation.id}) }}">show</a>
    $reservation = new Reservation();
$location = new \App\Entity\Location();
//    $form = $this->createForm(ReservationType::class, $reservation);
//    $form->handleRequest($request);
//  if($request->request->get('prix')!=Null){
    $id = $request ->query->get('id');
    $nb= $request-> query->get('nb');
   // $dateDeb = $request -> get('dateDeb');
    $location = $locationRepository ->find($id);

    $reservation->setPrix($location->getPrix());
    $reservation->setDestination(        $location->getLocalisation());
    $reservation->setDateFin(new \DateTime('2021-01-01'));
    $reservation->setDateDebut(new \DateTime('2021-01-01'));
    $reservation->setNombresPersonnes($nb);

    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->persist($reservation);
    $entityManager->flush();
    return $this->render('reservation/new.html.twig');

}

    /**
     * @Route("/new", name="reservation_new", methods={"GET","POST"})
     */
    public function new(Request $request,LocationRepository $r): Response
    {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);
        if($request->request->get('date')!=Null)
        {   //$reservation->setPrix(0);
        $reservation->setDateFin(new \DateTime($request->request->get('date-1')));
            $reservation->setDateDebut(new \DateTime($request->request->get('date')));

            $reservation->setDestination($request->request->get('select'));
            $reservation->setNombresPersonnes($request->request->get('nb'));
           // $entityManager = $this->getDoctrine()->getManager();
            //$entityManager->persist($reservation);
            //$entityManager->flush();
            //$formSent = $this-> createForm(ReservationType::class, $reservation);
            //$formSent->handleRequest($request);
            return $this->render('hotel.html.twig',[
 'locations' =>$r->findbb($reservation->getDestination(),$reservation->getNombresPersonnes()),
'nb'=>$reservation->getNombresPersonnes(),
                'dateDebut' => $reservation->getDateDebut(),

                'dateFin' => $reservation->getDateFin(),
                //'form' => $formSent->createView(),

            ]);
        }

else{
    return $this->render('reservation/new.html.twig', [
        'reservation' => $reservation,

    ]);

}

    }

    /**
     * @Route("/{id}", name="reservation_show", methods={"GET"})
     */
    public function show(Reservation $reservation): Response
    {
        return $this->render('reservation/show.html.twig',[
            'reservation' =>$reservation,

        ]);
    }

    /**
     * @Route("/{id}/edit", name="reservation_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Reservation $reservation): Response
    {
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('reservation_index');
        }

        return $this->render('reservation/edit.html.twig', [
            'reservation' => $reservation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="reservation_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Reservation $reservation): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reservation->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($reservation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('reservation_index');
    }


    /**
     * @Route("/home/new", name="reservation_new_front", methods={"GET","POST"})
     */
    public function newfront(Request $request): Response
    {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($reservation);
            $entityManager->flush();

            return $this->redirectToRoute('reservation_index');
        }

        return $this->render('front/reservation/new.html.twig', [
            'reservation' => $reservation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/home/{id}", name="reservation_show_front", methods={"GET"})
     */
    public function showfront(Reservation $reservation): Response
    {
        return $this->render('front/reservation/show.html.twig', [
            'reservation' => $reservation,
        ]);
    }

    /**
     * @Route("/home/{id}/edit", name="reservation_edit_front", methods={"GET","POST"})
     */
    public function editfront(Request $request, Reservation $reservation): Response
    {
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('front/reservation_index');
        }

        return $this->render('front/reservation/edit.html.twig', [
            'reservation' => $reservation,
            'form' => $form->createView(),
        ]);
    }

}









