<?php

namespace App\Manager;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Security\Role;
use App\Workflow\RegistrationDefinitionWorkflow;
use Doctrine\ORM\EntityManagerInterface;
use League\OAuth2\Client\Provider\FacebookUser;
use League\OAuth2\Client\Provider\GoogleUser;

class UserManager
{
    /** @var EntityManagerInterface */
    private $manager;

    /** @var UserRepository */
    private $userRepository;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
        $this->userRepository = $this->manager->getRepository(User::class);
    }

    public function findUser(int $id): ?User
    {
        return $this->userRepository->find($id);
    }

    public function getOrCreateUserFromGoogle(GoogleUser $googleUser): User
    {
        return $this->getOrCreateUser(
            $googleUser->getEmail(),
            $googleUser->getFirstName(),
            $googleUser->getLastName()
        );
    }

    public function getOrCreateUserFromFacebook(FacebookUser $facebookUser): User
    {
        return $this->getOrCreateUser(
            $facebookUser->getEmail(),
            $facebookUser->getFirstName(),
            $facebookUser->getLastName()
        );
    }

    private function getOrCreateUser(string $email, string $firstName, string $lastName): User
    {
        $user = $this->userRepository->findOneBy(['email' => $email]);
        if (null !== $user) {
            return $user;
        }

        $user = new User();
        $user->setEmail($email);
        $user->setRoles([Role::USER]);
        $user->setPassword('');
        $user->setFirstName($firstName);
        $user->setLastName($lastName);
        $user->setRegistrationCode('');
        $user->setRegistrationState(RegistrationDefinitionWorkflow::PLACE_ACTIVATED);
        $user->setEnabled(true);

        $this->manager->persist($user);
        $this->manager->flush();

        return $user;
    }
}
