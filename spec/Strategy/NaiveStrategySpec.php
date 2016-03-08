<?php

namespace spec\TripSorter\Strategy;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use TripSorter\Exception\TripSorterException;
use TripSorter\Model\BoardingCard;
use TripSorter\Model\BoardingCardInterface;
use TripSorter\Model\Location;
use TripSorter\Model\TransportationInfo\BusTransportationInfo;
use TripSorter\Model\TransportationInfo\PlaneTransportationInfo;
use TripSorter\Model\TransportationInfo\TrainTransportationInfo;

class NaiveStrategySpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('TripSorter\Strategy\NaiveStrategy');
        $this->shouldImplement('TripSorter\Strategy\StrategyInterface');
    }

    public function it_throws_exception_when_unable_to_find_start()
    {
        $collection = [];

        $this->initialize($collection);
        $this->shouldThrow(new TripSorterException('Unable to find start BoardingCard'))->duringFindStart();
    }

    public function it_throws_exception_when_unable_to_find_start_because_of_multiple_start_locations()
    {
        $collection = [
            new BoardingCard(
                new Location('Madrid'),
                new Location('Barcelona'),
                new TrainTransportationInfo('78A', '45B')
            ),
            new BoardingCard(
                new Location('Madrid'),
                new Location('Barcelona'),
                new TrainTransportationInfo('78A', '45B')
            ),
        ];

        $this->initialize($collection);
        $this->shouldThrow(new TripSorterException('Multiple start locations'))->duringFindStart();
    }

    public function it_return_valid_start_when_collection_is_correct()
    {
        $collection = [
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
        ];

        $this->initialize($collection);

        $startBoardingCard = $this->findStart();
        $startBoardingCard->shouldHaveType('TripSorter\Model\BoardingCard');
        $startBoardingCard->getStart()->getName()->shouldReturn('Madrid');
    }

    public function it_throws_exception_when_chain_is_broken_by_missing_boarding_card()
    {
        $collection = [
            $currentBoardingCard = new BoardingCard(
                new Location('Madrid'),
                new Location('Barcelona'),
                new TrainTransportationInfo('78A', '45B')
            ),
            // missing BoardingCard from Barcelona -> Gerona Airport
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
        ];

        $this->initialize($collection);

        $this->shouldThrow(new TripSorterException('Unable to find next BoardingCard'))->duringFindNext($currentBoardingCard);
    }

    public function it_throws_exception_when_chain_is_broken_by_multiple_next_boarding_cards()
    {
        $collection = [
            $currentBoardingCard = new BoardingCard(
                new Location('Madrid'),
                new Location('Barcelona'),
                new TrainTransportationInfo('78A', '45B')
            ),
            // multiple BoardingCard from Barcelona -> Gerona Airport
            new BoardingCard(
                new Location('Barcelona'),
                new Location('Gerona Airport'),
                new BusTransportationInfo()
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
        ];

        $this->initialize($collection);

        $this->shouldThrow(new TripSorterException('Multiple next BoardingCards'))->duringFindNext($currentBoardingCard);
    }

    public function it_return_valid_next_boarding_card()
    {
        $collection = [
            new BoardingCard(
                new Location('Madrid'),
                new Location('Barcelona'),
                new TrainTransportationInfo('78A', '45B')
            ),
            $currentBoardingCard = new BoardingCard(
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
        ];

        $this->initialize($collection);

        $nextBoardingCard = $this->findNext($currentBoardingCard);
        $nextBoardingCard->shouldHaveType('TripSorter\Model\BoardingCard');
        $nextBoardingCard->getStart()->getName()->shouldReturn('Gerona Airport');
    }
}
