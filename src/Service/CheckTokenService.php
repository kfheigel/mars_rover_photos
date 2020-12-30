<?php

namespace App\Service;

use App\Entity\ApiToken;
use Doctrine\Persistence\ManagerRegistry;


class CheckTokenService 
{
    private $registry;

    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }
    
    public function checkToken(string $apiKey): bool
    {
        $apiToken = $this->registry->getRepository(ApiToken::class)->findBy([
            'token' => $apiKey
        ]);

        if(empty($apiToken)){
            return false;
        }else{
            return true;
        }
    }
}