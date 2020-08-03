<?php

namespace App\Controller;

use App\Entity\Competences;
use App\Entity\GroupeCompetences;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\GroupeCompetencesRepository;
use Symfony\Component\Routing\Annotation\Route;
use ApiPlatform\Core\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GrpCompetencesController extends AbstractController
{
        /**
        * @Route(path="/api/admin/grpCompetences", name="api_get_grpCompetences", methods={"GET"})
        */
        public function getGrpCompetences(GroupeCompetencesRepository $repo)
        {
            if ($this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_FORMATEUR') || $this->isGranted('ROLE_CM')){
                $grpCompetences= $repo->findAll();
                return $this->json($grpCompetences,Response::HTTP_OK);
            }
            else{
                return $this->json("Access denied!!!");
            }
        }
    
        /**
        * @Route(path="/api/admin/grpCompetences", name="api_add_grpCompetences", methods={"POST"})
        */
        public function addGrpCompetences(Request $request,SerializerInterface $serializer,EntityManagerInterface $manager,ValidatorInterface $validator)
        {
            if ($this->isGranted('ROLE_ADMIN')){

                // Get Body content of the Request
                $grpCompetencesJson = $request->getContent();
                
                // Deserialize and insert into dataBase
                $grpCompetences = $serializer->deserialize($grpCompetencesJson, GroupeCompetences::class,'json');

                // Data Validation
                //$errors = $validator->validate($grpCompetences);
                //if (count($errors)>0) {
                //    $errorsString =$serializer->serialize($errors,"json");
                //    return new JsonResponse( $errorsString ,Response::HTTP_BAD_REQUEST,[],true);
                //}

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($grpCompetences);
                $entityManager->flush();
                return new JsonResponse("success",Response::HTTP_CREATED,[],true);

            }
            else{
                return $this->json("Access denied!!!");
            }
        }
        
}
