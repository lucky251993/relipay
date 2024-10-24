<?php

namespace App\Imports;

use App\Models\Detail;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class DetailsImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Detail([
            //
        ]);
    }
    
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
           
            Detail::create([
                'name' => $row[0],
                'email' => $row[1],
                'number' => $row[2],
                'role' => $row[3],
                'password' => bcrypt($row[4]),
                'date' => \Carbon\Carbon::parse($row[5]),
                'image' => null, 
            ]);
        }
    }
}
