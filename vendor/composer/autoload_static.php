<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitc86d10e389656bc5f0bdfef460336a0d
{
    public static $classMap = array (
        'Grav\\Plugin\\JSONLDBreadcrumbsPlugin' => __DIR__ . '/../..' . '/json-ld-breadcrumbs.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->classMap = ComposerStaticInitc86d10e389656bc5f0bdfef460336a0d::$classMap;

        }, null, ClassLoader::class);
    }
}
