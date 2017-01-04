<?php

namespace WayOfDoing\PhpStreamSampling\Generator;

use WayOfDoing\PhpStreamSampling\Random\CryptoStrengthRandomnessSource;

final class CryptoStrengthRandomIntegerGenerator extends RandomIntegerGenerator
{
    /**
     * CryptoStrengthRandomIntegerGenerator constructor.
     *
     * @param $minInclusive
     * @param $maxInclusive
     */
    public function __construct($minInclusive = 0, $maxInclusive = PHP_INT_MAX)
    {
        parent::__construct(new CryptoStrengthRandomnessSource(), $minInclusive, $maxInclusive);
    }
}
