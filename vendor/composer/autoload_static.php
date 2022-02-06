<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitb20e905536dd182822d259cbcf4531dd
{
    public static $prefixLengthsPsr4 = array (
        'W' => 
        array (
            'WilliamCosta\\DotEnv\\' => 20,
        ),
        'A' => 
        array (
            'App\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'WilliamCosta\\DotEnv\\' => 
        array (
            0 => __DIR__ . '/..' . '/william-costa/dot-env/src',
        ),
        'App\\' => 
        array (
            0 => __DIR__ . '/../..' . '/app',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitb20e905536dd182822d259cbcf4531dd::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitb20e905536dd182822d259cbcf4531dd::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitb20e905536dd182822d259cbcf4531dd::$classMap;

        }, null, ClassLoader::class);
    }
}
