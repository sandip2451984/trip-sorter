<?php

namespace spec\TripSorter;

use PhpSpec\Exception\Example\FailureException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use TripSorter\Model\BoardingCard;
use TripSorter\Model\BoardingCardInterface;
use TripSorter\Model\Location;
use TripSorter\Model\TransportationInfo\BusTransportationInfo;
use TripSorter\Model\TransportationInfo\PlaneTransportationInfo;
use TripSorter\Model\TransportationInfo\TrainTransportationInfo;
use TripSorter\Strategy\NaiveStrategy;
use TripSorter\Strategy\StrategyInterface;

function dumpCollection($collection) {
    foreach ($collection as $boardingCard) {
        echo (string) $boardingCard . PHP_EOL;
    }
};

class SorterSpec extends ObjectBehavior
{
    public function it_is_initializable(StrategyInterface $strategy)
    {
        $this->beConstructedWith($strategy);
        $this->shouldHaveType('TripSorter\Sorter');
    }

    public function it_returns_sorted_collection()
    {
        $this->beConstructedWith(new NaiveStrategy());
        $sortedCollection = $this->handle($collection = [
            new BoardingCard(
                new Location('Stockholm'),
                new Location('New York JFK'),
                new PlaneTransportationInfo('SK22', '22', '7B', 'will we automatically transferred from your last leg')
            ),
            new BoardingCard(
                new Location('Gerona Airport'),
                new Location('Stockholm'),
                new PlaneTransportationInfo('SK455', '45B', '3A', 'drop at ticket counter 344')
            ),
            new BoardingCard(
                new Location('Barcelona'),
                new Location('Gerona Airport'),
                new BusTransportationInfo()
            ),
            new BoardingCard(
                new Location('Madrid'),
                new Location('Barcelona'),
                new TrainTransportationInfo('78A', '45B')
            ),
        ]);

        $sortedCollection->shouldBeArray();
        $sortedCollection->shouldHaveCount(count($collection));
        $sortedCollection->shouldContainsBoardingCardInstances();
        $sortedCollection->shouldEqualsCollection([
            new BoardingCard(
                new Location('Madrid'),
                new Location('Barcelona'),
                new TrainTransportationInfo('78A', '45B')
            ),
            new BoardingCard(
                new Location('Barcelona'),
                new Location('Gerona Airport'),
                new BusTransportationInfo()
            ),
            new BoardingCard(
                new Location('Gerona Airport'),
                new Location('Stockholm'),
                new PlaneTransportationInfo('SK455', '45B', '3A', 'drop at ticket counter 344')
            ),
            new BoardingCard(
                new Location('Stockholm'),
                new Location('New York JFK'),
                new PlaneTransportationInfo('SK22', '22', '7B', 'will we automatically transferred from your last leg')
            ),
        ]);
    }

    public function getMatchers()
    {
        return [
            'containsBoardingCardInstances' => function ($collection) {
                foreach ($collection as $boardingCard) {
                    if (!$boardingCard instanceof BoardingCardInterface) {
                        throw new FailureException(sprintf(
                            'All boarding cards in collection should have type BoardingCardInterface, %s given',
                            is_object($boardingCard) ? get_class($boardingCard) : gettype($boardingCard)
                        ));
                    }
                }

                return true;
            },
            'equalsCollection' => function ($collection, $expectedCollection) {
                foreach ($collection as $key => $boardingCard) {
                    if (!array_key_exists($key, $expectedCollection)) {
                        throw new FailureException('');
                    }

                    $expectedBoardingCard = $expectedCollection[$key];
                    if (!$boardingCard->isEqualWith($expectedBoardingCard)) {
                        throw new FailureException(sprintf(
                            'Given BoardingCard %s does is not equals with expected %s',
                            (string) $boardingCard,
                            (string) $expectedBoardingCard

                        ));
                    }
                }

                return true;
            },
        ];
    }
}
