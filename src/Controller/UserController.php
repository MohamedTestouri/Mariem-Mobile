<?php

namespace App\Controller;
use App\Entity\Images;
use App\Form\EditProfileType;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\JsonResponse;
/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/api/login", name="user_login")
     */
    public function login(Request $request): Response
    {
        $email = $request->query->get('email');
        $password = $request->query->get('password');

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->findOneBy(['email'=>$email]);
        if($user){

            if(password_verify($password,$user->getPassword())) {


                $serializer = new Serializer([new ObjectNormalizer()]);
                $formatted = $serializer->normalize($user);
                return new JsonResponse($formatted);
            }
            else{
                //password not found
                return new Response("pass");
            }
        }
        else{
            //email not found
            return new Response("failed");
        }
    }

    /**
     * @Route("/api/signup", name="user_signup")
     */
    public function signUp(Request $request,UserPasswordEncoderInterface $encoder ): Response
    {
        $email = $request->query->get('email');
      //  $role = $request->query->get('roles');
        $password = $request->query->get('password');
        $nom = $request->query->get('nom');
        $prenom = $request->query->get('prenom');
        $phone = $request->query->get('phone');
        //$adresse = $request->query->get('adresse');
        //$photo = $request->query->get('photos');
        //$job = $request->query->get('job');
        //$pseudo = $request->query->get('Pseudo');
        //$localisation = $request->query->get('localisation');

        //test addresse lazm bl @
        if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
            return new Response("email invalide");
        }
        $user = new User();
        $user->setEmail($email);
    //    $user->setRoles(array($role));
        $user->setPassword($encoder->encodePassword($user, $password));
        $user->setNom($nom);
        $user->setPrenom($prenom);
        $user->setPhone($phone);
     //   $user->setAdresse($adresse);
      //  $user->setPhotos($photo);
        /* if($request->files->get('photos') != null){
             $file = $request->files->get("photos"); // url image
             $fileName = $file->getClientOriginalName();//nom image
             $file->move(
                 $fileName
             );
             $user->setPhotos($fileName);
         }*/

     //   $user->setJob($job);
       // $user->setPseudo($pseudo);
        //$user->setLocalisation($localisation);

        try{
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return new JsonResponse("compte cree",200);
        }catch (\Exception $ex){
            return new Response("exception".$ex->getMessage());
        }
    }

    /**
     * @Route("/api/findemail", name="user_find")
     */
    public function findByEmail(Request $request, NormalizerInterface $Normalizer){

        $email = $request->query->get('email');

        $user = $this->getDoctrine()->getRepository(User::class)->findByEmail($email);
        $jsonContent = $Normalizer->normalize($user, 'json');

        return new Response(json_encode($jsonContent));
    }



    /**
     * @Route("/", name="user_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="user_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_delete", methods={"DELETE"})
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_index');
    }
    /**
     * @Route("/user/profil/modifier", name="user_profil_modifier")
     */
    public function editProfile(Request $request)
    {
        $user = $this->getUser();
        $form = $this->createForm(EditProfileType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('message', 'Profil mis à jour');
            return $this->redirectToRoute('user');
        }

        return $this->render('user/editprofile.html.twig', [
            'form' => $form->createView(),
        ]);
    }

/**
     * @Route("/new1/{email}/{password}/{nom}/{prenom}/{phone}/{adresse}/{photos}/{job}/{pseudo}/{localisation}", name="commentaire_new", methods={"GET","POST"})
     */
    public function new1($email,$password,$nom,$prenom,$phone,$adresse,$photos,$job,$pseudo,$localisation,Request $request): Response
    {
       
        $formAide = new User();
        $entityManager = $this->getDoctrine()->getManager();
        $formAide->setEmail($email );
        $formAide->setPassword($password);
        $formAide->setNom($nom);
        $formAide->setPrenom($prenom);
        $formAide->setPhone($phone);

        $formAide->setAdresse($adresse);
        $formAide->setPhotos($photos);
        $formAide->setJob($job);

        $formAide->setPseudo($pseudo);
        $formAide->setLocalisation($localisation);

        $formAide->setRoles(["user"]);


           $entityManager->persist($formAide);
         $entityManager->flush();
  
         $serializer = new Serializer([new ObjectNormalizer()]);
         $formatted = $serializer->normalize($formAide);
         return new JsonResponse($formatted,200);
    }
}
