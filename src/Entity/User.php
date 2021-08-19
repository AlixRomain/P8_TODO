<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 * @UniqueEntity("email")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\Length(
     *      min = 3,
     *      max = 180,
     *      minMessage = "Votre adresse email doit contenir au moins {{ limit }} caractères de long",
     *      maxMessage = "Votre adresse email ne peut pas contenir plus que {{ limit }} caractères"
     * )
     * @Assert\NotBlank(message="Vous devez saisir une adresse email.")
     * @Assert\Email(message="Le format de l'adresse n'est pas correcte.")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=25, unique=true)
     * @Assert\Length(
     *      min = 3,
     *      max = 25,
     *      minMessage = "Votre nom d'utilisateur doit contenir au moins {{ limit }} caractères de long",
     *      maxMessage = "Votre nom d'utilisateur ne peut pas contenir plus que {{ limit }} caractères"
     * )
     * @Assert\NotBlank(message="Vous devez saisir un nom d'utilisateur.")
     */
    private $name;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="Vous devez saisir un mot de passe.")
     * @Assert\Length(
     *      min = 8,
     *      max = 255,
     *      minMessage = "Votre mot de passe doit contenir au moins {{ limit }} caractères.",
     *      maxMessage = "Votre mot de passe ne peut pas contenir plus que {{ limit }} caractères !"
     * )
     * @Assert\Regex(
     *     pattern = "^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W)^",
     *     message = "Le mot de passe doit contenir au moins une minuscule, une majuscule, un chiffre et un caractère spécial !"
     * )
     * @Assert\NotCompromisedPassword
     * @see https://symfony.com/blog/new-in-symfony-4-3-compromised-password-validator
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Task", mappedBy="user", orphanRemoval=true)
     */
    private $tasks;

    /**
     * @ORM\OneToMany(targetEntity=Task::class, mappedBy="targetUser", orphanRemoval=true)
     */
    private $targetTask;


    public function __construct()
    {
        $this->tasks = new ArrayCollection();
        $this->targetTask = new ArrayCollection();
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getName(): string
    {
        return (string) $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;

        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {

    }

    /**
     * @return Collection|Task[]
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function addTask(Task $task): self
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks[] = $task;
            $task->setUser($this);
        }

        return $this;
    }

    public function removeTask(Task $task): self
    {
        if ($this->tasks->contains($task)) {
            $this->tasks->removeElement($task);
            // set the owning side to null (unless already changed)
            if ($task->getUser() === $this) {
                $task->setUser(null);
            }
        }

        return $this;
    }

    public static function getAuthorizedRoles()
    {
        return [['ROLE_USER'], ['ROLE_ADMIN']];
    }

    /**
     * @return Collection|Task[]
     */
    public function getTargetTask(): Collection
    {
        return $this->targetTask;
    }

    public function addTargetTask(Task $targetTask): self
    {
        if (!$this->targetTask->contains($targetTask)) {
            $this->targetTask[] = $targetTask;
            $targetTask->setTargetUser($this);
        }

        return $this;
    }

    public function removeTargetTask(Task $targetTask): self
    {
        if ($this->targetTask->removeElement($targetTask)) {

            if ($targetTask->getTargetUser() === $this) {
                $targetTask->setTargetUser(null);
            }
        }

        return $this;
    }

}
