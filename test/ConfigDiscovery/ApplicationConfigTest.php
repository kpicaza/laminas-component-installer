<?php

/**
 * @see       https://github.com/laminas/laminas-component-installer for the canonical source repository
 * @copyright https://github.com/laminas/laminas-component-installer/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-component-installer/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\ComponentInstaller\ConfigDiscovery;

use Laminas\ComponentInstaller\ConfigDiscovery\ApplicationConfig;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;

class ApplicationConfigTest extends TestCase
{
    /** @var vfsStreamDirectory */
    private $configDir;

    /** @var ApplicationConfig */
    private $locator;

    protected function setUp() : void
    {
        $this->configDir = vfsStream::setup('project');
        $this->locator = new ApplicationConfig(
            vfsStream::url('project')
        );
    }

    public function testAbsenceOfFileReturnsFalseOnLocate(): void
    {
        $this->assertFalse($this->locator->locate());
    }

    public function testLocateReturnsFalseWhenFileDoesNotHaveExpectedContents(): void
    {
        vfsStream::newFile('config/application.config.php')
            ->at($this->configDir)
            ->setContent('<' . "?php\nreturn [];");
        $this->assertFalse($this->locator->locate());
    }

    /**
     * @return string[][]
     *
     * @psalm-return array{long-array: array{0: string}, short-array: array{0: string}}
     */
    public function validApplicationConfigContents(): array
    {
        return [
            'long-array'  => ['<' . "?php\nreturn array(\n    'modules' => array(\n    )\n);"],
            'short-array' => ['<' . "?php\nreturn [\n    'modules' => [\n    ]\n];"],
        ];
    }

    /**
     * @dataProvider validApplicationConfigContents
     *
     * @param string $contents
     *
     * @return void
     */
    public function testLocateReturnsTrueWhenFileExistsAndHasExpectedContent($contents): void
    {
        vfsStream::newFile('config/application.config.php')
            ->at($this->configDir)
            ->setContent($contents);

        $this->assertTrue($this->locator->locate());
    }
}
