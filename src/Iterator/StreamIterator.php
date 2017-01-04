<?php

namespace WayOfDoing\PhpStreamSampling\Iterator;

use Iterator;

/**
 * Iterates over a character stream.
 *
 * The first idea is that this could be trivially implemented on top of fread()
 * and feof(), but sadly that's not the case because of two things:
 *
 *      1. For maximum interoperability the iteration pattern must be assumed
 *         to be that of the foreach construct (rewind(); valid(); next())
 *      2. feof() does not return true until _after_ reading one past the end
 *         of the stream
 *
 * This means that in order for valid() to work as expected (which of course
 * includes being idempotent), end-of-stream detection _must_ happen by reading
 * one-ahead in next() and caching the result so it can be later returned
 * through current().
 */
class StreamIterator implements Iterator
{
    private $stream;

    private $pos;

    private $current;

    private $isExhausted = false;

    /**
     * StreamIterator constructor.
     *
     * @param resource $stream
     * @todo note begins iteration from current pos but seeks to 0 if rewound
     */
    public function __construct($stream)
    {
        $this->stream = $stream;
        $this->pos = ftell($this->stream);
        $this->isExhausted = feof($this->stream);
    }

    public function current()
    {
        if ($this->current === null) {
            $this->next();
        }

        return $this->current;
    }

    public function next()
    {
        ++$this->pos;
        $this->current = fread($this->stream, 1);
        if (feof($this->stream)) {
            $this->isExhausted = true;
        }
    }

    public function key()
    {
        return $this->pos;
    }

    public function valid()
    {
        return !$this->isExhausted;
    }

    public function rewind()
    {
        // Don't try to seek if you don't need to, otherwise foreach won't work over non-seekable streams
        if ($this->pos !== 0) {
            fseek($this->stream, 0);
            $this->current = null;
            $this->isExhausted = false;
        }
    }
}
