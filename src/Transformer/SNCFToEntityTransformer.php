<?php

namespace App\Transformer;

use App\Doctrine\DBAL\Type\Point;
use App\Entity\Stop;
use App\Model\SNCF\StopArea;

class SNCFToEntityTransformer
{
    public function execute(StopArea $stopArea, ?Stop $stop): Stop
    {
        if (null === $stop) {
            $stop = new Stop();
        }

        $stop->setCode($stopArea->getId());
        $stop->setName($stopArea->getName());
        $stop->setLabel($stopArea->getLabel());
        $stop->setCoordinates(
            new Point($stopArea->getCoord()['lat'], $stopArea->getCoord()['lon'])
        );

        return $stop;
    }
}
