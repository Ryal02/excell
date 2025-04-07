<?php
use Illuminate\Support\Facades\Route;
use App\Imports\MembersImport;
use App\Exports\MembersExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\MemberController;
use Illuminate\Http\Request;

Route::get('/', function () {
    return redirect()->route('members.index');
});

Route::get('/members', [MemberController::class, 'index'])->name('members.index');
Route::post('/members/store', [MemberController::class, 'store'])->name('members.store');
Route::get('/listing', [MemberController::class, 'viewListing'])->name('members.viewListing');

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

Route::get('export', function () {
    return Excel::download(new MembersExport, 'members.xlsx');
});

