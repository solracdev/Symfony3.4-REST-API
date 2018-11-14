<?php

namespace AppBundle\Resource\Pagination\Person;

use AppBundle\Resource\Filtering\Person\PersonResourceFilter;
use AppBundle\Resource\Filtering\ResourceFilterInterface;
use AppBundle\Resource\Pagination\AbstractPagination;
use AppBundle\Resource\Pagination\PaginationInterface;

class PersonPagination extends AbstractPagination implements PaginationInterface {

     private const ROUTE = "get_humans";
    
    /**
     * @var PersonResourceFilter
     */
    private $personResurceFilter;
    
    public function __construct(PersonResourceFilter $personResurceFilter) {
        
        $this->personResurceFilter = $personResurceFilter;
    }


    public function getResourceFilter(): ResourceFilterInterface {
        
        return $this->personResurceFilter;
    }

    public function getRouteName(): string {
        
        return self::ROUTE;
    }

}
