<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitf1d661133a6495c20eec2c2b34632f7c
{
    public static $prefixLengthsPsr4 = array (
        'm' => 
        array (
            'mikehaertl\\shellcommand\\' => 24,
        ),
        'P' => 
        array (
            'PHPMailer\\PHPMailer\\' => 20,
        ),
        'I' => 
        array (
            'Ifsnop\\' => 7,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'mikehaertl\\shellcommand\\' => 
        array (
            0 => __DIR__ . '/..' . '/mikehaertl/php-shellcommand/src',
        ),
        'PHPMailer\\PHPMailer\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpmailer/phpmailer/src',
        ),
        'Ifsnop\\' => 
        array (
            0 => __DIR__ . '/..' . '/ifsnop/mysqldump-php/src/Ifsnop',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitf1d661133a6495c20eec2c2b34632f7c::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitf1d661133a6495c20eec2c2b34632f7c::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitf1d661133a6495c20eec2c2b34632f7c::$classMap;

        }, null, ClassLoader::class);
    }
}
