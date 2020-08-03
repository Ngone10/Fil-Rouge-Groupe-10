<?php

namespace App\Controller;

use App\Entity\Competences;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CompetencesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use ApiPlatform\Core\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CompetencesController extends AbstractController
{
        /**
        * @Route(path="/api/admin/competences", name="api_get_competences", methods={"GET"})
        */
        public function getGrpCompetences(CompetencesRepository $repo)
        {
            if ($this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_FORMATEUR') || $this->isGranted('ROLE_CM')){
                $grpCompetences= $repo->findAll();
                return $this->json($grpCompetences,Response::HTTP_OK,);
            }
            else{
                return $this->json("Access denied!!!");
            }
        }
    
        /**
        * @Route(path="/api/admin/competences", name="api_add_competences", methods={"POST"})
        */
        public function addCompetences(Request $request,SerializerInterface $serializer,EntityManagerInterface $manager,ValidatorInterface $validator)
        {
            if ($this->isGranted('ROLE_ADMIN')){

                // Get Body content of the Request
                $competencesJson = $request->getContent();

                // Deserialize and insert into dataBase
                $competences = $serializer->deserialize($competencesJson, Competences::class,'json');

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($competences);
                $entityManager->flush();
                return new JsonResponse("success",Response::HTTP_CREATED,[],true);

            }
            else{
                return $this->json("Access denied!!!");
            }
        }
}
