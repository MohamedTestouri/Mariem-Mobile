<?php

namespace App\Controller;

use App\Entity\Location;
use App\Form\LocationType;
use App\Repository\LocationRepository;

use Dompdf\Dompdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/home/location")
 */
class LocationController extends AbstractController
{
    /**
     * @Route("/all", name="location_all")
     */
    public function findall(LocationRepository $locRepo): Response
{
    $location = $locRepo->findAll();
    $serializer = new Serializer([new ObjectNormalizer()]);
    $formatted = $serializer->normalize($location);
    return new JsonResponse($formatted);
}
     /**
      * @Route("/", name="location_index", methods={"GET"})
      */
     public function index(LocationRepository $locationRepository): Response
{
    return $this->render('location/index.html.twig', [
        'locations' => $locationRepository->findAll(),
    ]);
}


    // /**
    //  * @Route("/generatePdf", name="generatePdf", methods={"GET"})
    //  */
    // public function generatePdf(LocationRepository $locationRepository): Response
    // {
    //     // instantiate and use the dompdf class



    //     $dompdf = new Dompdf();
    //     $test = $this->renderView('location/table.html.twig', [
    //         'locations' => $locationRepository->findAll(),
    //     ]);
    //     $dompdf->loadHtml($test);

    //     // (Optional) Setup the paper size and orientation
    //     $dompdf->setPaper('A4', 'landscape');

    //     // Render the HTML as PDF
    //     $dompdf->render();

    //     // Output the generated PDF to Browser
    //     $dompdf->stream();



    //     return  $test;


    // }

    /**
     * @Route("/new", name="location_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $location = new Location();
        $form = $this->createForm(LocationType::class, $location);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($location);
            $entityManager->flush();

            return $this->redirectToRoute('location_index');
        }

        return $this->render('location/new.html.twig', [
            'location' => $location,
            'form' => $form->createView(),
        ]);
    }




    /**
     * @Route("/{id}", name="location_show", methods={"GET"})
     */
    public function show(Location $location): Response
{
    return $this->render('location/show.html.twig', [
        'location' => $location,
    ]);
}

    /**
     * @Route("/{id}/edit", name="location_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Location $location): Response
{
    $form = $this->createForm(LocationType::class, $location);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('location_index');
    }

    return $this->render('location/edit.html.twig', [
        'location' => $location,
        'form' => $form->createView(),
    ]);
}

    /**
     * @Route("/{id}", name="location_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Location $location): Response
{
    if ($this->isCsrfTokenValid('delete' . $location->getId(), $request->request->get('_token'))) {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($location);
        $entityManager->flush();
    }

    return $this->redirectToRoute('location_index');
}

    /**
     * @Route("/api/show", name="location_show_api", methods={"GET"})
     */
    public function showAll(SerializerInterface $SerializerInterface): Response
{
    $formAide = $this->getDoctrine()->getManager()
        ->getRepository(Location::class)
        ->findAll();
    $SerializerInterface = new Serializer([new ObjectNormalizer()]);
    $formatted = $SerializerInterface->normalize($formAide);
    return new JsonResponse($formatted, 200);
}

    /**
     * @Route("/new1/{description}/{localisation}/{prix}/{type}/{places_dispo}/{URL}", name="location_new", methods={"GET","POST"})
     */
    public function new1(SerializerInterface $serializer, $description, $localisation, $prix, $type, $places_dispo, $URL): Response
{
    $loc = new Location();
    $entityManager = $this->getDoctrine()->getManager();
    $loc->setDescription($description);
    $loc->setLocalisation($localisation);
    $loc->setPrix($prix);
    $loc->setType($type);
    $loc->getPlaces_Dispo($places_dispo);
    $loc->setURl($URL);
    $entityManager->persist($loc);
    $entityManager->flush();

    $serializer = new Serializer([new ObjectNormalizer()]);
    $formatted = $serializer->normalize($loc);
    return new JsonResponse($formatted, 200);


}

/**
 * @Route("/edit1/{description}/{localisation}/{prix}/{type}/{places_dispo}/{URL}", name="location_edit", methods={"GET","POST"})
 */
    public function edit1(SerializerInterface $serializer, $description, $localisation, $prix, $type, $places_dispo, $URL, Request $request): Response
{
    $loc = new Location();
    $entityManager = $this->getDoctrine()->getManager()
        ->getRepository(Location::class)
        ->find($request->get("id"));
    $loc->setdescription($description);
    $loc->setlocalisation($localisation);
    $loc->setprix($prix);
    $loc->settype($type);
    $loc->setPlaces_Dispo($places_dispo);
    $loc->setURl($URL);
    $entityManager->persist($loc);
    $entityManager->flush();

    $serializer = new Serializer([new ObjectNormalizer()]);
    $formatted = $serializer->normalize($loc);
    return new JsonResponse($formatted, 200);


}

/**
 * @Route("/delete/{id}", name="location_delete", methods={"GET"})
 */
    public function delete1(Request $request): Response
{
    $myid = $request->get("id");
    $entityManager = $this->getDoctrine()->getManager();
    $loc = $entityManager->getRepository(Location::class)->find($myid);
    if ($loc != null) {
        $entityManager->remove($loc);
        $entityManager->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize("location Supprimé avec succ");
        return new JsonResponse($formatted);
    }


    return new JsonResponse("id produit  invalide ");
}

}
