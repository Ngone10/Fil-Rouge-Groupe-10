<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\UserRepository;

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
}
