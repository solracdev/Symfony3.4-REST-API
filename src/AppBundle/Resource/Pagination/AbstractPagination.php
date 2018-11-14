<?php

namespace AppBundle\Resource\Pagination;

use AppBundle\Resource\Filtering\FilterDefinitionInterface;
use Doctrine\ORM\UnexpectedResultException;
use Hateoas\Representation\CollectionRepresentation;
use Hateoas\Representation\PaginatedRepresentation;

abstract class AbstractPagination implements PaginationInterface {

    public function paginate(Page $page, FilterDefinitionInterface $filter): PaginatedRepresentation {

        $resources = $this->getResourceFilter()->getResources($filter)
                ->setFirstResult($page->getOffset())
                ->setMaxResults($page->getLimit())
                ->getQuery()
                ->getResult();

        $resourceCount = $pages = null;

        try {

            $resourceCount = $this->getResourceFilter()->getResourcesCount($filter)
                    ->getQuery()
                    ->getSingleScalarResult();

            $pages = (int) ceil($resourceCount / $page->getLimit());
        } catch (UnexpectedResultException $ex) {
            
        }

        return new PaginatedRepresentation(
                new CollectionRepresentation($resources),
                $this->getRouteName(),
                $filter->getQueryParameters(),
                $page->getPage(),
                $page->getLimit(),
                $pages,
                null,
                null,
                false,
                $resourceCount
        );
    }

}
