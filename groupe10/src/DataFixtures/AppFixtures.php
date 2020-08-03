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

        $tabRoles = ['ROLE_ADMIN', 'ROLE_FORMATEUR', 'ROLE_CM', 'ROLE_APPRENANT'];
        $tab = ['ADMIN', 'FORMATEUR', 'CM', 'APPRENANT'];

        $tabUser = [
            [
                "email"=>"admin@gmail.com",
                "password"=>"admin",
                "username"=>"Niass",
                "prenom"=>"Baye",
                "nom"=>"Niass",
                "adresse"=>"Inconnue"
            ],
            [
                "email"=>"formateur@gmail.com",
                "password"=>"formateur",
                "username"=>"Wane",
                "prenom"=>"Birane",
                "nom"=>"Wane",
                "adresse"=>"Thies"
            ],
            [
                "email"=>"cm@gmail.com",
                "password"=>"cm",
                "username"=>"Yankoba",
                "prenom"=>"Yankoba",
                "nom"=>"Mane",
                "adresse"=>"Inconnue"
            ],
            [
                "email"=>"apprenant@gmail.com",
                "password"=>"apprenant",
                "username"=>"qwerty",
                "prenom"=>"Abdel",
                "nom"=>"Kader",
                "adresse"=>"Yoff"
            ]
        ];

            for ($i=0; $i<count($tab); $i++){
                $profils = new Profil();
                $profils->setLibelle($tab[$i]);

                $user = new User();

                $role = [$tabRoles[$i]];
                $user->setRoles($role);
                $user->setUsername($tabUser[$i]["username"]);
                $user->setEmail($tabUser[$i]["email"]);
                $user->setPrenom($tabUser[$i]["prenom"]);
                $user->setNom($tabUser[$i]["nom"]);
                $user->setAdresse($tabUser[$i]["adresse"]);


                $password = $this->encoder->encodePassword($user, $tabUser[$i]["password"]);
                $user->setPassword($password);
                $user->setProfil($profils);

                $manager->persist($profils);
                $manager->persist($user);

                $manager->flush();
            }
    }
}