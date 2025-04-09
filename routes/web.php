<?php
use Illuminate\Support\Facades\Route;
use App\Imports\MembersImport;
use App\Exports\MembersExport;
use App\Exports\MemberDepExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\MemberController;
use Illuminate\Http\Request;

Route::get('/', function () {
    return redirect()->route('members.index');
});

// Route::get('/all-slp-list', [YourController::class, 'getAllSlp']);

Route::get('/members', [MemberController::class, 'index'])->name('members.index');
Route::post('/members/store', [MemberController::class, 'store'])->name('members.store');
Route::get('/listing', [MemberController::class, 'viewListing'])->name('members.viewListing');
Route::get('/members/{member}/edit', [MemberController::class, 'edit'])->name('members.edit');
Route::put('/members/{id}/update', [MemberController::class, 'update'])->name('members.update');
// slp bad
Route::get('/members/slp/{slp}/dependents', [MemberController::class, 'getDependentsBySlp'])->name('members.getDependentsBySlp');
// slp good
Route::get('/members/slp/{slp}/dependents-good', [MemberController::class, 'getDependentsBygoodSlp'])->name('members.getDependentsBygoodSlp');
Route::get('/members/d1', [MemberController::class, 'getD1membersDep'])->name('members.getD1membersDep');

Route::get('/members/slp/all/dependents', [MemberController::class, 'getAllDependents'])->name('members.getAllDependents');

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
Route::post('/export-visible-members', function (Request $request) {
    // Decode the data passed from frontend
    $data = json_decode($request->input('data'), true);

    // Return the data as an Excel download
    return Excel::download(new MemberDepExport($data), 'visible_members.xlsx');
})->name('export.visibleMembers');