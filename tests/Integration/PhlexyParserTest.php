<?php

namespace NenadalM\HoursCounter\Tests\Integration;

use NenadalM\HoursCounter\PhlexyParser;
use NenadalM\HoursCounter\Tests\ParserTestCase;

class PhlexyParserTest extends ParserTestCase
{
    public function getParser()
    {
        return new PhlexyParser();
    }
}
