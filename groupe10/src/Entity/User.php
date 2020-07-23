<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use ApiPlatform\Core\Action\NotFoundAction;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity("email",message="Cette adresse mail est déja utilisé.")
 * @ApiResource(
 *     collectionOperations={
 *         "get"={"path"="/admin/users"},
 *         "post"={"path"="/admin/users"},
 *         "get_apprenants"={
 *          "method"="GET",
 *          "path"="/apprenants" ,
 *          "normalization_context"={"groups":"apprenant:read"},
 *          "route_name"="apprenant_liste",
 *          }
 *     },
 *     itemOperations={
 *         "get"={"path"="/admin/users/{id}",
 *         "requirements"={"id"="\d+"}
 *          },
 *         "put"={"path"="/admin/users/{id}",
 *         "requirements"={"id"="\d+"}
 *          },
 *     }
 * )
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"apprenant:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * 
     */
    private $username;

    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"apprenant:read"})
     */
    private $avatar;

    /**
     * @ORM\ManyToOne(targetEntity=Profil::class, inversedBy="users")
     * @Groups({"apprenant:read"})
     */
    private $profil;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *     message = "Ce Champ ne doit pas être vide."
     * )
     * @Groups({"apprenant:read"})
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *     message = "Ce Champ ne doit pas être vide."
     * )
     * @Groups({"apprenant:read"})
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *     message = "Ce Champ ne doit pas être vide."
     * )
     * @Groups({"apprenant:read"})
     */
    private $telephone;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *     message = "Ce Champ ne doit pas être vide."
     * )
     * @Groups({"apprenant:read"})
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"apprenant:read"})
     */
    private $genre;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"apprenant:read"})
     */
    private $statut;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"apprenant:read"})
     */
    private $info_complementaire;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *     message = "Ce Champ ne doit pas être vide."
     * )
     * @Groups({"apprenant:read"})
     */
    private $email;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_'.$this->profil->getLibelle();

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getProfil(): ?Profil
    {
        return $this->profil;
    }

    public function setProfil(?Profil $profil): self
    {
        $this->profil = $profil;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getGenre(): ?string
    {
        return $this->genre;
    }

    public function setGenre(?string $genre): self
    {
        $this->genre = $genre;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getInfoComplementaire(): ?string
    {
        return $this->info_complementaire;
    }

    public function setInfoComplementaire(?string $info_complementaire): self
    {
        $this->info_complementaire = $info_complementaire;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }
}
