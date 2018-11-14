<?php

namespace AppBundle\Resource\Filtering;

interface SortableFilterDefinitionInterface {
    
    public function getSortbyQuery(): ?string;
    public function getSortByArray(): ?array;
    
    
}
