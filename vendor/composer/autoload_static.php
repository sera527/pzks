<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit450bf87eca965058a4714463363ef093
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PZKS\\' => 5,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PZKS\\' => 
        array (
            0 => __DIR__ . '/../..' . '/PZKS',
        ),
    );

    public static $classMap = array (
        'PZKS\\Exceptions\\ErrorLexicalException' => __DIR__ . '/../..' . '/PZKS/Exceptions/ErrorLexicalException.php',
        'PZKS\\Exceptions\\LexicalException' => __DIR__ . '/../..' . '/PZKS/Exceptions/LexicalException.php',
        'PZKS\\Exceptions\\NoticeLexicalException' => __DIR__ . '/../..' . '/PZKS/Exceptions/NoticeLexicalException.php',
        'PZKS\\Exceptions\\PZKSException' => __DIR__ . '/../..' . '/PZKS/Exceptions/PZKSException.php',
        'PZKS\\LexicalValidator' => __DIR__ . '/../..' . '/PZKS/LexicalValidator.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit450bf87eca965058a4714463363ef093::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit450bf87eca965058a4714463363ef093::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit450bf87eca965058a4714463363ef093::$classMap;

        }, null, ClassLoader::class);
    }
}