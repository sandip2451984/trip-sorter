<?php

namespace TripSorter\Model;

use TripSorter\Model\TransportationInfo\TransportationInfoInterface;

class BoardingCard implements BoardingCardInterface, ComparableBoardingCardInterface
{
    protected $start;
    protected $end;
    protected $info;

    public function __construct(LocationInterface $start, LocationInterface $end, TransportationInfoInterface $info)
    {
        $this->start = $start;
        $this->end = $end;
        $this->info = $info;
    }

    /**
     * @inheritdoc
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @inheritdoc
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * @inheritdoc
     */
    public function getTransportationInfo()
    {
        return $this->info;
    }

    /**
     * @inheritdoc
     */
    public function isEqualWith(BoardingCardInterface $boardingCard)
    {
        return $this->getStart()->getName() === $boardingCard->getStart()->getName()
            && $this->getEnd()->getName() === $boardingCard->getEnd()->getName();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return vsprintf('Start: %s, end: %s, transportation info: %s%s', [
            $this->start->getName(),
            $this->end->getName(),
            (string) $this->getTransportationInfo(),
            PHP_EOL
        ]);
    }
}
