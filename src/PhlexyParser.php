<?php

namespace NenadalM\HoursCounter;

use Phlexy\LexerDataGenerator;
use Phlexy\LexerFactory\Stateless\UsingPregReplace;
use Phlexy\Lexer\Stateless\UsingPregReplace as Lexer;

class PhlexyParser implements ParserInterface
{
    /** @var Lexer */
    private $lexer;

    public function __construct()
    {
        $factory = new UsingPregReplace(new LexerDataGenerator());
        $lexer = $factory->createLexer([
            ParserInterface::REGEXP_DAY => ParserInterface::T_DAY,
            ParserInterface::REGEXP_END_OF_BLOCK => ParserInterface::T_END_OF_BLOCK,
            ParserInterface::REGEXP_TIME_INTERVAL => ParserInterface::T_TIME_INTERVAL,
            ParserInterface::REGEXP_DESCRIPTION => ParserInterface::T_DESCRIPTION,
            ParserInterface::REGEXP_END_OF_STATEMENT => ParserInterface::T_END_OF_STATEMENT,
            ParserInterface::REGEXP_END_OF_LINE => ParserInterface::T_END_OF_LINE,
        ]);

        $this->lexer = $lexer;
    }

    public function parse($input)
    {
        $tokens = $this->lexer->lex($input);

        array_unshift($tokens, null);
        $buffer = [];
        $chunks = [];
        $totalTimeSum = new Time(0, 0);
        $lastTimeSum = new Time(0, 0);
        $totalBlockTimeSum = new Time(0, 0);
        for ($i = 0; $i < count($tokens); ++$i) {
            $next = isset($tokens[$i + 1]) ? $tokens[$i + 1] : [0 => null, 1 => null, 2 => null];
            $current = $tokens[$i];

            if ($next[0] === ParserInterface::T_DAY && $chunks) {
                $buffer[] = '#'.$totalBlockTimeSum."\n\n";
                $totalBlockTimeSum = new Time(0, 0);
            }

            if ($next[0] === ParserInterface::T_TIME_INTERVAL) {
                if ($current[0] !== ParserInterface::T_END_OF_STATEMENT) {
                    $chunks = array_merge($chunks, $buffer);
                    $buffer = [];
                }
                $lastTimeSum = $lastTimeSum->add(Time::createFromIntervalString($next[2]));
            }

            if ($current[0] === ParserInterface::T_TIME_INTERVAL && $next[0] !== ParserInterface::T_END_OF_STATEMENT) {
                array_unshift($buffer, sprintf("%s\n", $lastTimeSum));
                $totalBlockTimeSum = $totalBlockTimeSum->add($lastTimeSum);
                $totalTimeSum = $totalTimeSum->add($lastTimeSum);
                $lastTimeSum = new Time(0, 0);
                $chunks = array_merge($chunks, $buffer);
                $buffer = [];
            }

            $buffer[] = $next[2];
        }

        $buffer[] = "\n#".$totalBlockTimeSum."\n\n### Total hours: ".$totalTimeSum."\n";

        return implode('', array_merge($chunks, $buffer));
    }
}
