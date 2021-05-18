<?php

namespace App\Controller;

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
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("/reservation/api")
 */
class ReservationApiController extends AbstractController
{
    /**
     * ReservationController constructor.
     */
    public function __construct()
    {
    }

    /**
     * @Route("/showall", name="reservation_showapi")
     */
    public function findall(ReservationRepository $resRepo): Response
    {
        $reservation=$resRepo->findAll();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($reservation);
        return new JsonResponse($formatted);
    }

}









