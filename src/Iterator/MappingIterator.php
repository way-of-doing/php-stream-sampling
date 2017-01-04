<?php

namespace WayOfDoing\PhpStreamSampling\Iterator;

use Iterator;
use OuterIterator;

abstract class MappingIterator implements OuterIterator
{
    private $innerIterator;

    /**
     * MappingIterator constructor.
     *
     * @param Iterator $innerIterator
     */
    public function __construct(Iterator $innerIterator)
    {
        $this->innerIterator = $innerIterator;
    }

    final public function getInnerIterator(): Iterator
    {
        return $this->innerIterator;
    }

    public function current()
    {
        return $this->mapValue($this->getInnerIterator()->current());
    }

    public function next()
    {
        $this->getInnerIterator()->next();
    }

    public function key()
    {
        return $this->mapKey($this->getInnerIterator()->key());
    }

    public function valid(): bool
    {
        return $this->getInnerIterator()->valid();
    }

    public function rewind()
    {
        $this->getInnerIterator()->rewind();
    }

    protected abstract function mapKey($key);

    protected abstract function mapValue($value);
}
