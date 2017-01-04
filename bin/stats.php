<?php

use WayOfDoing\PhpStreamSampling\Iterator\BinaryStringIterator;
use WayOfDoing\PhpStreamSampling\Sampling\ReservoirSampler;

require __DIR__ . '/../vendor/autoload.php';

$input = 'abc';

$results = [];
for($i = 0; $i < 100000; ++$i) {
    $sample = ReservoirSampler::sample(new BinaryStringIterator($input), 2);
    sort($sample);
    $sample = implode('', $sample);
    $results += [$sample => 0];
    ++$results[$sample];
}
ksort($results);
print_r($results);
