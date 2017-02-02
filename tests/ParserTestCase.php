<?php

namespace NenadalM\HoursCounter\Tests;

use NenadalM\HoursCounter\ParserInterface;
use PHPUnit_Framework_TestCase;

abstract class ParserTestCase extends PHPUnit_Framework_TestCase
{
    /** @var ParserInterface */
    private $parser;

    protected function setUp()
    {
        $this->parser = $this->getParser();
    }

    /**
     * @expectedException \Exception
     *
     * @dataProvider invalidInputProvider
     */
    public function testInvalidInput($invalidFixture)
    {
        $this->parser->parse($this->fileContent($invalidFixture));
    }

    public function invalidInputProvider()
    {
        return [
            ['missingSemicolon'],
        ];
    }

    /**
     * @dataProvider testProvider
     */
    public function testOutput($fixture, $expected)
    {
        $result = $this->parser->parse($this->fileContent($fixture));
        $this->assertEquals($this->fileContent($expected), $result);
    }

    public function testProvider()
    {
        return [
            ['fixture', 'fixtureResult'],
        ];
    }

    private function fileContent($fileName)
    {
        return file_get_contents($this->file($fileName));
    }

    private function file($name)
    {
        return sprintf('%s/Fixtures/files/%s', __DIR__, $name);
    }

    /**
     * @return ParserInterface
     */
    abstract public function getParser();
}
