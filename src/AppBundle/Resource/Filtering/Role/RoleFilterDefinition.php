<?php

namespace AppBundle\Resource\Filtering\Role;

use AppBundle\Resource\Filtering\AbstractFilterDefinition;
use AppBundle\Resource\Filtering\FilterDefinitionInterface;
use AppBundle\Resource\Filtering\SortableFilterDefinitionInterface;

class RoleFilterDefinition extends AbstractFilterDefinition implements FilterDefinitionInterface, SortableFilterDefinitionInterface {

    /**
     * @var string|null
     */
    private $playedName;

    /**
     * @var int|null
     */
    private $movie;

    /**
     * @var string|null
     */
    private $sortBy;

    /**
     * @var array|null
     */
    private $sortByArray;

    public function __construct(?string $playedName, ?int $movie, ?string $sortBy, ?array $sortByArray) {

        $this->playedName = $playedName;
        $this->movie = $movie;
        $this->sortBy = $sortBy;
        $this->sortByArray = $sortByArray;
    }

    public function getPlayedName() {
        return $this->playedName;
    }

    public function getMovie() {
        return $this->movie;
    }

    public function getSortByQuery(): ?string {
        return $this->sortBy;
    }

    public function getSortByArray(): ?array {
        return $this->sortByArray;
    }

    public function getParameters(): array {
        
        return get_object_vars($this);
    }

}
