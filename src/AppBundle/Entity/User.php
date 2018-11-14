<?php

namespace AppBundle\Entity;

use AppBundle\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @ORM\Table(name="user")
 * @UniqueEntity("username", groups={"Default", "Patch"})
 */
class User implements UserInterface {
    
    const ROLE_USER = "ROLE_USER";
    const ROLE_ADMIN = "ROLE_ADMIN";

    /**
     * @var int
     * 
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     * 
     * @Serializer\Groups({"Default", "Deserialize"})
     */
    private $id;

    /**
     * @var string
     * 
     * @ORM\Column(type="string", unique=true)
     * 
     * @Assert\NotBlank(groups={"Default"})
     * @Serializer\Groups({"Default", "Deserialize"})
     */
    private $username;

    /**
     * @var string
     * 
     * @ORM\Column(type="string", nullable=false)
     * 
     * @Assert\NotBlank(groups={"Default"})
     * @Assert\Regex(
     *      pattern="/(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9]).{7,}/",
     *      message="The Password must be minimun seven characters long and contain at least one digit, one upper case and one low case letter",
     *      groups={"Default", "Patch"}
     * )
     * @Serializer\Groups({"Deserialize"})
     */
    private $password;

    /**
     * @var string
     * 
     * @Assert\NotBlank(groups={"Default"})
     * @Assert\Expression(
     *      "this.getPassword() === this.getRetypedPassword()",
     *      message="Passwords does not match",
     *      groups={"Default", "Patch"}
     * )
     * @Serializer\Type("string")
     * @Serializer\Groups({"Deserialize"})
     */
    private $retypedPassword;

    /**
     * @var array
     * 
     * @ORM\Column(type="simple_array", length=200)
     * 
     * @Serializer\Exclude() // Poner esta propiedad para que sea excluida en los cambios, de este modo no se puede modificar por la peticion Patch
     */
    private $roles;

    
    public function __construct() {

        $this->roles = new ArrayCollection();
    }

    public function eraseCredentials() {
        
    }

    // Getters

    public function getId() {
        return $this->id;
    }

    public function getRetypedPassword(): ?string {
        return $this->retypedPassword;
    }

    public function getPassword(): ?string {

        return $this->password;
    }

    /**
     * 
     * @return Array
     */
    public function getRoles(): Array {

        return $this->roles;
    }

    public function getSalt() {
        
    }
    
    /**
     * 
     * @return string
     */
    public function getUsername(): string {

        return $this->username;
    }

    // Setters

    /**
     * 
     * @param string $username
     * @return void
     */
    public function setUsername(?string $username): void {
        $this->username = $username;
    }

    /**
     * 
     * @param string|null $password
     * @return void
     */
    public function setPassword(?string $password): void {

        $this->password = $password;
    }
    
    /**
     * 
     * @param array $roles
     * @return void
     */
    public function setRoles(array $roles): void {
        $this->roles = $roles;
    }

    /**
     * 
     * @param string $retypedPassword
     * @return void
     */
    public function setRetypedPassword(string $retypedPassword): void {
        $this->retypedPassword = $retypedPassword;
    }

}
