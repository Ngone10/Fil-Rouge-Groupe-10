<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use ApiPlatform\Core\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ApprenantController extends AbstractController
{
    /**
    * @Route(
    * name="apprenant_liste",
    * path="api/apprenants",
    * methods={"GET"},
    * defaults={
    * "_controller"="\app\ControllerApprenantController::getApprenant",
    * "_api_resource_class"=User::class,
    * "_api_collection_operation_name"="get_apprenants"
    * }
    * )
    */
    public function getApprenant(UserRepository $repo)
    {
        $apprenants= $repo->findByProfil("APPRENANT");
        return $this->json($apprenants,Response::HTTP_OK,);
    }

    /**
    * @Route(
    * name="ajout_apprenant",
    * path="api/apprenants",
    * methods={"POST"},
    * defaults={
    * "_controller"="\app\ControllerApprenantController::postApprenant",
    * "_api_resource_class"=User::class,
    * "_api_collection_operation_name"="post_apprenants"
    * }
    * )
    */
    public function postApprenant(Request $request,SerializerInterface $serializer,UserPasswordEncoderInterface $encoder,EntityManagerInterface $manager,ValidatorInterface $validator,UserRepository $userRepository)
    {
        $apprenant_profil = $userRepository->findOneBy([
            "id" => 7
        ]);
        $apprenant = $request->request->all();
        $avatar = $request->files->get("avatar");
        $avatar = fopen($avatar->getRealPath(),"rb");
        $apprenant["avatar"] = $avatar;
        $apprenant = $serializer->denormalize($apprenant,"App\Entity\User");
        $errors = $validator->validate($apprenant);
        if (count($errors)){
            $errors = $serializer->serialize($errors,"json");
            return new JsonResponse($errors,Response::HTTP_BAD_REQUEST,[],true);
        }else if($apprenant->getProfil()->getId() != $apprenant_profil->getId()){
            $errors = [
                "message" => "Veuillez choisir le profil apprenant"
            ];
            $errors = $serializer->serialize($errors,"json");
            return new JsonResponse($errors,Response::HTTP_BAD_REQUEST,[],true);
        }
        $password = $apprenant->getPassword();
        $apprenant->setPassword($encoder->encodePassword($apprenant,$password));
        $manager->persist($apprenant);
        $manager->flush();
        fclose($avatar);
        return $this->json($apprenant,Response::HTTP_CREATED);
    }




    /*public function postApprenant(Request $request, UserPasswordEncoderInterface $encoder, SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $em, UserRepository $userRepository)
    {
        $user = $request->request->all();
        dd($user);
        $user = $serializer->denormalize($user, "App\Entity\User");
        $errors[] = $validator->validate($user);
        if (count($errors)){
            $errors = $serializer->serialize($errors, "json");
            return new JsonResponse($errors, Response::HTTP_BAD_REQUEST, [], true);
        }
        $password = $user->getPassword();
        $user->setPassword($encoder->encodePassword($user, $password));
        $em->persist($user);
        $em->flush();
        return $this->json($serializer->normalize($user),Response::HTTP_CREATED);
    }*/
}
