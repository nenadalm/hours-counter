<?php

namespace NenadalM\HoursCounter\Tests\Integration;

use PHPUnit_Framework_TestCase;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class AppTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider testProvider
     */
    public function testOutput($fixture, $expected)
    {
        $app = new \NenadalM\HoursCounter\App();
        $result = $app->run($this->fileContent($fixture));
        $this->assertEquals($this->fileContent($expected), $result);
    }

    public function testProvider()
    {
        return [
            ['fixture', 'fixtureResult']
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
