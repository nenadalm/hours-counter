<?php

namespace NenadalM\HoursCounter;

use JMS\Parser\AbstractParser;
use JMS\Parser\SimpleLexer;

/**
 * @property SimpleLexer $lexer
 *
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class Parser extends AbstractParser
{
    const T_UNKNOWN = 0;
    const T_DAY = 1;
    const T_END_OF_BLOCK = 2;
    const T_TIME_INTERVAL = 3;
    const T_DESCRIPTION = 4;
    const T_END_OF_STATEMENT = 5;

    protected function parseInternal()
    {
        $buffer = [];
        $chunks = [];
        $lastTimeSum = new Time(0, 0);
        $totalBlockTimeSum = new Time(0, 0);
        do {
            if ($this->lexer->isNext(static::T_DAY) && $chunks) {
                $buffer[] = '#'.$totalBlockTimeSum."\n\n";
                $totalBlockTimeSum = new Time(0, 0);
            }

            if ($this->lexer->isNext(static::T_TIME_INTERVAL)) {
                if ($this->lexer->token[2] !== static::T_END_OF_STATEMENT) {
                    $chunks = array_merge($chunks, $buffer);
                    $buffer = [];
                }
                $lastTimeSum = $lastTimeSum->add(Time::createFromIntervalString($this->lexer->next[0]));
            }

            if ($this->lexer->token[2] === static::T_TIME_INTERVAL && !$this->lexer->isNext(static::T_END_OF_STATEMENT)) {
                array_unshift($buffer, sprintf("\n%s", $lastTimeSum));
                $totalBlockTimeSum = $totalBlockTimeSum->add($lastTimeSum);
                $lastTimeSum = new Time(0, 0);
                $chunks = array_merge($chunks, $buffer);
                $buffer = [];
            }

            $buffer[] = $this->lexer->next[0];
        } while ($this->lexer->moveNext());

        $buffer[] = "\n#".$totalBlockTimeSum."\n";

        return implode('', array_merge($chunks, $buffer));
    }
}
