<?php

namespace NenadalM\HoursCounter\Tests\Integration;

use NenadalM\HoursCounter\App;
use PHPUnit_Framework_TestCase;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class AppTest extends PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Exception
     *
     * @dataProvider invalidInputProvider
     */
    public function testInvalidInput($invalidFixture)
    {
        $app = new App();
        $app->run($this->fileContent($invalidFixture));
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
        $app = new App();
        $result = $app->run($this->fileContent($fixture));
        $this->assertEquals($this->fileContent($expected), $result);
    }

    public function testProvider()
    {
        return [
            ['fixture', 'fixtureResult'],
        ];
    }

    protected function fileContent($fileName)
    {
        return file_get_contents($this->file($fileName));
    }

    protected function file($name)
    {
        return sprintf('%s/../Fixtures/files/%s', __DIR__, $name);
    }
}
