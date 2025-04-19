<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\Dependent;

class BarangayController extends Controller
{
    public function __construct()
    {
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', 0);
    }
    
    public function index() {

        $barangays = Member::select('barangay')->distinct()->get();

        return view('barangay.index', compact('barangays'));
    }

    public function showBrgy($brgy)
    {
        $brgy_members = Member::where('barangay', $brgy)->get();
    
        $memberIds = $brgy_members->pluck('id');
        $brgy_dependents = Dependent::whereIn('member_id', $memberIds)->get();
    
        return view('barangay.show', compact('brgy_members', 'brgy_dependents', 'brgy'));
    }
}
