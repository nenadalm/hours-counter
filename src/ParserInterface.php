<?php

namespace NenadalM\HoursCounter;

interface ParserInterface
{
    /**
     * @param string $input
     *
     * @return string
     */
    public function parse($input);
}
