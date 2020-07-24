<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FormateurController extends AbstractController
{
    /**
    * @Route(
    * name="liste_Formateur",
    * path="api/Formateurs",
    * methods={"GET"},
    * defaults={
    * "_controller"="\app\ControllerFormateurController::getFormateur",
    * "_api_resource_class"=User::class,
    * "_api_collection_operation_name"="get_Formateurs"
    * }
    * )
    */
    public function getFormateur(UserRepository $repo)
    {
        $Formateurs= $repo->findByProfil("FORMATEUR");
        return $this->json($Formateurs,Response::HTTP_OK,);
    }
}
