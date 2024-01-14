<?php

namespace App\Helpers;

class Helpers
{
    public static function convertingArrayBeforeSavingInDB(array $data, string $key): array
    {
        $result = [];
        foreach ($data as $item) {
            $result[] = [$key => $item];
        }

        return $result;
    }
}
