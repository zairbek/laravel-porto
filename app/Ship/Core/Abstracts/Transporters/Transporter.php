<?php

namespace App\Ship\Core\Abstracts\Transporters;

use ReflectionClass;
use ReflectionProperty;
use Spatie\DataTransferObject\DataTransferObject;

abstract class Transporter extends DataTransferObject
{
    public static function convert(array $data): DataTransferObject
    {
        $reflectionDto = new ReflectionClass(static::class);
        $transformedProperties = [];

        foreach ($reflectionDto->getProperties() as $property) {
            if ($property->isPublic()) {
                $name = $property->getName();
                $type = $property->getType()->getName();
                settype($data[$name], $type);

                $transformedProperties[$name] = self::dataOrNull($property, $data[$name]);
            }
        }

        return new static($transformedProperties);
    }

    /**
     * @param ReflectionProperty $property
     * @param mixed $data
     * @return mixed|null
     */
    private static function dataOrNull(ReflectionProperty $property, $data)
    {
        if ($property->getType()->allowsNull() && empty($data)) {
            return null;
        }

        return $data;
    }
}
