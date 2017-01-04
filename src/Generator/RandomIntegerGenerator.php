<?php

namespace WayOfDoing\PhpStreamSampling\Generator;

use IteratorAggregate;
use Traversable;
use WayOfDoing\PhpStreamSampling\Exception\ArgumentOutOfRangeException;
use WayOfDoing\PhpStreamSampling\Random\RandomnessSourceInterface;

class RandomIntegerGenerator implements IteratorAggregate
{
    private $randomnessSource;

    private $minInclusive;

    private $maxInclusive;

    /**
     * RandomIntegerGenerator constructor.
     *
     * @param RandomnessSourceInterface $randomnessSource
     * @param int|null $minInclusive
     * @param int|null $maxInclusive
     */
    public function __construct(RandomnessSourceInterface $randomnessSource, $minInclusive = null, $maxInclusive = null)
    {
        if (!isset($minInclusive, $maxInclusive)) {
            list($defaultMin, $defaultMax) = $randomnessSource->getGuaranteedSafeRange();
            $minInclusive = $minInclusive ?? $defaultMin;
            $maxInclusive = $maxInclusive ?? $defaultMax;
        }

        if (!$randomnessSource->isSafeRange($minInclusive, $maxInclusive)) {
            throw new ArgumentOutOfRangeException('Effective integer range is not safe with randomness source provided.');
        }

        $this->randomnessSource = $randomnessSource;
        $this->minInclusive = $minInclusive;
        $this->maxInclusive = $maxInclusive;
    }

    final public function getRandomnessSource(): RandomnessSourceInterface
    {
        return $this->randomnessSource;
    }

    final public function getMinRandomValue(): int
    {
        return $this->minInclusive;
    }

    final public function getMaxRandomValue(): int
    {
        return $this->maxInclusive;
    }

    final public function getIterator()
    {
        return $this->randomGenerator();
    }

    private function randomGenerator(): Traversable
    {
        while (true) {
            yield $this->randomnessSource->getNextRandom($this->minInclusive, $this->maxInclusive);
        }
    }
}
