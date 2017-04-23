<?php
namespace NenadalM\HoursCounter;

use Hoa\Compiler\Llk\Llk;
use Hoa\Compiler\Llk\Rule\Token;
use Hoa\File\Read;

class HoaParser implements ParserInterface
{
    public function parse($input)
    {
        $compiler = Llk::load(new Read(__DIR__ . '/../resources/grammar/grammar.pp'));
        $compiler->parse($input);
        $trace = $compiler->getTrace();

        /* @var $tokens Token[] */
        $tokens = array_values(array_filter(
            $trace,
            function ($token) {
                return $token instanceof Token;
            }
        ));

        array_unshift($tokens, new Token(null, null, null, null));
        $buffer = [];
        $chunks = [];
        $totalTimeSum = new Time(0, 0);
        $lastTimeSum = new Time(0, 0);
        $totalBlockTimeSum = new Time(0, 0);
        for ($i = 0; $i < count($tokens); ++$i) {
            /* @var $next Token */
            $next = isset($tokens[$i + 1]) ? $tokens[$i + 1] : new Token(null, null, null, null);
            $current = $tokens[$i];

            if ($next->getTokenName() === 'day' && $chunks) {
                $buffer[] = '#'.$totalBlockTimeSum."\n\n";
                $totalBlockTimeSum = new Time(0, 0);
            }

            if ($next->getTokenName() === 'time_interval') {
                if ($current->getTokenName() !== 'end_of_statement') {
                    $chunks = array_merge($chunks, $buffer);
                    $buffer = [];
                }
                $lastTimeSum = $lastTimeSum->add(Time::createFromIntervalString($next->getValue()));
            }

            if ($current->getTokenName() === 'time_interval' && $next->getTokenName() !== 'end_of_statement') {
                array_unshift($buffer, sprintf("%s\n", $lastTimeSum));
                $totalBlockTimeSum = $totalBlockTimeSum->add($lastTimeSum);
                $totalTimeSum = $totalTimeSum->add($lastTimeSum);
                $lastTimeSum = new Time(0, 0);
                $chunks = array_merge($chunks, $buffer);
                $buffer = [];
            }

            $buffer[] = $next->getValue();
        }

        $buffer[] = "\n#".$totalBlockTimeSum."\n\n### Total hours: ".$totalTimeSum."\n";

        return implode('', array_merge($chunks, $buffer));
    }
}
