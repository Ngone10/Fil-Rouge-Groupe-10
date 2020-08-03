<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Admin;
use App\Entity\Apprenant;
use App\Entity\Formateur;
use App\Entity\GroupeCompetences;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UserController extends AbstractController
{

    /**
    * @Route(path="/api/admin/users", name="api_get_users", methods={"GET"})
    */
    public function getUsers(UserRepository $repo)
    {
        if ($this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_FORMATEUR') || $this->isGranted('ROLE_CM')){
            $users= $repo->findAll();
            return $this->json($users,Response::HTTP_OK);
        }
        else{
            return $this->json("Access denied!!!");
        }
    }

    /**
    * @Route(path="/api/admin/users", name="api_add_users", methods={"POST"})
    */
    public function addUser(SerializerInterface $serializer,ValidatorInterface $validator, Request $request,UserPasswordEncoderInterface $encoder)
    {
        // Get Body content of the Request
        $userJson = $request->getContent();
        
        // Deserialize and insert into dataBase
        $user = $serializer->deserialize($userJson, User::class,'json');

        // Data Validation
        $errors = $validator->validate($user);
        if (count($errors)>0) {
            $errorsString =$serializer->serialize($errors,"json");
            return new JsonResponse( $errorsString ,Response::HTTP_BAD_REQUEST,[],true);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $password = $user->getPassword();
        $user->setPassword($encoder->encodePassword($user, $password));
        $entityManager->persist($user);
        $entityManager->flush();
        return new JsonResponse("success",Response::HTTP_CREATED,[],true);

    }

        /**
        * @Route(path="/api/admin", name="ajout_admin", methods={"POST"})
        */
        public function addAdmin(Request $request,SerializerInterface $serializer,UserPasswordEncoderInterface $encoder,EntityManagerInterface $manager,ValidatorInterface $validator)
        {
            if ($this->isGranted('ROLE_ADMIN')){

                // Get Body content of the Request
                $userJson = $request->getContent();
                
                // Deserialize and insert into dataBase
                $user = $serializer->deserialize($userJson, Admin::class,'json');

                // Data Validation
                $errors = $validator->validate($user);
                if (count($errors)>0) {
                    $errorsString =$serializer->serialize($errors,"json");
                    return new JsonResponse( $errorsString ,Response::HTTP_BAD_REQUEST,[],true);
                }

                $entityManager = $this->getDoctrine()->getManager();
                $password = $user->getPassword();
                $user->setPassword($encoder->encodePassword($user, $password));
                $entityManager->persist($user);
                $entityManager->flush();
                return new JsonResponse("success",Response::HTTP_CREATED,[],true);

            }
            else{
                return $this->json("Access denied!!!");
            }
        }

        /**
        * @Route(path="/api/formateurs", name="ajout_formateurs", methods={"POST"})
        */
        public function addFormateur(Request $request,SerializerInterface $serializer,UserPasswordEncoderInterface $encoder,EntityManagerInterface $manager,ValidatorInterface $validator)
        {
            if ($this->isGranted('ROLE_ADMIN')){

                // Get Body content of the Request
                $userJson = $request->getContent();
                
                // Deserialize and insert into dataBase
                $user = $serializer->deserialize($userJson, Formateur::class,'json');

                // Data Validation
                $errors = $validator->validate($user);
                if (count($errors)>0) {
                    $errorsString =$serializer->serialize($errors,"json");
                    return new JsonResponse( $errorsString ,Response::HTTP_BAD_REQUEST,[],true);
                }

                $entityManager = $this->getDoctrine()->getManager();
                $password = $user->getPassword();
                $user->setPassword($encoder->encodePassword($user, $password));
                $entityManager->persist($user);
                $entityManager->flush();
                return new JsonResponse("success",Response::HTTP_CREATED,[],true);

            }
            else{
                return $this->json("Access denied!!!");
            }
        }

        /**
        * @Route(path="/api/apprenants", name="ajout_apprenants", methods={"POST"})
        */
        public function addApprenant(Request $request,SerializerInterface $serializer,UserPasswordEncoderInterface $encoder,EntityManagerInterface $manager,ValidatorInterface $validator)
        {
            if ($this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_FORMATEUR')){

                // Get Body content of the Request
                $userJson = $request->getContent();
                
                // Deserialize and insert into dataBase
                $user = $serializer->deserialize($userJson, Apprenant::class,'json');

                // Data Validation
                $errors = $validator->validate($user);
                if (count($errors)>0) {
                    $errorsString =$serializer->serialize($errors,"json");
                    return new JsonResponse( $errorsString ,Response::HTTP_BAD_REQUEST,[],true);
                }

                $entityManager = $this->getDoctrine()->getManager();
                $password = $user->getPassword();
                $user->setPassword($encoder->encodePassword($user, $password));
                $entityManager->persist($user);
                $entityManager->flush();
                return new JsonResponse("success",Response::HTTP_CREATED,[],true);

            }
            else{
                return $this->json("Access denied!!!");
            }
        }

        //Seuls Les Formateurs/CM/ADMIN Peuvent Lister les Apprenants!!!
        public function showApprenant(UserRepository $repo)
        {
            if ($this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_FORMATEUR') || $this->isGranted('ROLE_CM') ) {
                $apprenants= $repo->findByProfil("APPRENANT");
                return $this->json($apprenants,Response::HTTP_OK,);
            }
            else{
                return $this->json("Access denied!!!");
            }
        }
    
        //Seuls Les Admins/Formateurs/CM Peuvent Lister un Apprenant Par Son ID!!!
        public function showApprenantById(UserRepository $repo, $id)
        {
            //$security->getToken();
            if ($this->isGranted('ROLE_ADMIN')||$this->isGranted('ROLE_FORMATEUR')|| $this->isGranted('ROLE_CM')) {
                $apprenants= $repo->findByProfilById("APPRENANT",$id);
                return $this->json($apprenants,Response::HTTP_OK,);
            }
            else{
                return $this->json("Access denied!!!");
            }
        }
    
    
       //Seul Le CM/Admin Peut Lister Les Formateurs!!!
        public function showFormateur(UserRepository $repo)
        {
            if ($this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_CM') ) {
            $formateurs= $repo->findByProfil("FORMATEUR");
            return $this->json($formateurs,Response::HTTP_OK);
            }
            else{
                return $this->json("Access denied!!!");
            }
        }
    
    
        //Seuls Les /Admin/Formateurs/CM Peuvent Lister un Formateur Par Son ID!!!
        public function showFormateurById(UserRepository $repo, $id)
        {
            if ($this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_CM') ) {
                $formateurs= $repo->findByProfilById("FORMATEUR",$id);
                return $this->json($formateurs,Response::HTTP_OK);
            }
            else{
                return $this->json("Access denied!!!");
            }
            
        }
    

        // Show Apprenant informations Only !!!
    
        public function showApprenantByHisId( UserRepository $repo, $id ) {
    
            if ( $this->isGranted('ROLE_APPRENANT') ) {
                $idApprenant = $this->getUser()->getId();
                $apprenant = $repo->findByProfilById("APPRENANT", $id);
    
                if ( $idApprenant == $id ) {
                    return $this->json($apprenant, Response::HTTP_OK,);
                }else {
                    return $this->json("Vous n'avez pas acces à ce profil, désolé !!!");
                }
    
            }
    
        }

}
