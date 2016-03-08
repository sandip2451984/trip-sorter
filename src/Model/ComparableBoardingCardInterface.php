<?php

namespace TripSorter\Model;

interface ComparableBoardingCardInterface
{
    /**
     * @param BoardingCardInterface $boardingCard
     * @return mixed
     */
    public function isEqualWith(BoardingCardInterface $boardingCard);
}
