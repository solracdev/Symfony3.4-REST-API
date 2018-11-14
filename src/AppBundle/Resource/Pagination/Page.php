<?php

namespace AppBundle\Resource\Pagination;

class Page {

    /**
     * @var int
     */
    private $limit;

    /**
     * @var int
     */
    private $page;

    /**
     *
     * @var int
     */
    private $offset;

    /**
     * 
     * @param int $page
     * @param int $limit
     */
    public function __construct(int $page, int $limit) {

        $this->page = $page;
        $this->limit = $limit;
        $this->offset = ($page - 1) * $limit;
    }
    
    /**
     * Get Limit
     * 
     * @return type
     */
    public function getLimit() {
        return $this->limit;
    }

    /**
     * Get Page
     * 
     * @return type
     */
    public function getPage() {
        return $this->page;
    }

    /**
     * Get Offset
     * 
     * @return type
     */
    public function getOffset() {
        return $this->offset;
    }

}
