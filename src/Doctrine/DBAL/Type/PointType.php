<?php

namespace App\Doctrine\DBAL\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class PointType extends Type
{
    const POINT = 'point';

    public function getSqlDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        return 'POINT';
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): Point
    {
        $coordinates = [];
        preg_match('#\((?P<latitude>-?[0-9.]+),(?P<longitude>-?[0-9.]+)\)#', $value, $coordinates);

        $latitude = $coordinates['latitude'] ?? 0;
        $longitude = $coordinates['longitude'] ?? 0;

        return new Point((float) $latitude, (float) $longitude);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        if (!$value instanceof Point) {
            throw new \RuntimeException('Value of point type must be an "point" object');
        }

        return sprintf('%s,%s', $value->getLatitude(), $value->getLongitude());
    }

    public function getName(): string
    {
        return self::POINT;
    }
}
