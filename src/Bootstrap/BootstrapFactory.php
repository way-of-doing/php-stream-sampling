<?php

namespace WayOfDoing\PhpStreamSampling\Bootstrap;

use LimitIterator;
use LogicException;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use WayOfDoing\PhpStreamSampling\Exception\IncompleteInputException;
use WayOfDoing\PhpStreamSampling\Generator\CryptoStrengthRandomIntegerGenerator;
use WayOfDoing\PhpStreamSampling\Iterator\CallbackMappingIterator;
use WayOfDoing\PhpStreamSampling\Iterator\StreamIterator;
use WayOfDoing\PhpStreamSampling\Utility\StreamMetadata;

class BootstrapFactory
{
    private $userInput;

    private $defaultInputStream;

    private $runtimeConfiguration;

    public function __construct(InputInterface $userInput, $defaultInputStream = STDIN)
    {
        $this->ensureCompatibleEnvironment();
        $this->userInput = $userInput;
        $this->defaultInputStream = $defaultInputStream;
    }

    public function getInputIterator()
    {
        $useDefaultInputStream = !StreamMetadata::isCharacterDevice($this->defaultInputStream); // notice logical NOT

        $config = $this->getRuntimeConfiguration();
        $iterator = $useDefaultInputStream ? $this->getDefaultInputIterator() : $this->getGeneratedDataIterator();

        if ($config->getInputLengthLimit() !== null) {
            $iterator = new LimitIterator($iterator, 0, $config->getInputLengthLimit());
        } else if (!$useDefaultInputStream) {
            throw new LogicException('The generated data stream is infinite, so please specify a --limit.');
        }

        return $iterator;
    }

    public function getRuntimeConfiguration()
    {
        return $this->runtimeConfiguration
            ?? $this->runtimeConfiguration = $this->createRuntimeConfiguration();
    }

    private function ensureCompatibleEnvironment()
    {
        if (PHP_SAPI !== 'cli') {
            throw new LogicException('This SAPI is not supported.'); // TODO?
        }
    }

    private function getGeneratedDataIterator()
    {
        $generator = new CryptoStrengthRandomIntegerGenerator(0, 25);

        return new CallbackMappingIterator($generator->getIterator(), function ($i) { return chr(ord('A') + $i); });
    }

    private function getDefaultInputIterator()
    {
        return new StreamIterator($this->defaultInputStream);
    }

    private function createRuntimeConfiguration()
    {
        $inputDefinition = new InputDefinition();
        $inputDefinition->addOptions(
            [
                new InputOption('sample', 's', InputOption::VALUE_REQUIRED, 'Sample size'),
                new InputOption('limit', 'l', InputOption::VALUE_REQUIRED, 'Input character processing limit'),
            ]
        );

        $this->userInput->bind($inputDefinition);

        if ($this->userInput->getOption('sample') === null) {
            throw new IncompleteInputException();
        }

        $configuration = new RuntimeConfiguration(
            $this->userInput->getOption('sample'),
            $this->userInput->getOption('limit')
        );

        return $configuration;
    }
}
