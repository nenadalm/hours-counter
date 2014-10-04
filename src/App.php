<?php

namespace NenadalM\HoursCounter;

use JMS\Parser\SimpleLexer;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class App
{
    public function run($input)
    {
        $lexer = new SimpleLexer('/
                (^$)
                |(;)
                |([a-z]+:)
                |(=+)
                |([0-9]{1,2}:[0-9]{1,2} - [0-9]{1,2}:[0-9]{1,2})
                |(\* .*)
            /xm', [
                'T_UNKNOWN',
                'T_DAY',
                'T_END_OF_BLOCK',
                'T_TIME_INTERVAL',
                'T_DESCRIPTION',
            ], function ($value) {
                if (preg_match('/^[a-z]+:$/', $value)) {
                    return [1, $value];
                } elseif (preg_match('/^=+$/', $value)) {
                    return [2, $value];
                } elseif (preg_match('/[0-9]{1,2}:[0-9]{1,2} - [0-9]{1,2}:[0-9]{1,2}/', $value)) {
                    return [3, $value];
                } elseif (preg_match('(\* .*)', $value)) {
                    return [4, $value];
                } elseif (preg_match('/;/', $value)) {
                    return [5, $value];
                }

                return [0, $value];
            });

        $parser = new Parser($lexer);

        return $parser->parse($input);
    }
}
