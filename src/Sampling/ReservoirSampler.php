<?php

namespace WayOfDoing\PhpStreamSampling\Sampling;

use WayOfDoing\PhpStreamSampling\Exception\InsufficientInputException;
use WayOfDoing\PhpStreamSampling\Random\MersenneTwisterRandomnessSource;
use WayOfDoing\PhpStreamSampling\Random\RandomnessSourceInterface;

final class ReservoirSampler
{
    /**
     * @var RandomnessSourceInterface
     */
    private $randomnessSource;

    private $reservoir;

    private $observedItemCount;

    public function __construct(RandomnessSourceInterface $randomnessSource, int $sampleSize)
    {
        $this->randomnessSource = $randomnessSource;
        $this->reset($sampleSize);
    }

    /**
     * @param \Traversable|array $input
     * @param int $sampleSize
     *
     * @return array
     */
    public static function sample($input, int $sampleSize): array
    {
        $sampler = new self(new MersenneTwisterRandomnessSource(), $sampleSize);
        foreach ($input as $item) {
            $sampler->observe($item);
        }

        return $sampler->getSample();
    }

    public function getSampleSize(): int
    {
        return count($this->reservoir);
    }

    public function reset(int $sampleSize)
    {
        $this->observedItemCount = 0;
        $this->reservoir = array_fill(0, $sampleSize, null); // what we initialize with should not actually matter
    }

    public function observe($item)
    {
        $sampleSize = $this->getSampleSize();

        if ($this->observedItemCount < $sampleSize) {
            $this->reservoir[$this->observedItemCount++] = $item;
        } else {
            $random = $this->randomnessSource->getNextRandom(0, $this->observedItemCount++);
            if ($random < $sampleSize) {
                $this->reservoir[$random] = $item;
            }
        }
    }

    public function canSample(): bool
    {
        return $this->observedItemCount >= $this->getSampleSize();
    }

    public function getSample(): array
    {
        if (!$this->canSample()) {
            throw new InsufficientInputException(
                sprintf(
                    'Cannot produce a sample of size %d because only %d items have been observed.',
                    $this->getSampleSize(),
                    $this->observedItemCount
                )
            );
        }

        return $this->reservoir;
    }
}
