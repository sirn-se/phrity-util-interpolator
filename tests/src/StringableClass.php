<?php

namespace Phrity\Util\Interpolator\Test;

use Stringable;

class StringableClass implements Stringable
{
    private string $content;

    public function __construct(string $content)
    {
        $this->content = $content;
    }

    public function __toString(): string
    {
        return $this->content;
    }
}
