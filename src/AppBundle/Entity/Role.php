<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serialize;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Annotation as App;
use Hateoas\Configuration\Annotation as Hateoas;

/**
 * Role
 *
 * @ORM\Table(name="role")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RoleRepository")
 * @Serialize\ExclusionPolicy("ALL")
 * @Hateoas\Relation(
 *         "person",
 *          href=@Hateoas\Route("get_human", parameters={"person" = "expr(object.getPerson().getId())"})
 * )
 */
class Role {

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
     * @var Person
     * 
     * @ORM\ManyToOne(targetEntity="Person")
     * @App\DeserializeEntity(type="AppBundle\Entity\Person", idField="id", idGetter="getId", setter="setPerson")
     * 
     * @Serialize\Groups({"Deserialize"})
     * @Serialize\Expose()
     */
    private $person;

    /**
     * @var string
     * 
     * @ORM\Column(name="played_name", type="string", length=100)
     * 
     * @Serialize\Groups({"Default", "Deserialize"})
     * @Serialize\Expose()
     */
    private $playedName;

    /**
     * @var Movie
     * 
     * @ORM\ManyToOne(targetEntity="Movie", inversedBy="roles")
     */
    private $movie;

    // GETTERS
    public function getId() {
        return $this->id;
    }

    public function getPerson(): Person {
        return $this->person;
    }

    public function getPlayedName() {
        return $this->playedName;
    }

    public function getMovie(): Movie {
        return $this->movie;
    }

    // SETTERS
    public function setPerson(Person $person) {
        $this->person = $person;
    }

    public function setPlayedName($playedName) {
        $this->playedName = $playedName;
    }

    public function setMovie(Movie $movie) {
        $this->movie = $movie;
    }

}
