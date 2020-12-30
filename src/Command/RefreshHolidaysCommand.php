<?php

namespace App\Command;

use HolidayAPI\Client;
use App\Entity\Holidays;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RefreshHolidaysCommand extends Command
{
    protected static $defaultName = "app:refresh-holidays";

    private $entityManager;
    private $holidayApiKey;

    public function __construct(string $holidayApiKey, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->holidayApiKey = $holidayApiKey;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
        ->setDescription("Deletes all entries in Holidays table, gets new entries based on year that user inputs")
        ->addArgument('year', InputArgument::REQUIRED, 'Enter last year (Previous years, and current year is not available due to free membership in holidayapi');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $deleteEntries = $this->entityManager->createQuery(
            'DELETE FROM App\Entity\Holidays')->execute();
        
        $holiday_api = new \HolidayAPI\Client(['key' => $this->holidayApiKey]);
        $holidays = $holiday_api->holidays([
        'country' => 'PL',
        'year' => $input->getArgument('year'),
        ]);
        
        foreach($holidays['holidays'] as $entry){
            $holiday = new Holidays();
            $holiday->setName(strtolower($entry['name']));
            $date = strtotime($entry['date']); 
            
            $holiday->setDate(date('Y-m-d', $date));
            $holiday->setPublic($entry['public']);
            $this->entityManager->persist($holiday);
            $this->entityManager->flush();
        }
        return 0;
    }
}