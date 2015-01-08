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
                    return [Parser::T_DAY, $value];
                } elseif (preg_match('/^=+$/', $value)) {
                    return [Parser::T_END_OF_BLOCK, $value];
                } elseif (preg_match('/^\s*[0-9]{1,2}:[0-9]{1,2} - [0-9]{1,2}:[0-9]{1,2}\s*$/', $value)) {
                    return [Parser::T_TIME_INTERVAL, $value];
                } elseif (preg_match('(\* .*)', $value)) {
                    return [Parser::T_DESCRIPTION, $value];
                } elseif (preg_match('/;/', $value)) {
                    return [Parser::T_END_OF_STATEMENT, $value];
                } elseif ($value === "\n") {
                    return [Parser::T_END_OF_LINE, $value];
                }

                throw new \Exception(sprintf('Unrecognized value "%s" found.', $value));
            });

        $parser = new Parser($lexer);

        return $parser->parse($input);
    }
}
