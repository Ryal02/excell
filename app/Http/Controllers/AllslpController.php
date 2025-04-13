<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Dependent;

class AllslpController extends Controller
{
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
    
            // Init barangay counts
            foreach ($barangays as $barangay) {
                $memberCount = $group->where('barangay', $barangay)->count();
                $dependentCount = $group->filter(function ($m) use ($barangay) {
                    return $m->barangay === $barangay;
                })->flatMap->dependents->count();
    
                $row[$barangay] = $memberCount + $dependentCount;
            }
    
            // Total stats
            $totalMembers = $group->count();
            $totalDependents = $group->flatMap->dependents->count();
    
            $row['member'] = $totalMembers;
            $row['dependent'] = $totalDependents;
            $row['total'] = $totalMembers + $totalDependents;
    
            $data[] = $row;
        }
    
        return response()->json([
            'columns' => $barangays,
            'data' => $data
        ]);
    }
    
    
}
