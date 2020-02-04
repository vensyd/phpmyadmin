<?php
/**
 * Format converter
 *
 * @package PhpMyAdmin
 */
declare(strict_types=1);

namespace PhpMyAdmin\Utils;

use PhpMyAdmin\Util;

use function hex2bin;
use function inet_ntop;
use function strpos;
use function substr;

/**
 * Format converter
 *
 * @package PhpMyAdmin
 */
class FormatConverter
{
    /**
     * Transforms a binary to an IP
     *
     * @param mixed $buffer Data to transform
     *
     * @return false|string
     */
    public static function binaryToIp($buffer)
    {
        if (0 !== strpos($buffer, '0x')) {
            return $buffer;
        }

        $ipHex = substr($buffer, 2);
        $ipBin = hex2bin($ipHex);

        if (false === $ipBin) {
            return $buffer;
        }

        return @inet_ntop($ipBin);
    }

    /**
     * Transforms an IP to a binary
     *
     * @param mixed $buffer Data to transform
     *
     * @return string
     */
    public static function ipToBinary($buffer)
    {
        $val = @inet_pton($buffer);
        if ($val !== false) {
            return '0x' . bin2hex($val);
        }

        return $buffer;
    }

    /**
     * Transforms an IP to a long
     *
     * @param string $buffer Data to transform
     *
     * @return int|string
     */
    public static function ipToLong(string $buffer)
    {
        $ipLong = ip2long($buffer);
        if ($ipLong === false) {
            return $buffer;
        }

        return $ipLong;
    }

    /**
     * Transforms a long to an IP
     *
     * @param mixed $buffer Data to transform
     *
     * @return string
     */
    public static function longToIp($buffer)
    {
        if (! Util::isInteger($buffer) || $buffer < 0 || $buffer > 4294967295) {
            return $buffer;
        }

        return long2ip((int) $buffer);
    }
}
