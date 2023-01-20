<?php

namespace App\Imports;

use App\Models\LoanRequest;
use Maatwebsite\Excel\Concerns\ToModel;
use Str;

class LoanImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {

        dd($row);


            // 'loan_request_guid' => Str::uuid(),
            // 'fullname' => $row[0],
            // 'identity'=>$row[1],

        
    }
}
