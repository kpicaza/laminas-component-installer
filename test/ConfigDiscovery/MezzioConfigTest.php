<?php

/**
 * @see       https://github.com/laminas/laminas-component-installer for the canonical source repository
 * @copyright https://github.com/laminas/laminas-component-installer/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-component-installer/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\ComponentInstaller\ConfigDiscovery;

use Laminas\ComponentInstaller\ConfigDiscovery\MezzioConfig;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;

class MezzioConfigTest extends TestCase
{
    /** @var vfsStreamDirectory */
    private $configDir;

    /** @var MezzioConfig */
    private $locator;

    protected function setUp() : void
    {
        $this->configDir = vfsStream::setup('project');
        $this->locator = new MezzioConfig(
            vfsStream::url('project')
        );
    }

    public function testAbsenceOfFileReturnsFalseOnLocate(): void
    {
        $this->assertFalse($this->locator->locate());
    }

    public function testLocateReturnsFalseWhenFileDoesNotHaveExpectedContents(): void
    {
        vfsStream::newFile('config/config.php')
            ->at($this->configDir)
            ->setContent('<' . "?php\nreturn [];");
        $this->assertFalse($this->locator->locate());
    }

    /**
     * @return string[][]
     *
     * @psalm-return array{fqcn-short-array: array{0: string}, globally-qualified-short-array: array{0: string}, imported-short-array: array{0: string}, fqcn-long-array: array{0: string}, globally-qualified-long-array: array{0: string}, imported-long-array: array{0: string}}
     */
    public function validMezzioConfigContents(): array
    {
        // @codingStandardsIgnoreStart
        return [
            'fqcn-short-array'               => ['<' . "?php\n\$configManager = new Mezzio\ConfigManager\ConfigManager([\n]);"],
            'globally-qualified-short-array' => ['<' . "?php\n\$configManager = new \Mezzio\ConfigManager\ConfigManager([\n]);"],
            'imported-short-array'           => ['<' . "?php\n\$configManager = new ConfigManager([\n]);"],
            'fqcn-long-array'                => ['<' . "?php\n\$configManager = new Mezzio\ConfigManager\ConfigManager(array(\n));"],
            'globally-qualified-long-array'  => ['<' . "?php\n\$configManager = new \Mezzio\ConfigManager\ConfigManager(array(\n));"],
            'imported-long-array'            => ['<' . "?php\n\$configManager = new ConfigManager(array(\n));"],
        ];
        // @codingStandardsIgnoreEnd
    }

    /**
     * @dataProvider validMezzioConfigContents
     *
     * @param string $contents
     *
     * @return void
     */
    public function testLocateReturnsTrueWhenFileExistsAndHasExpectedContent($contents): void
    {
        vfsStream::newFile('config/config.php')
            ->at($this->configDir)
            ->setContent($contents);

        $this->assertTrue($this->locator->locate());
    }
}
