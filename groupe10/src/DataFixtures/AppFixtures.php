<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Profil;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        $tabRoles = ['ROLE_ADMIN', 'ROLE_FORMATEUR', 'ROLE_APPRENANT'];
        $tab = ['admin', 'formateur', 'apprenant'];

        $tabUser = [
            [
                "prenom"=>"admin",
                "nom"=>"admin",
                "email"=>"admin@gmail.com",
                "password"=>"admin",
                "telephone"=>"+221771231212",
                "adresse"=>"Grand Dakar",
                "genre"=>"",
                "statut"=>"",
                "info_complementaire"=>""
            ],
            [
                "prenom"=>"formateur",
                "nom"=>"formateur",
                "email"=>"formateur@gmail.com",
                "password"=>"formateur",
                "telephone"=>"+221773212121",
                "adresse"=>"Dieuppeul",
                "genre"=>"",
                "statut"=>"",
                "info_complementaire"=>""
            ],
            [
                "prenom"=>"apprenant",
                "nom"=>"apprenant",
                "email"=>"apprenant@gmail.com",
                "password"=>"apprenant",
                "telephone"=>"+221771433434",
                "adresse"=>"Mbao",
                "genre"=>"Masculin",
                "statut"=>"Actif",
                "info_complementaire"=>"Passioné de technologie."
            ],
        ];

        for ($i=0; $i<count($tab); $i++){
            $profils = new Profil();
            $profils->setLibelle($tab[$i]);

            $user = new User();

            $user->setUsername($tab[$i]);
            $role = [$tabRoles[$i]];
            $user->setRoles($role);
            $user->setPrenom($tabUser[$i]["prenom"]);
            $user->setNom($tabUser[$i]["nom"]);
            $user->setEmail($tabUser[$i]["email"]);
            $user->setTelephone($tabUser[$i]["telephone"]);
            $user->setAdresse($tabUser[$i]["adresse"]);
            $user->setGenre($tabUser[$i]["genre"]);
            $user->setStatut($tabUser[$i]["statut"]);
            $user->setInfoComplementaire($tabUser[$i]["info_complementaire"]);

            $password = $this->encoder->encodePassword($user, $tabUser[$i]["password"]);
            $user->setPassword($password);
            $user->setProfil($profils);

            $manager->persist($profils);
            $manager->persist($user);

            $manager->flush();
        }
    }
}
