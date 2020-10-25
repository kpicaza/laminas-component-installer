<?php

/**
 * @see       https://github.com/laminas/laminas-component-installer for the canonical source repository
 * @copyright https://github.com/laminas/laminas-component-installer/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-component-installer/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\ComponentInstaller\Injector;

use Laminas\ComponentInstaller\Injector\ModulesConfigInjector;

class ModulesConfigInjectorTest extends AbstractInjectorTestCase
{
    /** @var string */
    protected $configFile = 'config/modules.config.php';

    /** @var string */
    protected $injectorClass = ModulesConfigInjector::class;

    /** @var int[] */
    protected $injectorTypesAllowed = [
        ModulesConfigInjector::TYPE_COMPONENT,
        ModulesConfigInjector::TYPE_MODULE,
        ModulesConfigInjector::TYPE_DEPENDENCY,
        ModulesConfigInjector::TYPE_BEFORE_APPLICATION,
    ];

    /**
     * @return (bool|int)[][]
     *
     * @psalm-return array{config-provider: array{0: int, 1: false}, component: array{0: int, 1: true}, module: array{0: int, 1: true}, dependency: array{0: int, 1: true}, before-application-modules: array{0: int, 1: true}}
     */
    public function allowedTypes()
    {
        return [
            'config-provider'            => [ModulesConfigInjector::TYPE_CONFIG_PROVIDER, false],
            'component'                  => [ModulesConfigInjector::TYPE_COMPONENT, true],
            'module'                     => [ModulesConfigInjector::TYPE_MODULE, true],
            'dependency'                 => [ModulesConfigInjector::TYPE_DEPENDENCY, true],
            'before-application-modules' => [ModulesConfigInjector::TYPE_BEFORE_APPLICATION, true],
        ];
    }

    /**
     * @return (int|string)[][]
     *
     * @psalm-return array{component-long-array: array{0: int, 1: string, 2: string}, component-short-array: array{0: int, 1: string, 2: string}, module-long-array: array{0: int, 1: string, 2: string}, module-short-array: array{0: int, 1: string, 2: string}}
     */
    public function injectComponentProvider()
    {
        // @codingStandardsIgnoreStart
        $baseContentsLongArray  = '<' . "?php\nreturn array(\n    'Application',\n);";
        $baseContentsShortArray = '<' . "?php\nreturn [\n    'Application',\n];";
        return [
            'component-long-array'  => [ModulesConfigInjector::TYPE_COMPONENT, $baseContentsLongArray,  '<' . "?php\nreturn array(\n    'Foo\Bar',\n    'Application',\n);"],
            'component-short-array' => [ModulesConfigInjector::TYPE_COMPONENT, $baseContentsShortArray, '<' . "?php\nreturn [\n    'Foo\Bar',\n    'Application',\n];"],
            'module-long-array'     => [ModulesConfigInjector::TYPE_MODULE,    $baseContentsLongArray,  '<' . "?php\nreturn array(\n    'Application',\n    'Foo\Bar',\n);"],
            'module-short-array'    => [ModulesConfigInjector::TYPE_MODULE,    $baseContentsShortArray, '<' . "?php\nreturn [\n    'Application',\n    'Foo\Bar',\n];"],
        ];
        // @codingStandardsIgnoreEnd
    }

    /**
     * @return (int|string)[][]
     *
     * @psalm-return array{component-long-array: array{0: string, 1: int}, component-short-array: array{0: string, 1: int}, module-long-array: array{0: string, 1: int}, module-short-array: array{0: string, 1: int}}
     */
    public function packageAlreadyRegisteredProvider()
    {
        // @codingStandardsIgnoreStart
        return [
            'component-long-array'  => ['<' . "?php\nreturn array(\n    'Foo\Bar',\n    'Application',\n);", ModulesConfigInjector::TYPE_COMPONENT],
            'component-short-array' => ['<' . "?php\nreturn [\n    'Foo\Bar',\n    'Application',\n];",      ModulesConfigInjector::TYPE_COMPONENT],
            'module-long-array'     => ['<' . "?php\nreturn array(\n    'Application',\n    'Foo\Bar',\n);", ModulesConfigInjector::TYPE_MODULE],
            'module-short-array'    => ['<' . "?php\nreturn [\n    'Application',\n    'Foo\Bar',\n];",      ModulesConfigInjector::TYPE_MODULE],
        ];
        // @codingStandardsIgnoreEnd
    }

    /**
     * @return string[][]
     *
     * @psalm-return array{long-array: array{0: string}, short-array: array{0: string}}
     */
    public function emptyConfiguration()
    {
        // @codingStandardsIgnoreStart
        $baseContentsLongArray  = '<' . "?php\nreturn array(\n    'Application',\n);";
        $baseContentsShortArray = '<' . "?php\nreturn [\n    'Application',\n];";
        // @codingStandardsIgnoreEnd

        return [
            'long-array'  => [$baseContentsLongArray],
            'short-array' => [$baseContentsShortArray],
        ];
    }

    /**
     * @return string[][]
     *
     * @psalm-return array{long-array: array{0: string, 1: string}, short-array: array{0: string, 1: string}}
     */
    public function packagePopulatedInConfiguration()
    {
        // @codingStandardsIgnoreStart
        $baseContentsLongArray  = '<' . "?php\nreturn array(\n    'Application',\n);";
        $baseContentsShortArray = '<' . "?php\nreturn [\n    'Application',\n];";
        return [
            'long-array'  => ['<' . "?php\nreturn array(\n    'Foo\Bar',\n    'Application',\n);", $baseContentsLongArray],
            'short-array' => ['<' . "?php\nreturn [\n    'Foo\Bar',\n    'Application',\n];",      $baseContentsShortArray],
        ];
        // @codingStandardsIgnoreEnd
    }
}
