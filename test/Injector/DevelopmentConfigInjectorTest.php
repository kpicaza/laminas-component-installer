<?php

/**
 * @see       https://github.com/laminas/laminas-component-installer for the canonical source repository
 * @copyright https://github.com/laminas/laminas-component-installer/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-component-installer/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\ComponentInstaller\Injector;

use Laminas\ComponentInstaller\Injector\DevelopmentConfigInjector;

class DevelopmentConfigInjectorTest extends AbstractInjectorTestCase
{
    /** @var string */
    protected $configFile = 'config/development.config.php.dist';

    /** @var string */
    protected $injectorClass = DevelopmentConfigInjector::class;

    /** @var int[] */
    protected $injectorTypesAllowed = [
        DevelopmentConfigInjector::TYPE_COMPONENT,
        DevelopmentConfigInjector::TYPE_MODULE,
        DevelopmentConfigInjector::TYPE_DEPENDENCY,
        DevelopmentConfigInjector::TYPE_BEFORE_APPLICATION,
    ];

    /**
     * @return (bool|int)[][]
     *
     * @psalm-return array{config-provider: array{0: int, 1: false}, component: array{0: int, 1: true}, module: array{0: int, 1: true}, dependency: array{0: int, 1: true}, before-application-modules: array{0: int, 1: true}}
     */
    public function allowedTypes()
    {
        return [
            'config-provider'            => [DevelopmentConfigInjector::TYPE_CONFIG_PROVIDER, false],
            'component'                  => [DevelopmentConfigInjector::TYPE_COMPONENT, true],
            'module'                     => [DevelopmentConfigInjector::TYPE_MODULE, true],
            'dependency'                 => [DevelopmentConfigInjector::TYPE_DEPENDENCY, true],
            'before-application-modules' => [DevelopmentConfigInjector::TYPE_BEFORE_APPLICATION, true],
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
        $baseContentsLongArray  = '<' . "?php\nreturn array(\n    'modules' => array(\n        'Application',\n    )\n);";
        $baseContentsShortArray = '<' . "?php\nreturn [\n    'modules' => [\n        'Application',\n    ]\n];";
        return [
            'component-long-array'  => [DevelopmentConfigInjector::TYPE_COMPONENT, $baseContentsLongArray,  '<' . "?php\nreturn array(\n    'modules' => array(\n        'Foo\Bar',\n        'Application',\n    )\n);"],
            'component-short-array' => [DevelopmentConfigInjector::TYPE_COMPONENT, $baseContentsShortArray, '<' . "?php\nreturn [\n    'modules' => [\n        'Foo\Bar',\n        'Application',\n    ]\n];"],
            'module-long-array'     => [DevelopmentConfigInjector::TYPE_MODULE,    $baseContentsLongArray,  '<' . "?php\nreturn array(\n    'modules' => array(\n        'Application',\n        'Foo\Bar',\n    )\n);"],
            'module-short-array'    => [DevelopmentConfigInjector::TYPE_MODULE,    $baseContentsShortArray, '<' . "?php\nreturn [\n    'modules' => [\n        'Application',\n        'Foo\Bar',\n    ]\n];"],
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
            'component-long-array'  => ['<' . "?php\nreturn array(\n    'modules' => array(\n        'Foo\Bar',\n        'Application',\n    )\n);", DevelopmentConfigInjector::TYPE_COMPONENT],
            'component-short-array' => ['<' . "?php\nreturn [\n    'modules' => [\n        'Foo\Bar',\n        'Application',\n    ]\n];",           DevelopmentConfigInjector::TYPE_COMPONENT],
            'module-long-array'     => ['<' . "?php\nreturn array(\n    'modules' => array(\n        'Application',\n        'Foo\Bar',\n    )\n);", DevelopmentConfigInjector::TYPE_MODULE],
            'module-short-array'    => ['<' . "?php\nreturn [\n    'modules' => [\n        'Application',\n        'Foo\Bar',\n    ]\n];",           DevelopmentConfigInjector::TYPE_MODULE],
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
        $baseContentsLongArray  = '<' . "?php\nreturn array(\n    'modules' => array(\n        'Application',\n    )\n);";
        $baseContentsShortArray = '<' . "?php\nreturn [\n    'modules' => [\n        'Application',\n    ]\n];";
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
        $baseContentsLongArray  = '<' . "?php\nreturn array(\n    'modules' => array(\n        'Application',\n    )\n);";
        $baseContentsShortArray = '<' . "?php\nreturn [\n    'modules' => [\n        'Application',\n    ]\n];";
        return [
            'long-array'  => ['<' . "?php\nreturn array(\n    'modules' => array(\n        'Foo\Bar',\n        'Application',\n    )\n);", $baseContentsLongArray],
            'short-array' => ['<' . "?php\nreturn [\n    'modules' => [\n        'Foo\Bar',\n        'Application',\n    ]\n];",           $baseContentsShortArray],
        ];
        // @codingStandardsIgnoreEnd
    }
}
