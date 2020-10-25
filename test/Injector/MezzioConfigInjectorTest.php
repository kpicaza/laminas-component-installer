<?php

/**
 * @see       https://github.com/laminas/laminas-component-installer for the canonical source repository
 * @copyright https://github.com/laminas/laminas-component-installer/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-component-installer/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\ComponentInstaller\Injector;

use Laminas\ComponentInstaller\Injector\MezzioConfigInjector;

use function preg_replace;

class MezzioConfigInjectorTest extends AbstractInjectorTestCase
{
    /** @var string */
    protected $configFile = 'config/config.php';

    /** @var string */
    protected $injectorClass = MezzioConfigInjector::class;

    /** @var int[] */
    protected $injectorTypesAllowed = [
        MezzioConfigInjector::TYPE_CONFIG_PROVIDER,
    ];

    /**
     * @param false|string $contents
     *
     * @return false|null|string
     */
    public function convertToShortArraySyntax($contents)
    {
        if (false === $contents) {
            return false;
        }


        return preg_replace('/array\(([^)]+)\)/s', '[$1]', $contents);
    }

    /**
     * @return (bool|int)[][]
     */
    public function allowedTypes()
    {
        return [
            'config-provider' => [MezzioConfigInjector::TYPE_CONFIG_PROVIDER, true],
            'component'       => [MezzioConfigInjector::TYPE_COMPONENT, false],
            'module'          => [MezzioConfigInjector::TYPE_MODULE, false],
        ];
    }

    /**
     * @return (false|int|mixed|string)[][]
     */
    public function injectComponentProvider()
    {
        // @codingStandardsIgnoreStart
        $baseContentsFqcnLongArray              = file_get_contents(__DIR__ . '/TestAsset/legacy-mezzio-application-fqcn.config.php');
        $baseContentsGloballyQualifiedLongArray = file_get_contents(__DIR__ . '/TestAsset/legacy-mezzio-application-globally-qualified.config.php');
        $baseContentsImportLongArray            = file_get_contents(__DIR__ . '/TestAsset/legacy-mezzio-application-import.config.php');

        $baseContentsFqcnShortArray              = $this->convertToShortArraySyntax($baseContentsFqcnLongArray);
        $baseContentsGloballyQualifiedShortArray = $this->convertToShortArraySyntax($baseContentsGloballyQualifiedLongArray);
        $baseContentsImportShortArray            = $this->convertToShortArraySyntax($baseContentsImportLongArray);

        $expectedContentsFqcnLongArray              = file_get_contents(__DIR__ . '/TestAsset/legacy-mezzio-populated-fqcn.config.php');
        $expectedContentsGloballyQualifiedLongArray = file_get_contents(__DIR__ . '/TestAsset/legacy-mezzio-populated-globally-qualified.config.php');
        $expectedContentsImportLongArray            = file_get_contents(__DIR__ . '/TestAsset/legacy-mezzio-populated-import.config.php');

        $expectedContentsFqcnShortArray              = $this->convertToShortArraySyntax($expectedContentsFqcnLongArray);
        $expectedContentsGloballyQualifiedShortArray = $this->convertToShortArraySyntax($expectedContentsGloballyQualifiedLongArray);
        $expectedContentsImportShortArray            = $this->convertToShortArraySyntax($expectedContentsImportLongArray);

        return [
            'fqcn-long-array'    => [MezzioConfigInjector::TYPE_CONFIG_PROVIDER, $baseContentsFqcnLongArray,               $expectedContentsFqcnLongArray],
            'global-long-array'  => [MezzioConfigInjector::TYPE_CONFIG_PROVIDER, $baseContentsGloballyQualifiedLongArray,  $expectedContentsGloballyQualifiedLongArray],
            'import-long-array'  => [MezzioConfigInjector::TYPE_CONFIG_PROVIDER, $baseContentsImportLongArray,             $expectedContentsImportLongArray],
            'fqcn-short-array'   => [MezzioConfigInjector::TYPE_CONFIG_PROVIDER, $baseContentsFqcnShortArray,              $expectedContentsFqcnShortArray],
            'global-short-array' => [MezzioConfigInjector::TYPE_CONFIG_PROVIDER, $baseContentsGloballyQualifiedShortArray, $expectedContentsGloballyQualifiedShortArray],
            'import-short-array' => [MezzioConfigInjector::TYPE_CONFIG_PROVIDER, $baseContentsImportShortArray,            $expectedContentsImportShortArray],
        ];
        // @codingStandardsIgnoreEnd
    }

    /**
     * @return (false|int|mixed|string)[][]
     */
    public function packageAlreadyRegisteredProvider()
    {
        // @codingStandardsIgnoreStart
        $fqcnLongArray              = file_get_contents(__DIR__ . '/TestAsset/legacy-mezzio-populated-fqcn.config.php');
        $globallyQualifiedLongArray = file_get_contents(__DIR__ . '/TestAsset/legacy-mezzio-populated-globally-qualified.config.php');
        $importLongArray            = file_get_contents(__DIR__ . '/TestAsset/legacy-mezzio-populated-import.config.php');

        $fqcnShortArray              = $this->convertToShortArraySyntax($fqcnLongArray);
        $globallyQualifiedShortArray = $this->convertToShortArraySyntax($globallyQualifiedLongArray);
        $importShortArray            = $this->convertToShortArraySyntax($importLongArray);

        return [
            'fqcn-long-array'    => [$fqcnLongArray,               MezzioConfigInjector::TYPE_CONFIG_PROVIDER],
            'global-long-array'  => [$globallyQualifiedLongArray,  MezzioConfigInjector::TYPE_CONFIG_PROVIDER],
            'import-long-array'  => [$importLongArray,             MezzioConfigInjector::TYPE_CONFIG_PROVIDER],
            'fqcn-short-array'   => [$fqcnShortArray,              MezzioConfigInjector::TYPE_CONFIG_PROVIDER],
            'global-short-array' => [$globallyQualifiedShortArray, MezzioConfigInjector::TYPE_CONFIG_PROVIDER],
            'import-short-array' => [$importShortArray,            MezzioConfigInjector::TYPE_CONFIG_PROVIDER],
        ];
        // @codingStandardsIgnoreEnd
    }

    /**
     * @return (false|mixed|string)[][]
     */
    public function emptyConfiguration()
    {
        // @codingStandardsIgnoreStart
        $fqcnLongArray              = file_get_contents(__DIR__ . '/TestAsset/legacy-mezzio-empty-fqcn.config.php');
        $globallyQualifiedLongArray = file_get_contents(__DIR__ . '/TestAsset/legacy-mezzio-empty-globally-qualified.config.php');
        $importLongArray            = file_get_contents(__DIR__ . '/TestAsset/legacy-mezzio-empty-import.config.php');
        // @codingStandardsIgnoreEnd

        $fqcnShortArray              = $this->convertToShortArraySyntax($fqcnLongArray);
        $globallyQualifiedShortArray = $this->convertToShortArraySyntax($globallyQualifiedLongArray);
        $importShortArray            = $this->convertToShortArraySyntax($importLongArray);

        return [
            'fqcn-long-array'    => [$fqcnLongArray],
            'global-long-array'  => [$globallyQualifiedLongArray],
            'import-long-array'  => [$importLongArray],
            'fqcn-short-array'   => [$fqcnShortArray],
            'global-short-array' => [$globallyQualifiedShortArray],
            'import-short-array' => [$importShortArray],
        ];
    }

    /**
     * @return (false|mixed|string)[][]
     */
    public function packagePopulatedInConfiguration()
    {
        // @codingStandardsIgnoreStart
        $baseContentsFqcnLongArray              = file_get_contents(__DIR__ . '/TestAsset/legacy-mezzio-populated-fqcn.config.php');
        $baseContentsGloballyQualifiedLongArray = file_get_contents(__DIR__ . '/TestAsset/legacy-mezzio-populated-globally-qualified.config.php');
        $baseContentsImportLongArray            = file_get_contents(__DIR__ . '/TestAsset/legacy-mezzio-populated-import.config.php');

        $baseContentsFqcnShortArray              = $this->convertToShortArraySyntax($baseContentsFqcnLongArray);
        $baseContentsGloballyQualifiedShortArray = $this->convertToShortArraySyntax($baseContentsGloballyQualifiedLongArray);
        $baseContentsImportShortArray            = $this->convertToShortArraySyntax($baseContentsImportLongArray);

        $expectedContentsFqcnLongArray              = file_get_contents(__DIR__ . '/TestAsset/legacy-mezzio-application-fqcn.config.php');
        $expectedContentsGloballyQualifiedLongArray = file_get_contents(__DIR__ . '/TestAsset/legacy-mezzio-application-globally-qualified.config.php');
        $expectedContentsImportLongArray            = file_get_contents(__DIR__ . '/TestAsset/legacy-mezzio-application-import.config.php');

        $expectedContentsFqcnShortArray              = $this->convertToShortArraySyntax($expectedContentsFqcnLongArray);
        $expectedContentsGloballyQualifiedShortArray = $this->convertToShortArraySyntax($expectedContentsGloballyQualifiedLongArray);
        $expectedContentsImportShortArray            = $this->convertToShortArraySyntax($expectedContentsImportLongArray);

        return [
            'fqcn-long-array'    => [$baseContentsFqcnLongArray,               $expectedContentsFqcnLongArray],
            'global-long-array'  => [$baseContentsGloballyQualifiedLongArray,  $expectedContentsGloballyQualifiedLongArray],
            'import-long-array'  => [$baseContentsImportLongArray,             $expectedContentsImportLongArray],
            'fqcn-short-array'   => [$baseContentsFqcnShortArray,              $expectedContentsFqcnShortArray],
            'global-short-array' => [$baseContentsGloballyQualifiedShortArray, $expectedContentsGloballyQualifiedShortArray],
            'import-short-array' => [$baseContentsImportShortArray,            $expectedContentsImportShortArray],
        ];
        // @codingStandardsIgnoreEnd
    }
}
