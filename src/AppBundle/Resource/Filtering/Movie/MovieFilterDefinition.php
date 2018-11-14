<?php

namespace AppBundle\Resource\Filtering\Movie;

use AppBundle\Resource\Filtering\AbstractFilterDefinition;
use AppBundle\Resource\Filtering\FilterDefinitionInterface;
use AppBundle\Resource\Filtering\SortableFilterDefinitionInterface;

class MovieFilterDefinition extends AbstractFilterDefinition implements FilterDefinitionInterface, SortableFilterDefinitionInterface {

    /**
     * @var string|null
     */
    private $title;

    /**
     * @var int|null
     */
    private $yearFrom;

    /**
     * @var int|null
     */
    private $yearTo;

    /**
     * @var int|null
     */
    private $timeFrom;

    /**
     * @var int|null
     */
    private $timeTo;

    /**
     * @var string|null
     */
    private $sortBy;

    /**
     * @var array|null
     */
    private $sortByArray;

    public function __construct(?string $title, ?int $yearFrom, ?int $yearTo, ?int $timeFrom, ?int $timeTo, ?string $sortByQuery, ?array $sortByArray) {

        $this->title = $title;
        $this->yearFrom = $yearFrom;
        $this->yearTo = $yearTo;
        $this->timeFrom = $timeFrom;
        $this->timeTo = $timeTo;
        $this->sortBy = $sortByQuery;
        $this->sortByArray = $sortByArray;
    }

    // Getters
    public function getTimeTo() {
        return $this->timeTo;
    }

    public function getTimeFrom() {
        return $this->timeFrom;
    }

    public function getYearFrom() {
        return $this->yearFrom;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getYearTo() {
        return $this->yearTo;
    }

    // Setters
    public function setTimeTo($timeTo) {
        $this->timeTo = $timeTo;
    }

    public function setTimeFrom($timeFrom) {
        $this->timeFrom = $timeFrom;
    }

    public function setYearFrom($yearFrom) {
        $this->yearFrom = $yearFrom;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function setYearTo($yearTo) {
        $this->yearTo = $yearTo;
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
