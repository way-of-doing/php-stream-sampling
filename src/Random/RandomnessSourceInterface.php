<?php

namespace WayOfDoing\PhpStreamSampling\Random;

interface RandomnessSourceInterface
{
    public function getGuaranteedSafeRange(): array;

    public function getNextRandom($minInclusive, $maxInclusive): int;

    public function isCryptoStrength(): bool;

    public function isSafeRange($minInclusive, $maxInclusive): bool;
}
