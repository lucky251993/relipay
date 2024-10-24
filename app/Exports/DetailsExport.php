<?php

namespace App\Exports;

use App\Models\Detail;
use Maatwebsite\Excel\Concerns\FromCollection;

class DetailsExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Detail::all();
    }
}
