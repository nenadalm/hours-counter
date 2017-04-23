<?php
namespace NenadalM\HoursCounter\Tests\Integration;

use NenadalM\HoursCounter\HoaParser;
use NenadalM\HoursCounter\Tests\ParserTestCase;

class HoaParserTest extends ParserTestCase
{
    public function getParser()
    {
        return new HoaParser();
    }
}
