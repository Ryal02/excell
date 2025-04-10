<?php
use Illuminate\Support\Facades\Route;
use App\Imports\MembersImport;
use App\Exports\MembersExport;
use App\Exports\MemberDepExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\FetchMemberDetailsController;
use Illuminate\Http\Request;

Route::get('/', function () {
    return redirect()->route('dashboard');
});


// Route::get('/dashboard', function () {
//     return view('dashboard.index');
// })->name('dashboard');

// Route::get('/all-slp-list', [YourController::class, 'getAllSlp']);
Route::get('/dashboard', [MemberController::class, 'index'])->name('dashboard');

Route::get('/batches', [MemberController::class, 'batches'])->name('batches');
Route::get('/members/batch/{batch}', [MemberController::class, 'showBatchMembers'])->name('members.batch');

//SLP SIDEBAR
Route::get('/slp/good', [MemberController::class, 'slpGood'])->name('slpGood');

Route::get('/get-slp-options', [FetchMemberDetailsController::class, 'getSlpOptions'])->name('getSlpOptions');
Route::get('/members/slp/{slp}/dependents-good', [FetchMemberDetailsController::class, 'getDependentsBygoodSlp'])->name('members.getDependentsBygoodSlp');
Route::get('/members/slp/all', [FetchMemberDetailsController::class, 'getAllDependents'])->name('members.getAllDependents');



// Route::get('/members', [MemberController::class, 'index'])->name('members.index');
Route::post('/members/store', [MemberController::class, 'store'])->name('members.store');
Route::get('/listing', [MemberController::class, 'viewListing'])->name('members.viewListing');
Route::get('/members/{member}/edit', [MemberController::class, 'edit'])->name('members.edit');
Route::put('/members/{id}/update', [MemberController::class, 'update'])->name('members.update');
// slp bad
Route::get('/members/slp/{slp}/dependents', [MemberController::class, 'getDependentsBySlp'])->name('members.getDependentsBySlp');
// slp good
// Route::get('/members/slp/{slp}/dependents-good', [MemberController::class, 'getDependentsBygoodSlp'])->name('members.getDependentsBygoodSlp');
Route::get('/members/d1', [MemberController::class, 'getD1membersDep'])->name('members.getD1membersDep');

// Route::get('/members/slp/bad/all', [MemberController::class, 'getAllDependents'])->name('members.getAllDependents');

Route::post('import', function(Request $request) {

    $request->validate([
        'file' => 'required|mimes:xlsx,xls,csv',
        'batch' => 'required|string|max:255'
    ]);

    try {
        Excel::import(new MembersImport($request->batch), $request->file('file'));

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