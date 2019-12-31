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
    protected $configFile = 'config/development.config.php.dist';

    protected $injectorClass = DevelopmentConfigInjector::class;

    protected $injectorTypesAllowed = [
        DevelopmentConfigInjector::TYPE_COMPONENT,
        DevelopmentConfigInjector::TYPE_MODULE,
        DevelopmentConfigInjector::TYPE_DEPENDENCY,
        DevelopmentConfigInjector::TYPE_BEFORE_APPLICATION,
    ];

    public function allowedTypes()
    {
        return [
            'config-provider' => [DevelopmentConfigInjector::TYPE_CONFIG_PROVIDER, false],
            'component'       => [DevelopmentConfigInjector::TYPE_COMPONENT, true],
            'module'          => [DevelopmentConfigInjector::TYPE_MODULE, true],
            'dependency'      => [DevelopmentConfigInjector::TYPE_DEPENDENCY, true],
            'before-application-modules' => [DevelopmentConfigInjector::TYPE_BEFORE_APPLICATION, true],
        ];
    }

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

    public function emptyConfiguration()
    {
        // @codingStandardsIgnoreStart
        $baseContentsLongArray  = '<' . "?php\nreturn array(\n    'modules' => array(\n        'Application',\n    )\n);";
        $baseContentsShortArray = '<' . "?php\nreturn [\n    'modules' => [\n        'Application',\n    ]\n];";
        return [
            'component-long-array'  => [DevelopmentConfigInjector::TYPE_COMPONENT, $baseContentsLongArray],
            'component-short-array' => [DevelopmentConfigInjector::TYPE_COMPONENT, $baseContentsShortArray],
            'module-long-array'     => [DevelopmentConfigInjector::TYPE_MODULE,    $baseContentsLongArray],
            'module-short-array'    => [DevelopmentConfigInjector::TYPE_MODULE,    $baseContentsShortArray],
        ];
        // @codingStandardsIgnoreEnd
    }

    public function packagePopulatedInConfiguration()
    {
        // @codingStandardsIgnoreStart
        $baseContentsLongArray  = '<' . "?php\nreturn array(\n    'modules' => array(\n        'Application',\n    )\n);";
        $baseContentsShortArray = '<' . "?php\nreturn [\n    'modules' => [\n        'Application',\n    ]\n];";
        return [
            'component-long-array'  => [DevelopmentConfigInjector::TYPE_COMPONENT, '<' . "?php\nreturn array(\n    'modules' => array(\n        'Foo\Bar',\n        'Application',\n    )\n);", $baseContentsLongArray],
            'component-short-array' => [DevelopmentConfigInjector::TYPE_COMPONENT, '<' . "?php\nreturn [\n    'modules' => [\n        'Foo\Bar',\n        'Application',\n    ]\n];",           $baseContentsShortArray],
            'module-long-array'     => [DevelopmentConfigInjector::TYPE_MODULE,    '<' . "?php\nreturn array(\n    'modules' => array(\n        'Application',\n        'Foo\Bar',\n    )\n);", $baseContentsLongArray],
            'module-short-array'    => [DevelopmentConfigInjector::TYPE_MODULE,    '<' . "?php\nreturn [\n    'modules' => [\n        'Application',\n        'Foo\Bar',\n    ]\n];",           $baseContentsShortArray],
        ];
        // @codingStandardsIgnoreEnd
    }
}
