<?php

namespace WayOfDoing\PhpStreamSampling\Iterator;

use Iterator;

final class CallbackMappingIterator extends MappingIterator
{
    private $keyMapper;

    private $valueMapper;

    /**
     * CallbackMappingIterator constructor.
     *
     * @param Iterator $iterator
     * @param callable $valueMapper
     * @param callable $keyMapper
     */
    public function __construct(Iterator $iterator, callable $valueMapper, callable $keyMapper = null)
    {
        parent::__construct($iterator);
        $this->keyMapper = $keyMapper;
        $this->valueMapper = $valueMapper;
    }

    protected function mapKey($key)
    {
        return $this->keyMapper ? call_user_func($this->keyMapper, $key) : $key;
    }

    protected function mapValue($value)
    {
        return call_user_func($this->valueMapper, $value);
    }

}
