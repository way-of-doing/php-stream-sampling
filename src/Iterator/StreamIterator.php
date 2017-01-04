<?php

namespace WayOfDoing\PhpStreamSampling\Iterator;

use Iterator;

class StreamIterator implements Iterator
{
    private $stream;

    private $pos;

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
    }

    public function current()
    {
        return fread($this->stream, 1);
    }

    public function next()
    {
        ++$this->pos;
    }

    public function key()
    {
        return $this->pos;
    }

    public function valid()
    {
        return !feof($this->stream);
    }

    public function rewind()
    {
        // Don't try to seek if you don't need to, otherwise foreach won't work over non-seekable streams
        if ($this->pos !== 0) {
            fseek($this->stream, 0);
        }
    }
}
