<?php

class GuidGenerator
{
    public static function create()
    {
        if (function_exists('com_create_guid')) {
            $guid = trim(com_create_guid(), '{}');
        } else {
            $guid = sprintf(
                '%04X%04X-%04X-%04X-%04X-%04X%04X%04X',
                mt_rand(0, 65535),
                mt_rand(0, 65535),
                mt_rand(0, 65535),
                mt_rand(16384, 20479),
                mt_rand(32768, 49151),
                mt_rand(0, 65535),
                mt_rand(0, 65535),
                mt_rand(0, 65535)
            );
        }

        return $guid;
    }
}