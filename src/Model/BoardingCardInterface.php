<?php

namespace TripSorter\Model;

use TripSorter\Model\TransportationInfo\TransportationInfoInterface;

interface BoardingCardInterface
{
    /**
     * @return LocationInterface
     */
    public function getStart();

    /**
     * @return LocationInterface
     */
    public function getEnd();

    /**
     * @return TransportationInfoInterface
     */
    public function getTransportationInfo();
}
