<?php

namespace TripSorter\Model\TransportationInfo;

class TrainTransportationInfo implements TransportationInfoInterface
{
    protected $trainNumber;
    protected $seatNumber;

    public function __construct($trainNumber, $seatNumber)
    {
        $this->trainNumber = $trainNumber;
        $this->seatNumber = $seatNumber;
    }

    /**
     * @inheritdoc
     */
    public function __toString()
    {
        return vsprintf('Train transportation: Train number: %s, seat number: %s', [
            $this->trainNumber,
            $this->seatNumber,
        ]);
    }
}
