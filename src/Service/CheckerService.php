<?php

namespace App\Service;

use DateTime;
use App\Entity\ApiToken;
use Doctrine\Persistence\ManagerRegistry;


class CheckerService 
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
        return(!empty($apiToken));
    }

    public function checkTokenExpiration(string $apiKey): bool
    {
        $apiToken = $this->registry->getRepository(ApiToken::class)->findBy([
            'token' => $apiKey
        ]);
        $expiresAt = $apiToken[0]->getExpiresAt();
        $currentDate = new DateTime(date("Y-m-d H:i:s"));

        return($expiresAt >= $currentDate);
    }

    public function checkDates(string $start, string $end): bool
    {
        if($start!=="''" && $end!=="''")
        {
            $start = strtotime($start);
            $end = strtotime($end);
            return($start < $end);
        }
        return true;
        
    }

    public function check(string $apiKey, string $start='', string $end=''): array
    {
        if(!$this->checkToken($apiKey)){
            return ['message' => 'Wrong Token!'];
        }elseif(!$this->checkTokenExpiration($apiKey)){
            return ['message' => 'Token Expired! Please click "Regenerate Token" button on the homepage'];
        }elseif(!$this->checkDates($start, $end)){
            return ['message' => 'Try to set first date earlier than second'];
        }else {
            return [];
        }
    }

}