<?php

namespace TripSorter;

use TripSorter\Model\BoardingCardInterface;
use TripSorter\Strategy\StrategyInterface;

class Sorter
{
    protected $strategy;

    public function __construct(StrategyInterface $strategy)
    {
        $this->strategy = $strategy;
    }

    public function handle(array $boardingCards)
    {
        $this->strategy->initialize($boardingCards);

        $collection = [
            $currentBoardingCard = $this->strategy->findStart()
        ];

        do {
            $collection[] = $currentBoardingCard = $this->strategy->findNext($currentBoardingCard);
        } while (!$this->isLastBoardingCard($collection, $boardingCards));

        return $collection;
    }

    protected function isLastBoardingCard(array $sortedBoardingCards, array $boardingCards)
    {
        return count($sortedBoardingCards) === count($boardingCards);
    }
}
