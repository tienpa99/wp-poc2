<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit76cb9cb2be702ee7fbb85fe13ed6af08
{
    public static $prefixLengthsPsr4 = array (
        'N' => 
        array (
            'NitroPack\\Url\\' => 14,
            'NitroPack\\SDK\\' => 14,
            'NitroPack\\HttpClient\\' => 21,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'NitroPack\\Url\\' => 
        array (
            0 => __DIR__ . '/..' . '/nitropack/url/src',
        ),
        'NitroPack\\SDK\\' => 
        array (
            0 => __DIR__ . '/../..' . '/NitroPack/SDK',
        ),
        'NitroPack\\HttpClient\\' => 
        array (
            0 => __DIR__ . '/..' . '/nitropack/httpclient/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit76cb9cb2be702ee7fbb85fe13ed6af08::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit76cb9cb2be702ee7fbb85fe13ed6af08::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit76cb9cb2be702ee7fbb85fe13ed6af08::$classMap;

        }, null, ClassLoader::class);
    }
}
