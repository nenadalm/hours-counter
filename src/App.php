<?php

namespace NenadalM\HoursCounter;

class App
{
    public function run($input)
    {
        $phlexyParser = new PhlexyParser();

        return $phlexyParser->parse($input);
    }
}
