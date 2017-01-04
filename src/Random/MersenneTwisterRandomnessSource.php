<?php

namespace WayOfDoing\PhpStreamSampling\Random;

final class MersenneTwisterRandomnessSource implements RandomnessSourceInterface
{
    public function getGuaranteedSafeRange(): array
    {
        return [0, mt_getrandmax()];
    }

    public function getNextRandom($minInclusive, $maxInclusive): int
    {
        return mt_rand($minInclusive, $maxInclusive);
    }

    public function isCryptoStrength(): bool
    {
        return false;
    }

    public function isSafeRange($minInclusive, $maxInclusive): bool
    {
        $rangeSize = $maxInclusive - $minInclusive;

        return is_int($rangeSize) && $rangeSize <= mt_getrandmax();
    }
}
