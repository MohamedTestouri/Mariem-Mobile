<?php

namespace App\Controller;

use App\Entity\Evenements;
use App\Form\EvenementsType;
use App\Repository\EvenementsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("/evenements/api")
 */
class EvenementsApiController extends AbstractController
{
    /**
     * @Route("/showevent", name="evenements_index", methods={"GET"})
     */
    public function findall(EvenementsRepository $evenementsRepo): Response
    {
        $event=$evenementsRepo->findAll();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($event);
        return new JsonResponse($formatted);
    }

    /**
     * @Route("/findby", name="evenements_index", methods={"GET"})
     */
    public function findbyId(EvenementsRepository $evenementsRepo): Response
    {
        // id user ?
    }

}
