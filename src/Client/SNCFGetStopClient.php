<?php

namespace App\Client;

use App\Model\SNCF\StopArea;
use App\Model\SNCF\StopAreasResponse;
use App\Serializer\Format;
use GuzzleHttp\Client;
use JMS\Serializer\SerializerInterface;

class SNCFGetStopClient
{
    const URI_LIST = 'coverage/sncf/stop_areas?count=1000&start_page=%d';

    /** @var Client */
    private $client;

    /** @var SerializerInterface */
    private $serializer;

    public function __construct(Client $clientSNCF, SerializerInterface $serializer)
    {
        $this->client = $clientSNCF;
        $this->serializer = $serializer;
    }

    /**
     * @return StopArea[]
     */
    public function getStops(): array
    {
        $stopAreas = [];
        $page = 0;

        do {
            $uri = sprintf(self::URI_LIST, $page);
            $response = $this->client->get($uri);

            /** @var StopAreasResponse $stopAreasResponse */
            $stopAreasResponse = $this->serializer->deserialize(
                (string) $response->getBody(),
                StopAreasResponse::class,
                Format::JSON
            );

            $stopAreas = array_merge($stopAreas, $stopAreasResponse->getStopAreas());
            $linkNext = $stopAreasResponse->getLinkNext();

            ++$page;
        } while (null !== $linkNext);

        return $stopAreas;
    }
}
