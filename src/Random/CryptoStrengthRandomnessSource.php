<?php

namespace WayOfDoing\PhpStreamSampling\Random;

final class CryptoStrengthRandomnessSource implements RandomnessSourceInterface
{
    public function getGuaranteedSafeRange(): array
    {
        return [0, PHP_INT_MAX]; // seems to be a bit conservative (PHP_INT_MIN to PHP_INT_MAX works for me), whatever
    }

    public function getNextRandom($minInclusive, $maxInclusive): int
    {
        return random_int($minInclusive, $maxInclusive);
    }

    public function isCryptoStrength(): bool
    {
        return true;
    }

    public function isSafeRange($minInclusive, $maxInclusive): bool
    {
        return true;
    }
}
