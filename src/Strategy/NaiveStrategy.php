<?php

namespace TripSorter\Strategy;

use TripSorter\Exception\TripSorterException;
use TripSorter\Model\BoardingCardInterface;

class NaiveStrategy implements StrategyInterface
{
    protected $boardingCards;

    /**
     * @inheritdoc
     */
    public function initialize(array $boardingCards)
    {
        $this->boardingCards = $boardingCards;
    }

    /**
     * @inheritdoc
     */
    public function findStart()
    {
        /**
         * 1) group BoardingCards by start Location
         *
         * [
         *      'Madrit' => [
         *          'start' => BoardingCard[],
         *          'end' => BoardingCard[]
         *      ],
         *      'Barcelona' => [
         *          'start' => BoardingCard[],
         *          'end' => BoardingCard[]
         *      ],
         *      ... etc.
         * ]
         */

        $boardingCardsGroupedByLocation = array_reduce(
            $this->boardingCards,
            function ($carry, BoardingCardInterface $boardingCard) {
                if (!array_key_exists($boardingCard->getStart()->getName(), $carry)) {
                    $carry[$boardingCard->getStart()->getName()] = [
                        'start' => [],
                        'end' => [],
                    ];
                }

                if (!array_key_exists($boardingCard->getEnd()->getName(), $carry)) {
                    $carry[$boardingCard->getEnd()->getName()] = [
                        'start' => [],
                        'end' => [],
                    ];
                }

                $carry[$boardingCard->getStart()->getName()]['start'][] = $boardingCard;
                $carry[$boardingCard->getEnd()->getName()]['end'][] = $boardingCard;

                return $carry;
            },
            []
        );

        /**
         * 2) Filter out only where start !== [] && end === []
         */
        $filteredBoardingCards = array_filter(
            $boardingCardsGroupedByLocation,
            function ($locationBoardingCards) {
                return $locationBoardingCards['start'] !== [] && $locationBoardingCards['end'] === [];
            }
        );

        /**
         * 3) Check and return if valid, throw exception when not
         */
        if (1 !== count($filteredBoardingCards)) {
            throw new TripSorterException('Unable to find start BoardingCard');
        }

        $startLocationBoardingCards = current($filteredBoardingCards)['start'];
        if (1 < count($startLocationBoardingCards)) {
            throw new TripSorterException('Multiple start locations');
        }

        return current($startLocationBoardingCards);
    }

    /**
     * @inheritdoc
     */
    public function findNext(BoardingCardInterface $currentBoardingCard)
    {
        $nextBoardingCards = array_filter(
            $this->boardingCards,
            function (BoardingCardInterface $boardingCard) use ($currentBoardingCard) {
                return $currentBoardingCard->getEnd()->getName() === $boardingCard->getStart()->getName();
            }
        );

        if (0 === count($nextBoardingCards)) {
            throw new TripSorterException('Unable to find next BoardingCard');
        }

        if (1 < count($nextBoardingCards)) {
            throw new TripSorterException('Multiple next BoardingCards');
        }

        return current($nextBoardingCards);
    }
}
