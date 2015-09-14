<?php

namespace NenadalM\HoursCounter;

use Exception;
use JMS\Parser\AbstractParser;
use JMS\Parser\SimpleLexer;

class JmsParser extends AbstractParser implements ParserInterface
{
    public function __construct()
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
            if (preg_match($this->lineRegexp(ParserInterface::REGEXP_DAY), $value)) {
                return [ParserInterface::T_DAY, $value];
            } elseif (preg_match($this->lineRegexp(ParserInterface::REGEXP_END_OF_BLOCK), $value)) {
                return [ParserInterface::T_END_OF_BLOCK, $value];
            } elseif (preg_match('/^\s*[0-9]{1,2}:[0-9]{1,2} - [0-9]{1,2}:[0-9]{1,2}\s*$/', $value)) {
                return [ParserInterface::T_TIME_INTERVAL, $value];
            } elseif (preg_match($this->lineRegexp(ParserInterface::REGEXP_DESCRIPTION), $value)) {
                return [ParserInterface::T_DESCRIPTION, $value];
            } elseif (preg_match($this->regexp(ParserInterface::REGEXP_END_OF_STATEMENT), $value)) {
                return [ParserInterface::T_END_OF_STATEMENT, $value];
            } elseif ($value === ParserInterface::REGEXP_END_OF_LINE) {
                return [ParserInterface::T_END_OF_LINE, $value];
            }

            throw new Exception(sprintf('Unrecognized value "%s" found.', $value));
        });

        parent::__construct($lexer);
    }

    private function lineRegexp($regexpPart)
    {
        return $this->regexp(sprintf('^%s$', $regexpPart));
    }

    private function regexp($regexpPart)
    {
        return sprintf('/%s/', $regexpPart);
    }

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
