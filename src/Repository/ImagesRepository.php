<?php

namespace App\Repository;

use App\Entity\Images;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Images|null find($id, $lockMode = null, $lockVersion = null)
 * @method Images|null findOneBy(array $criteria, array $orderBy = null)
 * @method Images[]    findAll()
 * @method Images[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImagesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Images::class);
    }

    public function imagesListQuery(string $rover, string $camera, string $start, string $end): array
    {
        $queryString='SELECT i FROM App\Entity\Images i WHERE i.rover = :rover AND i.camera = :camera ';
        $queryArray=[
            'rover' => $rover,
            'camera' => $camera,
        ];

        if($start==="''"){
            $queryString = $queryString;
            $queryArray = $queryArray;
        }
        elseif($start!=="''" && $end==="''"){
            $queryString =  $queryString . 'AND i.imageEarthDate = :start';
            $queryArray['start'] = $start;
        }elseif($start!=='' && $end!=="''"){
            $queryString =  $queryString . 'AND i.imageEarthDate BETWEEN :start AND :end';
            $queryArray['start'] = $start;
            $queryArray['end'] = $end;
        }

        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery($queryString);
        $query->setParameters($queryArray);

        return $query->getResult();
    }

    public function imageInfoQuery(int $id): array
    {

        $queryString='SELECT i FROM App\Entity\Images i ';
        $queryArray=[];

        if($id!==0){
            $queryString =  $queryString . 'WHERE i.photoId = :id';
            $queryArray['id'] = $id;
        }
        
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery($queryString);
        $query->setParameters($queryArray);

        return $query->getResult();
    }
}
