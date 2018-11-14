<?php

namespace AppBundle\Merge;

use Doctrine\Common\Annotations\Reader;
use InvalidArgumentException;
use ReflectionObject;

class EntityMerge{

    /**
     * @var AnnotationReader
     */
    private $reader;

    public function __construct(Reader $reader) {
        
        $this->reader = $reader;
    }
    
    /**
     * 
     * @param type $entity
     * @param type $change
     * @return void
     */
    public function merge($entity, $change): void {

        // Coger el nombre de la class del parametro entity, dara false si la entity no es una class
        $entityClassName = get_class($entity);

        if (false == $entityClassName) {

            throw new InvalidArgumentException("$entity no es una class");
        }

        // Coger el nombre de la class del parametro change false si no es un objeto
        $changeClassName = get_class($change);

        if (false == $changeClassName) {

            throw new InvalidArgumentException("$change no es una class");
        }
        
        // Continuar solo si el objeto change es de la misma class que entity o de alguno de sus hijos (inherenitance / herencia)
        if (!is_a($change, $entityClassName)) {
            
            throw new InvalidArgumentException("No se puede aplicar cambios de un objeto de tipo $changeClassName a uno de $entityClassName");
        }
        
        // 
        $entityReflection = new ReflectionObject($entity);
        $changeReflection = new ReflectionObject($change);
        
        foreach ($changeReflection->getProperties() as $changedProperty){
            
            // Poner las propiedades en modo accesible sino dara error
            $changedProperty->setAccessible(true);
            
            // Guardar el el valor de la propiedad / atributo
            $changedPropertyValue = $changedProperty->getValue($change);
            
            // Ignorar $change propiedades con atributo null
            if (null == $changedPropertyValue) {
                continue;  
            }
            
            // Ignorar las propiedades de $change que no estan en entity
            if (!$entityReflection->hasProperty($changedProperty->getName())) {
                continue; 
            }
            
            $entityPropierty = $entityReflection->getProperty($changedProperty->getName());
            $annotation = $this->reader->getPropertyAnnotation($entityPropierty, Id::class);
            
            // Ignorar $change propiedades que tienen la annotacion de Doctrine @Id
            if (null !== $annotation) {
                continue;
            }
            
            // Poner las porpiedades en modo accessible
            $entityPropierty->setAccessible(true);
            
            // Establecer a la propiedad de la entidad el valor cambiado
            $entityPropierty->setValue($entity, $changedPropertyValue); 
        }
    }

}
