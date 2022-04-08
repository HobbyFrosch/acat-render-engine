<?php

namespace ACAT\Utils;

/**
 *
 */
class FileUtils {

    /**
     * @param string $file
     *
     * @return bool|string
     */
    static function stripTrailingSlash(string $file) {
        if ($file === '' || !StringUtils::startsWith($file, '/')) {
            return $file;
        }
        return substr($file, 1);
    }

    /**
     * @param $folder
     */
    static function deleteFolderRecursively($folder) {
        foreach (glob("{$folder}/*") as $file) {
            if (is_dir($file)) {
                self::deleteFolderRecursively($file);
            }
            else {
                unlink($file);
            }
        }
        rmdir($folder);
    }

}