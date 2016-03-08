<?php

require_once __DIR__ . '/vendor/autoload.php';

function dumpCollection($collection) {
    foreach ($collection as $boardingCard) {
        echo (string) $boardingCard . '<br />';
    }
};

$collection = [
    new \TripSorter\Model\BoardingCard(
        new \TripSorter\Model\Location('Madrid'),
        new \TripSorter\Model\Location('Barcelona'),
        new \TripSorter\Model\TransportationInfo\TrainTransportationInfo('78A', '45B')
    ),
    new \TripSorter\Model\BoardingCard(
        new \TripSorter\Model\Location('Barcelona'),
        new \TripSorter\Model\Location('Gerona Airport'),
        new \TripSorter\Model\TransportationInfo\BusTransportationInfo()
    ),
    new \TripSorter\Model\BoardingCard(
        new \TripSorter\Model\Location('Gerona Airport'),
        new \TripSorter\Model\Location('Stockholm'),
        new \TripSorter\Model\TransportationInfo\PlaneTransportationInfo('SK455', '45B', '3A', 'drop at ticket counter 344')
    ),
    new \TripSorter\Model\BoardingCard(
        new \TripSorter\Model\Location('Stockholm'),
        new \TripSorter\Model\Location('New York JFK'),
        new \TripSorter\Model\TransportationInfo\PlaneTransportationInfo('SK22', '22', '7B', 'will we automatically transferred from your last leg')
    ),
];

dumpCollection($collection);

$sorter = new \TripSorter\Sorter(new \TripSorter\Strategy\NaiveStrategy());
$sortedCollection = $sorter->handle($collection);

dumpCollection($sortedCollection);