<?php

namespace AppBundle\Annotation;

use Doctrine\Common\Annotations\Annotation\Target;
use Doctrine\Common\Annotations\Annotation\Required;

/**
 * @Annotation
 * @Target({"PROPERTY"})
 */
final class DeserializeEntity {

    /**
     * @var string
     * @Required()
     */
    public $type;

    /**
     * @var string
     * @Required()
     */
    public $idField;

    /**
     * @var string
     * @Required()
     */
    public $setter;

    /**
     * @var string
     * @Required()
     */
    public $idGetter;

}
