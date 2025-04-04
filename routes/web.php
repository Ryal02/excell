<?php
use Illuminate\Support\Facades\Route;
use App\Imports\MembersImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('import');
});

Route::get('import', function() {
    return view('import');
});

Route::post('import', function(Request $request) {
    $request->validate([
        'file' => 'required|mimes:xlsx,xls,csv'
    ]);

    try {
        // Import the file
        Excel::import(new MembersImport, $request->file('file'));
        return redirect()->back()->with('success', 'Data Imported Successfully!');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'An error occurred while importing the file: ' . $e->getMessage());
    }
});
