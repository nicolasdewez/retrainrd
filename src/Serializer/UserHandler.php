<?php

namespace App\Serializer;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\Context;
use JMS\Serializer\JsonDeserializationVisitor;

class UserHandler
{
    /** @var EntityManagerInterface */
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function deserializeUserFromJson(JsonDeserializationVisitor $visitor, array $data, array $type, Context $context): User
    {
        return $this->manager->getRepository(User::class)->find($data['id']);
    }
}
