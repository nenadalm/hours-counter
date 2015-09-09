<?php

namespace NenadalM\HoursCounter;

interface ParserInterface
{
    const T_UNKNOWN = 0;
    const T_DAY = 1;
    const T_END_OF_BLOCK = 2;
    const T_TIME_INTERVAL = 3;
    const T_DESCRIPTION = 4;
    const T_END_OF_STATEMENT = 5;
    const T_END_OF_LINE = 6;

    /**
     * @param string $input
     *
     * @return string
     */
    public function parse($input);
}
