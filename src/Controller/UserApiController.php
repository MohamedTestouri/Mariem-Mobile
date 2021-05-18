<?php

namespace App\Controller;
use App\Entity\Images;
use App\Form\EditProfileType;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/user/api")
 */
class UserApiController extends AbstractController
{
    /**
     * @Route("/login", name="user_login")
     */
    public function login(Request $request): Response
    {
        $email = $request->query->get('email');
        $password = $request->query->get('password');

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(Membre::class)->findOneBy(['email'=>$email]);
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
     * @Route("/signup", name="user_signup")
     */
    public function signUp(Request $request,UserPasswordEncoderInterface $encoder ): Response
    {
        $email = $request->query->get('email');
        $role = $request->query->get('roles');
        $password = $request->query->get('password');
        $nom = $request->query->get('nom');
        $prenom = $request->query->get('prenom');
        $phone = $request->query->get('phone');
        $adresse = $request->query->get('adresse');
        $photo = $request->query->get('photos');
        $job = $request->query->get('job');
        $pseudo = $request->query->get('Pseudo');
        $localisation = $request->query->get('localisation');

        //test addresse lazm bl @
        if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
            return new Response("email invalide");
        }
        $user = new User();
        $user->setEmail($email);
        $user->setRoles(array($role));
        $user->setPassword($encoder->encodePassword($user, $password));
        $user->setNom($nom);
        $user->setPrenom($prenom);
        $user->setPhone($phone);
        $user->setAdresse($adresse);
        $user->setPhotos($photo);
       /* if($request->files->get('photos') != null){
            $file = $request->files->get("photos"); // url image
            $fileName = $file->getClientOriginalName();//nom image
            $file->move(
                $fileName
            );
            $user->setPhotos($fileName);
        }*/

        $user->setJob($job);
        $user->setPseudo($pseudo);
        $user->setLocalisation($localisation);

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
     * @Route("/findemail", name="user_find")
     */
    public function findByEmail(Request $request, NormalizerInterface $Normalizer){

        $email = $request->query->get('email');

        $repository = $this->getDoctrine()->getRepository(User::class);
        $user = $repository->findBy(array(['email'=>$email]));
        $jsonContent = $Normalizer->normalize($user, 'json');

        return new Response(json_encode($jsonContent));
    }
}
