<?php

namespace WayOfDoing\PhpStreamSampling\Utility;

use InvalidArgumentException;

final class StreamMetadata
{
    const S_IFSOCK = 0140000; // socket

    const S_IFLNK = 0120000; // symbolic link

    const S_IFREG = 0100000; // regular file

    const S_IFBLK = 0060000; // block device

    const S_IFDIR = 0040000; // directory

    const S_IFCHR = 0020000; // character device

    const S_IFIFO = 0010000; // pipe

    public static function isCharacterDevice($stream) : bool
    {
        $stat = @fstat($stream);
        if ($stat === false) {
            throw new InvalidArgumentException('Argument must be a stream resource.');
        }

        return (bool)($stat['mode'] & self::S_IFCHR);
    }
}
