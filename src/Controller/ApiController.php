<?php

namespace App\Controller;

use DateTime;
use App\Entity\Images;
use App\Entity\ApiToken;
use App\Service\CheckerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class ApiController extends AbstractController
{
    private $entityManager;
    private $checkerService;

    public function __construct(EntityManagerInterface $entityManager, CheckerService $checkerService)
    {
        $this->entityManager = $entityManager;
        $this->checkerService = $checkerService;
    }
    /**
     * @Route("/get/images/api/info", name="api", methods="GET")
     */
    public function index(): Response
    {
        $json = file_get_contents('../json/api_info.json');
        return new JsonResponse(json_decode($json));
    }

    /**
     * @Route("/get/images/api_key/{apiKey}/rover/{rover}/camera/{camera}/{start?''}/{end?''}", 
     * name="list_of_photos", 
     * methods="GET",
     * requirements={
     *      "rover" = "curiosity|opportunity|spirit",
     *      "camera" = "fhaz|rhaz|navcam|chemcam|mahli",
     *  })
     */
    public function getImages(string $apiKey, string $rover, string $camera, string $start, string $end): Response
    {
        $check = $this->checkerService->check($apiKey, $start, $end);
        if(!empty($check)){
            return $this->json($check);
        }

        $repository = $this->entityManager->getRepository(Images::class);
        $images = $repository->imagesListQuery($rover, $camera, $start, $end);
        return new JsonResponse($images);
    }

    /**
     * @Route("/get/image/api_key/{apiKey}/photo_id/{id?0}", 
     * name="photo_info", 
     * methods="GET",
     * requirements={"id"="\d+"})
     */
    public function getImageInfo(string $apiKey, int $id): Response
    {
        $check = $this->checkerService->check($apiKey);
        if(!empty($check)){
            return $this->json($check);
        }
        
        $repository = $this->entityManager->getRepository(Images::class);
        $imageInfo = $repository->imageInfoQuery($id);
        return new JsonResponse($imageInfo);
    }
}
