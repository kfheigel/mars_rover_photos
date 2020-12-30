<?php

namespace App\Command;

use App\Entity\Images;
use App\Entity\Holidays;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RefreshImagesCommand extends Command
{
    protected static $defaultName = "app:refresh-images";

    private $entityManager;
    private $nasaApiKey;
    private $registry;

    public function __construct(string $nasaApiKey, EntityManagerInterface $entityManager, ManagerRegistry $registry)
    {
        $this->entityManager = $entityManager;
        $this->nasaApiKey = $nasaApiKey;
        $this->registry = $registry;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
        ->setDescription("Deletes all entries in Holidays table, gets new entries based on year that user inputs");
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $deleteEntries = $this->entityManager->createQuery(
            'DELETE FROM App\Entity\Images')->execute();

        $holidays = $this->registry->getRepository(Holidays::class)->findAll();
            
        $httpClient = HttpClient::create();

        foreach($holidays as $holiday){
            
            $response = $httpClient->request('GET', 'https://api.nasa.gov/mars-photos/api/v1/rovers/curiosity/photos?earth_date=' . $holiday->getDate() . '&api_key=' . $this->nasaApiKey);
            if (200 !== $response->getStatusCode()) {
            } else { 
                $photosData = $response->toArray()['photos'];

                for($i=0; $i<count($photosData); $i++)
                {
                    $images = new Images();
                    $images->setPhotoId($photosData[$i]['id']);
                    $images->setImageUrl($photosData[$i]['img_src']);
                    $images->setImageEarthDate($photosData[$i]['earth_date']);
                    $images->setRover(strtolower($photosData[$i]['rover']['name']));
                    $images->setCamera(strtolower($photosData[$i]['camera']['name']));
                    $this->entityManager->persist($images);
                    $this->entityManager->flush();
                }
            }
        }

        return 0;
    }
}