<?php

namespace WayOfDoing\PhpStreamSampling\Iterator;

use Iterator;

class BinaryStringIterator implements Iterator
{
    private $string;

    private $len;

    private $pos;

    /**
     * BinaryStringIterator constructor.
     *
     * @param string $string
     */
    public function __construct(string $string)
    {
        $this->string = $string;
        $this->len = strlen($string);
        $this->rewind();
    }

    public function current(): string
    {
        return $this->string[$this->pos];
    }

    public function next()
    {
        ++$this->pos;
    }

    public function key(): int
    {
        return $this->pos;
    }

    public function valid(): bool
    {
        return $this->pos < $this->len;
    }

    public function rewind()
    {
        $this->pos = 0;
    }
}
