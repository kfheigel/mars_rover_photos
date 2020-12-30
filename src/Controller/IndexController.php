<?php

namespace App\Controller;

use App\Entity\ApiToken;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index($token=''): Response
    {
        return $this->render('index/index.html.twig', [
            'token' => 'Click Regenerate Token button to get your Token',
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

        $em = $this->getDoctrine()->getManager();
        $apiToken = $em->getRepository(ApiToken::class)->findBy([
            'user' => $this->getUser()->getId()
        ]);
        $apiToken[0]->renewExpiresAt();
        $em->persist($apiToken[0]);
        $em->flush(); 

        return $this->redirectToRoute('index', [
            'token' => $apiToken[0]->getToken(),
            ]);
    }
}
