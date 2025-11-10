<?php

namespace app\components;

use Hashids\Hashids;

class HashId
{
    private static $salt = 'salt-kamu-harus-unik';
    private static $minLength = 8;

    public static function encode($id)
    {
        $h = new Hashids(self::$salt, self::$minLength);
        return $h->encode($id);
    }

    public static function decode($hash)
    {
        $h = new Hashids(self::$salt, self::$minLength);
        $out = $h->decode($hash);
        return $out[0] ?? null;
    }
}
