<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitb39e444368c1d0a1d6972b88e27fdd98
{
    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'SimplePie\\' => 10,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'SimplePie\\' => 
        array (
            0 => __DIR__ . '/..' . '/simplepie/simplepie/src',
        ),
    );

    public static $prefixesPsr0 = array (
        'S' => 
        array (
            'SimplePie' => 
            array (
                0 => __DIR__ . '/..' . '/simplepie/simplepie/library',
            ),
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitb39e444368c1d0a1d6972b88e27fdd98::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitb39e444368c1d0a1d6972b88e27fdd98::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInitb39e444368c1d0a1d6972b88e27fdd98::$prefixesPsr0;
            $loader->classMap = ComposerStaticInitb39e444368c1d0a1d6972b88e27fdd98::$classMap;

        }, null, ClassLoader::class);
    }
}
