<?php

namespace TripSorter\Model\TransportationInfo;

class BusTransportationInfo implements TransportationInfoInterface
{
    protected $seatNumber;

    public function __construct($seatNumber = null)
    {
        $this->seatNumber = $seatNumber;
    }

    /**
     * @inheritdoc
     */
    public function __toString()
    {
        if (null === $this->seatNumber) {
            return 'Bus transportation. No seat assignment';
        }

        return sprintf('Bus transportation. Seat number: %s', $this->seatNumber);
    }
}
