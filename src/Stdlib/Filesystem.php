<?php
namespace Core42\Stdlib;

abstract class Filesystem
{
    /**
     * @param string $file
     * @return bool
     */
    public static function isAbsolutePath($file)
    {
        return strspn($file, '/\\', 0, 1)
        || (strlen($file) > 3 && ctype_alpha($file[0])
            && substr($file, 1, 1) === ':'
            && strspn($file, '/\\', 2, 1)
        )
        || null !== parse_url($file, PHP_URL_SCHEME);
    }
}
