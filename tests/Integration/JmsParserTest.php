<?php

namespace NenadalM\HoursCounter\Tests\Integration;

use NenadalM\HoursCounter\Parser;
use NenadalM\HoursCounter\Tests\ParserTestCase;

class JmsParserTest extends ParserTestCase
{
    public function getParser()
    {
        return new Parser();
    }
}
