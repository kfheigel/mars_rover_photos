<?php

namespace App\Controller;

use App\Entity\ApiToken;
use App\Service\GetTokenService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IndexController extends AbstractController
{
    private $getTokenService;

    public function __construct(GetTokenService $getTokenService)
    {
        $this->getTokenService = $getTokenService;
    }

    /**
     * @Route("/", name="index")
     */
    public function index($token='',$expiresAt=''): Response
    {
        if ($this->getUser()) {
            $token = $this->getTokenService->getToken($this->getUser());
            $expiresAt = $this->getTokenService->getExpiresAt($this->getUser())->format('Y-m-d H:i:s');
        }
        return $this->render('index/index.html.twig', [
            'token' => $token,
            'expiresAt' => $expiresAt,
        ]);
    }

    /**
     * @Route("/regenerate_token", name="regenerate_token")
     */
    public function regenerate(): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('index');
        }
        $this->getTokenService->getTokenRenew($this->getUser());

        return $this->redirectToRoute('index', [
            ]);
    }
}
