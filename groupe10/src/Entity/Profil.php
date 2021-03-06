<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProfilRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ProfilRepository::class)
 * @ApiResource(
 *       attributes={
 *                  "normalization_context"={"groups"={"read"}},
 *                  "denormalization_context"={"groups"={"write"}}
 *       },
 *      collectionOperations={
 *         "get"={"path"="/admin/profils",
 *                  "access_control"="(is_granted('ROLE_ADMIN'))",
 *                  "access_control_message"="Vous n'avez pas access à cette Ressource"},
 *                  "post"={"path"="/admin/profils"},
 *         "get_profils_users"={
 *                  "method"="GET",
 *                  "path"="/admin/profils/{id}/users",
 *                  "requirements"={"id"="\d+"},
 *                  "access_control"="(is_granted('ROLE_ADMIN'))",
 *                  "access_control_message"="Vous n'avez pas access à cette Ressource"
 *          }
 *     },
 *       itemOperations={
 *         "get"={"path"="/admin/profils/{id}",
 *                  "requirements"={"id"="\d+"},
 *                  "access_control"="(is_granted('ROLE_ADMIN'))",
 *                  "access_control_message"="Vous n'avez pas access à cette Ressource"
 *          },
 *         "put"={"path"="/admin/profils/{id}",
 *                  "requirements"={"id"="\d+"},
 *                  "access_control"="(is_granted('ROLE_ADMIN'))",
 *                  "access_control_message"="Vous n'avez pas access à cette Ressource"
 *          },
 *          "delete"={"path"="/admin/profils/{id}",
 *                  "requirements"={"id"="\d+"},
 *                  "access_control"="(is_granted('ROLE_ADMIN'))",
 *                  "access_control_message"="Vous n'avez pas access à cette Ressource"
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
     * @Groups({"profil:read", "user:write"})
     */
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="profil")
     * @ApiSubresource
     * @Groups({"profil:read", "user:write"})
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
