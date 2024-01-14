<?php

namespace App\Helpers;

class Helpers
{

    /**
     * Converting an array before saving it to a database
     *
     * @param  array $data
     * @param  string $key
     * @return array
     */
    public static function convertingArrayBeforeSavingInDB(array $data, string $key): array
    {
        $result = [];
        foreach ($data as $item) {
            $result[] = [$key => $item];
        }

        return $result;
    }
}
