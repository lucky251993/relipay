<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Detail;
use App\Imports\DetailsImport;
use App\Exports\DetailsExport;
use Maatwebsite\Excel\Facades\Excel;
class UserController extends Controller
{
    public function store(Request $request)
    {
        $tableData = json_decode($request->input('tableData'), true);

        foreach ($tableData as $data) {
            $imagePath = null;

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imagePath = $image->store('images', 'public'); // Store in public/images
            }

            Detail::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'number' => $data['number'],
                'role' => $data['role'],
                'password' => bcrypt($data['password']),
                'date' => $data['date'],
                'image' =>  $imagePath,
            ]);
        }

        return redirect()->back()->with('success', 'Details saved successfully!');
    }

    public function bulkUpload(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx',
        ]);

        Excel::import(new DetailsImport, $request->file('excel_file'));

        return redirect()->back()->with('success', 'Data uploaded successfully!');
    }

    // Method for bulk download
    public function bulkDownload()
    {
        return Excel::download(new DetailsExport, 'details.xlsx');
    }
}

