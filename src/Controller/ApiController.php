<?php

namespace App\Controller;

use DateTime;
use App\Entity\Images;
use App\Entity\ApiToken;
use App\Service\CheckTokenService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class ApiController extends AbstractController
{
    private $entityManager;
    private $checkTokenService;

    public function __construct(EntityManagerInterface $entityManager, CheckTokenService $checkTokenService)
    {
        $this->entityManager = $entityManager;
        $this->checkTokenService = $checkTokenService;
    }
    /**
     * @Route("/get/images/api/info", name="api", methods="GET")
     */
    public function index(): Response
    {
        return $this->json([
            'message' => 'Welcome to your new api!',
            'path' => 'src/Controller/IndexController.php',
        ]);
    }

    /**
     * @Route("/get/images/rover/{rover<curiosity|opportunity|spirit>}/camera/{camera<fhaz|rhaz|navcam|chemcam|mahli>}/api_key/{apiKey}/{start?''}/{end?''}", name="list_of_photos", methods="GET")
     */
    public function getImages(string $rover, string $camera, string $apiKey, string $start, string $end): Response
    {
        $em = $this->getDoctrine()->getManager();
        $apiToken =$em->getRepository(ApiToken::class)->findBy([
            'token' => $apiKey
        ]);
        dd($apiToken[0]->getExpiresAt());
        if(empty($apiToken)){
            return $this->json([
                'message' => 'Wrong Token!',
            ]);
        }

        $repository = $this->entityManager->getRepository(Images::class);
        $images = $repository->imagesListQuery($rover, $camera, $start, $end);
        return new JsonResponse($images);
    }

    /**
     * @Route("/get/image/info/api_key/{apiKey}/photo_id/{id?0} ", name="photo_info", methods="GET")
     */
    public function getImageInfo(string $apiKey, int $id): Response
    {
        if(!$this->checkTokenService->checkToken($apiKey)){
            return $this->json([
                'message' => 'Wrong Token!',
            ]);
        }
        $repository = $this->entityManager->getRepository(Images::class);
        $imageInfo = $repository->imageInfoQuery($id);
        return new JsonResponse($imageInfo);
    }
}
