<?php

namespace NenadalM\HoursCounter\Tests\Integration;

use NenadalM\HoursCounter\JmsParser;
use NenadalM\HoursCounter\Tests\ParserTestCase;

class JmsParserTest extends ParserTestCase
{
    public function getParser()
    {
        return new JmsParser();
    }
}
