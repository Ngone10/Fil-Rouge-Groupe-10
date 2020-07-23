<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProfilRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=ProfilRepository::class)
 * @UniqueEntity("libelle",message="Ce libellé existe déja.")
 * @ApiResource(
 *     collectionOperations={
 *         "get"={"path"="/admin/profils"},
 *         "post"={"path"="/admin/profils"},
 *     },
 *     itemOperations={
 *         "get"={"path"="/admin/profils/{id}",
 *          "requirements"={"id"="\d+"}
 *          },
 *         "put"={"path"="/admin/profils/{id}",
 *          "requirements"={"id"="\d+"}
 *          },
 *          "delete"={"path"="/admin/profils/{id}",
 *          "requirements"={"id"="\d+"}
 *          },
 *     }
 * )
 */
class Profil
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"profil:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *     message = "Ce Champ ne doit pas être vide."
     * )
     */
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="profil")
     */
    private $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setProfil($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getProfil() === $this) {
                $user->setProfil(null);
            }
        }

        return $this;
    }
}
