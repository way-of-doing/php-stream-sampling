<?php

namespace WayOfDoing\PhpStreamSampling\Bootstrap;

use WayOfDoing\PhpStreamSampling\Exception\ArgumentOutOfRangeException;

class RuntimeConfiguration
{
    private $inputLengthLimit;

    private $sampleSize;

    /**
     * RuntimeConfiguration constructor.
     *
     * @param int $sampleSize
     * @param int|null $inputLengthLimit
     */
    public function __construct(int $sampleSize, int $inputLengthLimit = null)
    {
        if ($sampleSize < 1) {
            throw new ArgumentOutOfRangeException(
                sprintf('Sample size must be positive (%d was specified).', $sampleSize)
            );
        }

        if ($inputLengthLimit !== null) {
            if ($inputLengthLimit < 1) {
                throw new ArgumentOutOfRangeException(
                    sprintf('Input length limit must be positive (%d was specified).', $inputLengthLimit)
                );
            } else if ($inputLengthLimit < $sampleSize) {
                throw new ArgumentOutOfRangeException(
                    sprintf(
                        'Input length limit (%d) must be no smaller than sample size (%d).',
                        $inputLengthLimit,
                        $sampleSize
                    )
                );
            }
        }

        $this->sampleSize = $sampleSize;
        $this->inputLengthLimit = $inputLengthLimit;
    }

    /**
     * @return int
     */
    public function getSampleSize(): int
    {
        return $this->sampleSize;
    }

    /**
     * @return int|null
     */
    public function getInputLengthLimit()
    {
        return $this->inputLengthLimit;
    }
}
