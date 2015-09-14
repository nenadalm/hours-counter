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

    const REGEXP_DAY = '[a-z]+:';
    const REGEXP_END_OF_BLOCK = '=+';
    const REGEXP_TIME_INTERVAL = '(<? *)[0-9]{1,2}:[0-9]{1,2} - [0-9]{1,2}:[0-9]{1,2}(?=[;\n])';
    const REGEXP_DESCRIPTION = '(\* [^\n]*)';
    const REGEXP_END_OF_STATEMENT = ';';
    const REGEXP_END_OF_LINE = "\n";

    /**
     * @param string $input
     *
     * @return string
     */
    public function parse($input);
}
