<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToArray;

class TamuImport implements ToArray
{
    public function array(array $array)
    {
        return $array;
    }
}
