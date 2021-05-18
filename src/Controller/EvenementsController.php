<?php

namespace App\Controller;

use App\Entity\Evenements;
use App\Form\EvenementsType;
use App\Repository\EvenementsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\JsonResponse;


/**
 * @Route("/evenements")
 */
class EvenementsController extends AbstractController
{
    /**
     * @Route("/", name="evenements_index", methods={"GET"})
     */
    public function index(EvenementsRepository $evenementsRepository): Response
    {
        return $this->render('evenements.html.twig', [
            'evenements' => $evenementsRepository->findAll(),
        ]);
    }
    /**
     * @Route ("/order", name="order_index_place", methods={"GET"})
     */
    public function orderIndex(EvenementsRepository $evenementsRepository): Response{
        return $this->render('evenements.html.twig', [
            'evenements' => $evenementsRepository->orderByPrice(),
        ]);
    }
    /**
     * @Route ("/orderPlace", name="order_index", methods={"GET"})
     */
    public function orderPlaceIndex(EvenementsRepository $evenementsRepository): Response{
        return $this->render('evenements.html.twig', [
            'evenements' => $evenementsRepository->orderByPlace(),
        ]);
    }

    /**
     * @Route("/new", name="evenements_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $evenement = new Evenements();
        $form = $this->createForm(EvenementsType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($evenement);
            $entityManager->flush();

            return $this->redirectToRoute('evenements_index');
        }

        return $this->render('evenements/ajoutevt.html.twig', [
            'evenement' => $evenement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/res", name="evenements_show", methods={"GET"})
     */
    public function show(Request $request, EvenementsRepository $evenementsRepository): Response
    {
        $id = $request->query->get('id');
        $nb = $request->query->get('nb');
        // $em = $this->getDoctrine();
        $events = $evenementsRepository->find((int) $id);
        $nb = $events->getNombrePlace() - $nb;
            $events->setNombrePlace((int) $nb);
            $em = $this->getDoctrine()->getManager();
            $em->persist($events);
            $em->flush();
            return $this->render('evenements.html.twig', ['evenements' => $evenementsRepository->findAll()]);

    }

    /**
     * @Route("/{id}/edit", name="evenements_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Evenements $evenement): Response
    {
        $form = $this->createForm(EvenementsType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('evenements_index');
        }

        return $this->render('evenements/edit.html.twig', [
            'evenement' => $evenement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="evenements_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Evenements $evenement): Response
    {
        if ($this->isCsrfTokenValid('delete'.$evenement->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($evenement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('evenements_index');
    }
    public function findBestEventsAction()
    {
        $em=$this->getDoctrine()->getManager();
        $events=$em->getRepository(Evenement::class)->BestEvents();
        //var_dump($events);die;
        return $this->render('@Eco/Front/Evenement/indexBest.html.twig',array("events"=>$events));

    }

    /**
     * @Route("/showevent", name="evenements_index", methods={"GET"})
     */
    public function findall(EvenementsRepository $evenementsRepo): Response
    {
        $event=$evenementsRepo->findAll();
        $serializer = new Serializer([new DateTimeNormalizer, new ObjectNormalizer()]);
        $formatted = $serializer->normalize($event);
        return new JsonResponse($formatted);
    }
    /*
     * @Route("/event/api/showMyEvent/", name="show_api_myEvent", methods={"GET"})
     *
    public function showMyEvent(SerializerInterface $SerializerInterface, Request $request): Response
    {
        $eve=$this->getDoctrine()->getManager()
        ->getRepository(Evenements::class)
       ->findAll($request->get(''));
       $SerializerInterface= new Serializer([new ObjectNormalizer()]);
       $formatted = $SerializerInterface->normalize($eve);
         return new JsonResponse($formatted,200);
    }*/

    /**
     * @Route("/new1/{title}/{description}/{prix}/{localisation}/{date}/{categorie}/{nombrePlace}", name="evenements_new", methods={"GET","POST"})
     */
    public function new1(SerializerInterface $serializer,$title,$description,$prix,$localisation,$date,$categorie,$nombrePlace): Response
    {
        $eve = new Evenements();
        $date=new \DateTime();
        $entityManager = $this->getDoctrine()->getManager();
        $eve->setTitle($title);
        $eve->setDescription($description);
        $eve->setPrix($prix);
        $eve->setLocalisation($localisation);
        $eve->setDate($date);
        $eve->setCategorie($categorie);
        $eve->setNombrePlace($nombrePlace);
           $entityManager->persist($eve);
         $entityManager->flush();
  
         $serializer = new Serializer([new ObjectNormalizer()]);
         $formatted = $serializer->normalize($eve);
         return new JsonResponse($formatted,200);


}
/**
     * @Route("/{id}/edit1/{title}/{description}/{prix}/{localisation}/{date}/{categorie}/{nombrePlace}", name="evenements_edit", methods={"GET","POST"})
     */
    public function edit1(SerializerInterface $serializer,$title,$description,$prix,$localisation,$date,$categorie,$nombrePlace,Request $request): Response
    {
        
        $date=new \DateTime();
        $entityManager = $this->getDoctrine()->getManager();
        $formAide=$this->getDoctrine()->getManager()
                ->getRepository(Evenements::class)
                ->find($request->get("id"));
                $formAide->setTitle($title);
                $formAide->setDescription($description);
                $formAide->setPrix($prix);
                $formAide->setLocalisation($localisation);
                $formAide->setDate($date);
                $formAide->setCategorie($categorie);
                $formAide->setNombrePlace($nombrePlace);
                   $entityManager->persist($formAide);
                 $entityManager->flush();
          
                 $serializer = new Serializer([new ObjectNormalizer()]);
                 $formatted = $serializer->normalize($formAide);
                 return new JsonResponse("Even a ete modifie avec succes");
}
/**
     * @Route("/delete/{id}", name="evenements_delete", methods={"GET"})
     */
    public function delete1(Request $request): Response
    {
        $myid=$request->get("id");
        $entityManager = $this->getDoctrine()->getManager();
        $eve=$entityManager->getRepository(Evenements::class)->find($myid);  
        if($eve!=null){
                 $entityManager->remove($eve);
                 $entityManager->flush();
                 $serializer = new Serializer([new ObjectNormalizer()]);
                 $formatted = $serializer->normalize("evenement Supprimé avec succ");
                 return new JsonResponse($formatted);
                          }
        
       
         return new JsonResponse("id produit  invalide ");
     }

    /*
     * @Route ("/reserver", name="reserver", methode={"GET"})
     */
   /* public function reserverEvent(Request $request, EvenementsRepository  $evenementsRepository): Response{
       // $events = new Evenements();
        $id = $request->query->get('id');
       // $em = $this->getDoctrine();
        $events = $evenementsRepository->find($id);
foreach ($events as $e){
    $e->setNombrePlace($id);
    $em = $this->getDoctrine()->getManager();
    $em->persist($e);
    $em->flush();
}
    }*/
}
