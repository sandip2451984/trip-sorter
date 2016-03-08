<?php

namespace TripSorter\Model\TransportationInfo;

class PlaneTransportationInfo implements TransportationInfoInterface
{
    protected $flight;
    protected $gate;
    protected $seat;
    protected $baggageDrop;

    public function __construct($flight, $gate, $seat, $baggageDrop)
    {
        $this->flight = $flight;
        $this->gate = $gate;
        $this->seat = $seat;
        $this->baggageDrop = $baggageDrop;
    }

    /**
     * @inheritdoc
     */
    public function __toString()
    {
        // TODO: Implement __toString() method.
        return vsprintf('Plane transportation: Flight: %s, gate: %s, seat: %s, baggage: %s', [
            $this->flight,
            $this->gate,
            $this->seat,
            $this->baggageDrop,
        ]);
    }
}
