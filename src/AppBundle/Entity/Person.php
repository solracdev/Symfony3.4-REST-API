<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serialize;

/**
 * Person
 *
 * @ORM\Table(name="person")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PersonRepository")
 * @Serialize\ExclusionPolicy("ALL")
 */
class Person {

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * 
     * @Serialize\Groups({"Default", "Deserialize"})
     * @Serialize\Expose()
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="firstName", type="string", length=70)
     * 
     * @Assert\Length(min=1, max=255)
     * @Assert\NotBlank()
     * @Serialize\Groups({"Default", "Deserialize"})
     * @Serialize\Expose()
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="lastName", type="string", length=100)
     * 
     * @Assert\Length(min=1, max=100)
     * @Assert\NotBlank()
     * @Serialize\Groups({"Default", "Deserialize"})
     * @Serialize\Expose()
     */
    private $lastName;
    
    /**
     * @var string
     * 
     * @ORM\Column(name="biography", type="text", nullable=true)
     * 
     * @Assert\Length(min=10, max=5000)
     * @Serialize\Groups({"Default", "Deserialize"})
     * @Serialize\Expose()
     * @Serialize\Since("1.1")
     */
    private $biography;
    

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateOfBirth", type="date")
     * 
     * @Serialize\Type("DateTime<'Y-m-d'>")
     * @Serialize\Groups({"Default", "Deserialize"})
     * @Serialize\Expose()
     * @Assert\NotBlank()
     * @Assert\Date()
     */
    private $dateOfBirth;

    /**
     * Get id
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return Person
     */
    public function setFirstName($firstName) {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName() {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return Person
     */
    public function setLastName($lastName) {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName() {
        return $this->lastName;
    }

    /**
     * Set dateOfBirth
     *
     * @param \DateTime $dateOfBirth
     *
     * @return Person
     */
    public function setDateOfBirth($dateOfBirth) {
        $this->dateOfBirth = $dateOfBirth;

        return $this;
    }

    /**
     * Get dateOfBirth
     *
     * @return \DateTime
     */
    public function getDateOfBirth() {
        return $this->dateOfBirth;
    }
    
    public function getBiography() {
        return $this->biography;
    }

    public function setBiography($biography) {
        $this->biography = $biography;
    }



}
