<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit2cd9345003383e683546b6ea3515fb54
{
    public static $prefixesPsr0 = array (
        'I' => 
        array (
            'Imagine' => 
            array (
                0 => __DIR__ . '/..' . '/imagine/imagine/lib',
            ),
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixesPsr0 = ComposerStaticInit2cd9345003383e683546b6ea3515fb54::$prefixesPsr0;

        }, null, ClassLoader::class);
    }
}