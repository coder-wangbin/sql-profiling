<?php
/**
 * Desc:
 * User: baagee
 * Date: 2020/1/2
 * Time: 下午7:38
 */

namespace App\Library;
/**
 * Class IdGenerator
 * @package App\Library
 */
class IdGenerator
{
    /**
     * @return int
     */
    public static function getId()
    {
        return intval(microtime(true) * 10000) + mt_rand(10000, 99999);
    }
}
