<?php

use Symfony\Component\Console\Input\ArgvInput;
use WayOfDoing\PhpStreamSampling\Bootstrap\BootstrapFactory;
use WayOfDoing\PhpStreamSampling\Exception\IncompleteInputException;
use WayOfDoing\PhpStreamSampling\Sampling\ReservoirSampler;

require __DIR__ . '/../vendor/autoload.php';

try {
    $factory = new BootstrapFactory(new ArgvInput());
    $config = $factory->getRuntimeConfiguration();
    $sample = ReservoirSampler::sample($factory->getInputIterator(), $config->getSampleSize());

    echo PHP_EOL;
    print_r($sample);
    echo PHP_EOL;
    die(0);
} catch (IncompleteInputException $e) {
    printf("usage: use --sample|-s <size> to specify sample size, optionally --limit|-l <bytes> to process only part of the input\n");
    printf("usage: input is automatically selected between STDIN (if data is piped), or a CSPRNG-generated sequence of A-Z (if not)\n");
} catch (Exception $e) {
    printf("error: %s\n", $e->getMessage());
}

die(1);
