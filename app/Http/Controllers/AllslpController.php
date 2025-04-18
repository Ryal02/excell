<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Dependent;
use Illuminate\Http\Request;

class AllslpController extends Controller
{
    public function __construct()
    {
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', 0);
    }
    public function index()
    {
        return view('getslp.all');
    }

    public function fetchAll()
    {
        $memberBarangays = Member::pluck('barangay')->filter()->unique();
        $dependentBarangays = Dependent::join('members', 'dependents.member_id', '=', 'members.id')
            ->pluck('members.barangay')->filter()->unique();
    
        $barangays = $memberBarangays->merge($dependentBarangays)->unique()->sort()->values();
    
        $slpGroups = Member::with('dependents')->get()->groupBy('slp');
        $data = [];
    
        foreach ($slpGroups as $slp => $group) {
            $row = ['slp' => $slp];
    
            // Init barangay member and dependent counts
            foreach ($barangays as $barangay) {
                $members = $group->where('barangay', $barangay);
                $dependents = $members->flatMap->dependents;
    
                $row["member_$barangay"] = $members->count();
                $row["dependent_$barangay"] = $dependents->count();
            }
    
            $totalMembers = $group->count();
            $totalDependents = $group->flatMap->dependents->count();
    
            $row['member'] = $totalMembers;
            $row['dependent'] = $totalDependents;
            $row['total'] = $totalMembers + $totalDependents;
    
            $data[] = $row;
        }
    
        return response()->json([
            'barangays' => $barangays,
            'data' => $data
        ]);
    }    

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:members,id',
            'slp' => 'required|string|max:255',
        ]);

        $member = Member::find($request->id);
        $oldSlp = $member->slp;

        // Update all members with that old SLP name
        Member::where('slp', $oldSlp)->update(['slp' => $request->slp]);

        return redirect()->back()->with('success', 'SLP name updated successfully.');
    }

    public function getSlp(Request $request, $slp)
    {
        $members = Member::where('slp', $slp)->where('d2', '!=', '')->get();
        $dependents = Dependent::with('member') // <--- Eager load to avoid N+1
        ->join('members', 'dependents.member_id', '=', 'members.id')
        ->where('members.slp', $slp)
        ->where('dep_d2', '!=', '')
        ->select('dependents.*') // important to avoid overriding model instance
        ->get();
      
        $barangay = null;
        if ($members->isNotEmpty()) {
            // Get the barangay from members if any
            $barangay = $members->first()->barangay ?: $members->first()->brgy_d2;
        } elseif ($dependents->isNotEmpty()) {
            // Fallback if no members are found, get the barangay from dependents
            $barangay = $dependents->first()->member->barangay ?: $dependents->first()->member->brgy_d2;
        }
        $district = $request->district;

        return view('all-list-slpmember', compact('members', 'dependents', 'barangay', 'slp', 'district'));
    }
    
}
