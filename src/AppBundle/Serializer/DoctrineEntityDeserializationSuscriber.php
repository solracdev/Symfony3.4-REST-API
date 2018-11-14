<?php

namespace AppBundle\Serializer;

use AppBundle\Annotation\DeserializeEntity;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\Persistence\ManagerRegistry;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use JMS\Serializer\EventDispatcher\PreDeserializeEvent;
use LogicException;
use ReflectionClass;
use ReflectionObject;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Workflow\Registry;

class DoctrineEntityDeserializationSuscriber implements EventSubscriberInterface {

    /**
     * @var Registry
     */
    private $doctrineRegistry;

    /**
     * @var AnnotationReader
     */
    private $annotationsReader;

    public function __construct(Reader $annotationsReader, ManagerRegistry $doctrineRegistry) {

        $this->annotationsReader = $annotationsReader;
        $this->doctrineRegistry = $doctrineRegistry;
    }

    public static function getSubscribedEvents(): array {

        return [
            [
                "event" => "serializer.pre_deserialize",
                "method" => "onPreDeserialize",
                "format" => "json"
            ],
            [
                "event" => "serializer.post_deserialize",
                "method" => "onPostDeserialize",
                "format" => "json"
            ]
        ];
    }

    public function onPreDeserialize(PreDeserializeEvent $event) {

        $classType = $event->getType()["name"];

        if (!class_exists($classType)) {

            return;
        }

        $data = $event->getData();
        $class = new ReflectionClass($classType);

        foreach ($class->getProperties() as $property) {

            if (!isset($data[$property->getName()])) {

                continue;
            }

            /** @var DeserializeEntity $annotation * */
            $annotation = $this->annotationsReader->getPropertyAnnotation($property, DeserializeEntity::class);

            if (null == $annotation || !class_exists($annotation->type)) {

                continue;
            }

            $data[$property->getName()] = [$annotation->idField => $data[$property->getName()]];
        }

        $event->setData($data);
    }

    public function onPostDeserialize(ObjectEvent $event) {

        $classType = $event->getType()["name"];

        if (!class_exists($classType)) {

            return;
        }

        $object = $event->getObject();

        $reflection = new ReflectionObject($object);

        foreach ($reflection->getProperties() as $property) {

            /** @var DeserializeEntity $annotation * */
            $annotation = $this->annotationsReader->getPropertyAnnotation($property, DeserializeEntity::class);

            if (null == $annotation || !class_exists($annotation->type)) {

                continue;
            }

            if (!$reflection->hasMethod($annotation->setter)) {

                throw new LogicException("Object {$reflection->getName()} does not have the {$annotation->setter} Method");
            }
            
            $property->setAccessible(true);
            $deserializedEntity = $property->getValue($object);
            
            if (null == $deserializedEntity) {
                
                return;
            }
            
            // Curly Braces Notation GETTER
            // esto ->{}() es la manera de llamar a los metodos con annotaciones de esta manera el codigo de abajo es esto
            // $foo->metodo();
            $entityId = $deserializedEntity->{$annotation->idGetter}(); 
            
            $repository = $this->doctrineRegistry->getRepository($annotation->type);
            
            $entity = $repository->find($entityId);

            if (null == $entity) {
                
                throw new NotFoundHttpException("Resource {$reflection->getShortName()}/$entityId ");
                
            }
            // Curly Braces Notation SETTER
            // esto ->{}($foo) es la manera de llamar a un setter con anotaciones, el codigo de abajo seria esto
            // $foo->setName('name')
            $object->{$annotation->setter}($entity);
        }
    }

}
