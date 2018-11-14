<?php

namespace AppBundle\Resource\Filtering\Person;

use AppBundle\Resource\Filtering\AbstractFilterDefinition;
use AppBundle\Resource\Filtering\FilterDefinitionInterface;
use AppBundle\Resource\Filtering\SortableFilterDefinitionInterface;

class PersonFilterDefinition extends AbstractFilterDefinition implements FilterDefinitionInterface, SortableFilterDefinitionInterface {

    /**
     * @var string|null
     */
    private $sortByArray;

    /**
     * @var string|null
     */
    private $sortBy;

    /**
     * @var string|null
     */
    private $birthTo;

    /**
     * @var string|null
     */
    private $birthFrom;

    /**
     * @var string|null
     */
    private $lastName;

    /**
     * @var string|null
     */
    private $firstName;

    public function __construct(?string $firstName, ?string $lastName, ?string $birthFrom, ?string $birthTo, ?string $sortByQuery, ?string $sortByArray) {

        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->birthFrom = $birthFrom;
        $this->birthTo = $birthTo;
        $this->sortBy = $sortByQuery;
        $this->sortByArray = $sortByArray;
    }

    public function getSortBy() {
        return $this->sortBy;
    }

    public function getBirthTo() {
        return $this->birthTo;
    }

    public function getBirthFrom() {
        return $this->birthFrom;
    }

    public function getLastName() {
        return $this->lastName;
    }

    public function getFirstName() {
        return $this->firstName;
    }

    public function getParameters(): array {

        return get_object_vars($this);
    }

    public function getSortByArray(): ?array {

        return $this->sortByArray;
    }

    public function getSortbyQuery(): ?string {

        return $this->sortBy;
    }

}
