<?php

namespace TripSorter\Strategy;

use TripSorter\Exception\TripSorterException;
use TripSorter\Model\BoardingCardInterface;

interface StrategyInterface
{
    /**
     * @param BoardingCardInterface[] $boardingCards
     */
    public function initialize(array $boardingCards);

    /**
     * @return BoardingCardInterface
     * @throws TripSorterException
     */
    public function findStart();

    /**
     * @param BoardingCardInterface $currentBoardingCard
     * @return BoardingCardInterface
     * @throws TripSorterException
     */
    public function findNext(BoardingCardInterface $currentBoardingCard);
}
