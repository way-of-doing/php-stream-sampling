# Stream Sampling

## Task

### Description

Please write a script that receives and processes an input stream consisting of single characters. The stream is of 
unknown and possibly very large length and the script should work regardless of the size of the input.

The script should take one parameter, the sample size, and generate a random representative sample using that many
characters.

As for receiving the data the tool should work with two kinds of inputs:

- Values piped directly into the process using `cat input.txt | yourScript`
- Values generated by using a secure random source from within the language

Efficiency, memory consumption, testing, and a clean coding style are all important criteria to keep in mind.

### Example

Given a sample size of 5 and the following stream of characters as values

    THEQUICKBROWNFOXJUMPSOVERTHELAZYDOG

A possible random sample could be:

    EMETN

## Implementation

The aim is to produce a general, flexible, testable and tested solution for the general problem that can be applied
to many situations.

Class `ReservoirSampler` implements [Algorithm R](https://en.wikipedia.org/wiki/Reservoir_sampling#Algorithm_R).
Call `ReservoirSampler::sample()` for an one-stop-shop or use the incremental interface for additional control.

## Installation

Clone the repository, then run

	php bin/composer.phar install

This uses a bundled version of [Composer](https://getcomposer.org/) (provided for convenience) to automatically
pull project dependencies and generate a class autoloader. Nothing outside the project directory is affected.

## Usage

    php bin/run.php --sample|-s <size> [--limit|-l <bytes>]

Samples the input one byte at a time to produce a representative sample of size `<size>`. The input is selected
automatically between `STDIN` (if piped to the process) and a cryptographically strong random sequence of the letters
A-Z (if nothing is piped).

In the latter case it is mandatory to specify a limit for the input because by default the random sequence is of
infinite length.

    php bin/stats.php

Samples a hardcoded input configuration 100K times, collates the results and displays the number of occurrences for
each unique sample produced. Used as a quick and dirty test to verify algorithm correctness.
