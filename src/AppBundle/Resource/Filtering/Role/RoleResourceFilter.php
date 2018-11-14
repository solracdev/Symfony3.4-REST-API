<?php

namespace AppBundle\Resource\Filtering\Role;

use AppBundle\Repository\RoleRepository;
use AppBundle\Resource\Filtering\ResourceFilterInterface;
use Doctrine\ORM\QueryBuilder;

class RoleResourceFilter implements ResourceFilterInterface {

    /**
     * @var RoleRepository
     */
    private $roleRepository;

    /**
     * 
     * @param RoleRepository $roleRepository
     */
    public function __construct(RoleRepository $roleRepository) {

        $this->roleRepository = $roleRepository;
    }

    /**
     * 
     * @param RoleFilterDefinition $filter
     * @return QueryBuilder
     */
    public function getResources($filter): QueryBuilder {

        $qb = $this->getQuery($filter);
        $qb->select("role");

        return $qb;
    }

    /**
     * 
     * @param RoleFilterDefinition $filter
     * @return QueryBuilder
     */
    public function getResourcesCount($filter): QueryBuilder {

        $qb = $this->getQuery($filter);
        $qb->select("count(role)");

        return $qb;
    }

    /**
     * 
     * @param RoleFilterDefinition $filter
     * @return QueryBuilder
     */
    private function getQuery(RoleFilterDefinition $filter): QueryBuilder {

        $qb = $this->roleRepository->createQueryBuilder("role");

        if (null != $filter->getPlayedName()) {

            $qb->where($qb->expr()->like("role.playedName", ":playedName"));
            $qb->setParameter("playedName", "%{$filter->getPlayedName()}%");
        }

        if (null != $filter->getMovie()) {

            $qb->andWhere($qb->expr()->eq("role.movie", ":movieId"));
            $qb->setParameter("movieId", $filter->getMovie());
        }


        if (null !== $filter->getSortByArray()) {

            foreach ($filter->getSortByArray() as $by => $order) {

                $sort = ("desc" == $order) ? $qb->expr()->desc("role.$by") : $qb->expr()->asc("role.$by");

                $qb->addOrderBy($sort);
            }
        }
        
        return $qb;
    }

}
