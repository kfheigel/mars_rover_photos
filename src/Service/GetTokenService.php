<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\ApiToken;
use Doctrine\Persistence\ManagerRegistry;


class GetTokenService 
{
    private $registry;

    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }
    
    public function getToken(User $user): string
    {
        $apiToken = $this->registry->getRepository(ApiToken::class)->findBy([
            'user' => $user
        ]);
        return($apiToken[0]->getToken());
    }

    public function getExpiresAt(User $user): \DateTime
    {

        $apiToken = $this->registry->getRepository(ApiToken::class)->findBy([
            'user' => $user
        ]);
        return($apiToken[0]->getExpiresAt());
    }

    public function getTokenRenew(User $user): void
    {
        $entityManager = $this->registry->getManagerForClass(ApiToken::class);

        $apiToken = $this->registry->getRepository(ApiToken::class)->findBy([
            'user' => $user
        ]);
        $apiToken[0]->renewExpiresAt();
        $entityManager->persist($apiToken[0]);
        $entityManager->flush();

    }
}
