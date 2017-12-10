<?php

namespace App\Model\SNCF;

use JMS\Serializer\Annotation as Serializer;

class StopAreasResponse
{
    /**
     * @var StopArea[]
     *
     * @Serializer\Type("array<App\Model\SNCF\StopArea>")
     */
    private $stopAreas;

    /**
     * @var Link[]
     *
     * @Serializer\Type("array<App\Model\SNCF\Link>")
     */
    private $links;

    /**
     * @return StopArea[]
     */
    public function getStopAreas(): array
    {
        return $this->stopAreas;
    }

    /**
     * @param StopArea[] $stopAreas
     */
    public function setStopAreas(array $stopAreas): void
    {
        $this->stopAreas = $stopAreas;
    }

    /**
     * @return Link[]
     */
    public function getLinks(): array
    {
        return $this->links;
    }

    /**
     * @param Link[] $links
     */
    public function setLinks(array $links): void
    {
        $this->links = $links;
    }

    public function getLinkNext(): ?Link
    {
        foreach ($this->links as $link) {
            if ($link->isNext()) {
                return $link;
            }
        }

        return null;
    }
}
