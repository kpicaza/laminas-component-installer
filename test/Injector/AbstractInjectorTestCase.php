<?php

/**
 * @see       https://github.com/laminas/laminas-component-installer for the canonical source repository
 * @copyright https://github.com/laminas/laminas-component-installer/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-component-installer/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\ComponentInstaller\Injector;

use Laminas\ComponentInstaller\Injector\AbstractInjector;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;

use function file_get_contents;

abstract class AbstractInjectorTestCase extends TestCase
{
    /** @var vfsStreamDirectory */
    protected $configDir;

    /** @var string */
    protected $configFile;

    /** @var AbstractInjector */
    protected $injector;

    /** @var string */
    protected $injectorClass;

    /** @var int[] */
    protected $injectorTypesAllowed = [];

    protected function setUp() : void
    {
        $this->configDir = vfsStream::setup('project');

        $injectorClass = $this->injectorClass;
        $this->injector = new $injectorClass(
            vfsStream::url('project')
        );
    }

    abstract public function allowedTypes();

    /**
     * @dataProvider allowedTypes
     *
     * @param string $type
     * @param bool $expected
     *
     * @return void
     */
    public function testRegistersTypesReturnsExpectedBooleanBasedOnType($type, $expected): void
    {
        $this->assertSame($expected, $this->injector->registersType($type));
    }

    public function testGetTypesAllowedReturnsListOfAllExpectedTypes(): void
    {
        $this->assertEquals($this->injectorTypesAllowed, $this->injector->getTypesAllowed());
    }

    abstract public function injectComponentProvider();

    /**
     * @dataProvider injectComponentProvider
     *
     * @param string $type
     * @param string $initialContents
     * @param string $expectedContents
     *
     * @return void
     */
    public function testInjectAddsPackageToModulesListInAppropriateLocation($type, $initialContents, $expectedContents): void
    {
        vfsStream::newFile($this->configFile)
            ->at($this->configDir)
            ->setContent($initialContents);

        $injected = $this->injector->inject('Foo\Bar', $type);

        $result = file_get_contents(vfsStream::url('project/' . $this->configFile));
        $this->assertEquals($expectedContents, $result);
        $this->assertTrue($injected);
    }

    abstract public function packageAlreadyRegisteredProvider();

    /**
     * @dataProvider packageAlreadyRegisteredProvider
     *
     * @param string $contents
     * @param string $type
     *
     * @return void
     */
    public function testInjectDoesNotModifyContentsIfPackageIsAlreadyRegistered($contents, $type): void
    {
        vfsStream::newFile($this->configFile)
            ->at($this->configDir)
            ->setContent($contents);

        $injected = $this->injector->inject('Foo\Bar', $type);

        $result = file_get_contents(vfsStream::url('project/' . $this->configFile));
        $this->assertSame($contents, $result);
        $this->assertFalse($injected);
    }

    abstract public function emptyConfiguration();

    /**
     * @dataProvider emptyConfiguration
     *
     * @param string $contents
     *
     * @return void
     */
    public function testRemoveDoesNothingIfPackageIsNotInConfigFile($contents): void
    {
        vfsStream::newFile($this->configFile)
            ->at($this->configDir)
            ->setContent($contents);

        $removed = $this->injector->remove('Foo\Bar');
        $this->assertFalse($removed);
    }

    abstract public function packagePopulatedInConfiguration();

    /**
     * @dataProvider packagePopulatedInConfiguration
     *
     * @param string $initialContents
     * @param string $expectedContents
     *
     * @return void
     */
    public function testRemoveRemovesPackageFromConfigurationWhenFound($initialContents, $expectedContents): void
    {
        vfsStream::newFile($this->configFile)
            ->at($this->configDir)
            ->setContent($initialContents);

        $removed = $this->injector->remove('Foo\Bar');

        $result = file_get_contents(vfsStream::url('project/' . $this->configFile));
        $this->assertSame($expectedContents, $result);
        $this->assertTrue($removed);
    }
}
