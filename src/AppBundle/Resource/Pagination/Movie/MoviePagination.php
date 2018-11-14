<?php

namespace AppBundle\Resource\Pagination\Movie;

use AppBundle\Resource\Filtering\Movie\MovieResourceFilter;
use AppBundle\Resource\Filtering\ResourceFilterInterface;
use AppBundle\Resource\Pagination\AbstractPagination;
use AppBundle\Resource\Pagination\PaginationInterface;

class MoviePagination extends AbstractPagination implements PaginationInterface {
    
    private const ROUTE = "get_movies";

    /**
     * @var MovieResourceFilter
     */
    private $movieResource;

    public function __construct(MovieResourceFilter $movieResource) {

        $this->movieResource = $movieResource;
    }

    public function getResourceFilter(): ResourceFilterInterface {
        
        return $this->movieResource;
        
    }

    public function getRouteName(): string {
        
        return self::ROUTE;
        
    }

}
