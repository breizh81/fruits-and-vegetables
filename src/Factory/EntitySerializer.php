<?php

declare(strict_types=1);

namespace App\Factory;

class EntitySerializer
{
    public function toArray(object $entity): array
    {
        $reflectionClass = new \ReflectionClass($entity);
        $properties = $reflectionClass->getProperties(\ReflectionProperty::IS_PRIVATE);

        $data = [];
        foreach ($properties as $property) {
            $property->setAccessible(true);
            $value = $property->getValue($entity);

            if ($value instanceof \BackedEnum) {
                $data[$property->getName()] = $value->value;
            } else {
                $data[$property->getName()] = $value;
            }
        }

        return $data;
    }
}
