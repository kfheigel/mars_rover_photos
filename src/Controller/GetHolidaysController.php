<?php

namespace App\Controller;

use App\Entity\Holidays;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GetHolidaysController extends AbstractController
{
    /**
     * @Route("/get/holidays", name="get_holidays")
     */
    public function index(): Response
    {
        $holidays = $this->getDoctrine()->getRepository(Holidays::class)->findAll();

        return new JsonResponse($holidays);
    }
}
