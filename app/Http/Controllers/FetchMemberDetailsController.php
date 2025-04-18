<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Dependent;
use Illuminate\Http\Request;

class FetchMemberDetailsController extends Controller
{
    public function __construct()
    {
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', 0);
    }
    public function getSlpOptions(Request $request)
    {
        // Get the selected batch from the request
        $batch = $request->input('batch');
        
        // Fetch distinct SLP values based on the selected batch
        $slpGood = Member::where('batch', $batch)->distinct()->pluck('slp');
        
        // Return the view with the fetched SLP data
        return response()->json([
            'slpGood' => $slpGood
        ]);
    }
    public function getDependentsBygoodSlp(Request $request, $slp)
    {
        // Start the query for Members
        $membersQuery = Member::where('slp', $slp);
    
        // Apply filter for batch first
        if ($request->has('batch') && $request->batch) {
            $membersQuery->where('batch', $request->batch);
        }
    
        // Apply district and good/bad filters on members
        if ($request->has('district') && $request->district) {
            if ($request->district == 2) {
                // District 2 uses `d2` for good/bad
                if ($request->has('good_bad') && $request->good_bad === 'Good') {
                    // Good means members who have 'd2' not null
                    $membersQuery->whereNotNull('d2')->where('d2', '!=', '')
                    ->where('batch', $request->batch)->where('slp', $slp);
                } elseif ($request->has('good_bad') && $request->good_bad === 'Bad') {
                    // Bad means members who don't have 'd2'
                    $membersQuery->whereNull('d2')->orWhere('d2', '')
                    ->where('batch', $request->batch)->where('slp', $slp);
                }
            } elseif ($request->district == 1) {
                // District 1 uses `d1` for good/bad
                if ($request->has('good_bad') && $request->good_bad === 'Good') {
                    // Good means members who have 'd1' not null
                    $membersQuery->whereNotNull('d1')->where('d1', '!=', '')
                    ->where('batch', $request->batch)->where('slp', $slp);
                } elseif ($request->has('good_bad') && $request->good_bad === 'Bad') {
                    // Bad means members who don't have 'd1'
                    $membersQuery->whereNull('d1')->orWhere('d1', '')
                    ->where('batch', $request->batch)->where('slp', $slp);
                }
            }
        }
    
        // Get members based on the filters applied
        $members = $membersQuery->get();
    
        $dependentsQuery = Dependent::join('members', 'dependents.member_id', '=', 'members.id')
            ->where('members.slp', $slp);
    
        // Apply filter for batch on dependents
        if ($request->has('batch') && $request->batch) {
            $dependentsQuery->where('members.batch', $request->batch);
        }
        
        // Apply district and good/bad filters on dependents
        if ($request->has('district') && $request->district) {
            if ($request->district == 2) {
                // District 2 uses `dep_d2` for dependents' good/bad
                if ($request->has('good_bad') && $request->good_bad == 'Good') {
                    // Good means dependents who have 'dep_d2' not null
                    $dependentsQuery->whereNotNull('dependents.dep_d2')->where('dependents.dep_d2', '!=', '');
                } elseif ($request->has('good_bad') && $request->good_bad == 'Bad') {
                    // Bad means dependents who do not have 'dep_d2'
                    $dependentsQuery->whereNull('dependents.dep_d2')->where('dependents.dep_d2', '');
                }
            } elseif ($request->district == 1) {
                // District 1 uses `dep_d1` for dependents' good/bad
                if ($request->has('good_bad') && $request->good_bad == 'Good') {
                    // Good means dependents who have 'dep_d1' not null
                    $dependentsQuery->whereNotNull('dependents.dep_d1')->where('dependents.dep_d1', '!=', '');
                } elseif ($request->has('good_bad') && $request->good_bad == 'Bad') {
                    // Bad means dependents who do not have 'dep_d1'
                    $dependentsQuery->whereNull('dependents.dep_d1')->orWhere('dependents.dep_d1', '');
                }
            }
        }
        
        // Make sure the dependents belong to the correct SLP and batch
        $dependentsQuery->where('members.slp', $slp);
        
        if ($request->has('batch') && $request->batch) {
            $dependentsQuery->where('members.batch', $request->batch);
        }
        
        // Get dependents based on the filters applied
        $dependents = $dependentsQuery->get();
        // Determine the barangay based on the selected criteria
        $barangay = null;
        if ($members->isNotEmpty()) {
            // Get the barangay from members if any
            $barangay = $members->first()->barangay ?: $members->first()->brgy_d2;
        } elseif ($dependents->isNotEmpty()) {
            // Fallback if no members are found, get the barangay from dependents
            $barangay = $dependents->first()->member->barangay ?: $dependents->first()->member->brgy_d2;
        }
        $district = $request->district;

        // Return the view with the filtered data
        return view('good-slp-list', compact('members', 'dependents', 'barangay', 'slp', 'district'));
    }
    
    public function getAllDependents(Request $request)
{
    // Fetch all distinct 'slp' values
    $slps = Member::distinct()->pluck('slp');  
    
    $slpData = [];
    foreach ($slps as $slp) {
    
        // Base query for members, applying filters (batch, good/bad, and district)
        $membersQuery = Member::where('slp', $slp);
    
        // Apply batch filter if provided
        if ($request->has('batch') && $request->batch) {
            $membersQuery->where('batch', $request->batch);
        }
    
        // Apply good/bad filter for members based on the district
        if ($request->has('good_bad') && $request->good_bad === 'Good') {
            // If 'Good', check for non-null and non-empty values in the district field
            if ($request->district == 1) {
                $membersQuery->whereNotNull('d1')->where('d1', '!=', '')
                ->where('batch', $request->batch)->where('slp', $slp);
            } elseif ($request->district == 2) {
                $membersQuery->where(function($query) use ($request, $slp) {
                    // Apply good conditions for d2 if district is 2
                    if ($request->district == 2) {
                        $query->whereNotNull('d2')
                              ->where('d2', '!=', '');
                    }
                })
                ->where('batch', $request->batch)
                ->where('slp', $slp);
            }
        } elseif ($request->has('good_bad') && $request->good_bad === 'Bad') {
            // If 'Bad', check for null or empty values in the district field
            if ($request->district == 1) {
                $membersQuery->whereNull('d1')->orWhere('d1', '')
                ->where('batch', $request->batch)->where('slp', $slp);
            } elseif ($request->district == 2) {
                $membersQuery->whereNull('d2')->orWhere('d2', '')
                ->where('batch', $request->batch)->where('slp', $slp);
            }
        }
    
        $members = $membersQuery->get();
    
        // Base query for dependents, joining with members to filter by the same slp
        $dependentsQuery = Dependent::join('members', 'dependents.member_id', '=', 'members.id')
            ->where('members.slp', $slp);
    
        // Apply batch filter for dependents
        if ($request->has('batch') && $request->batch) {
            $dependentsQuery->where('members.batch', $request->batch);
        }
    
        // Apply good/bad filter for dependents based on the district
        if ($request->has('good_bad') && $request->good_bad == 'Good') {
            // If 'Good', check for non-null and non-empty values in the district field
            if ($request->district == 1) {
                $dependentsQuery->whereNotNull('dependents.dep_d1')->where('dependents.dep_d1', '!=', '');
            } elseif ($request->district == 2) {
                $dependentsQuery->whereNotNull('dependents.dep_d2')->where('dependents.dep_d2', '!=', '');
            }
        } elseif ($request->has('good_bad') && $request->good_bad == 'Bad') {
            // If 'Bad', check for null or empty values in the district field
            if ($request->district == 2) {
                $dependentsQuery->whereNull('dependents.dep_d2')->orWhere('dependents.dep_d2', '');
            } elseif ($request->district == 2) {
                $dependentsQuery->whereNull('dependents.dep_d1')->orWhere('dependents.dep_d1', '');
            }
        }
    
        // Apply district filter for dependents if provided
        if ($request->has('district') && $request->district) {
            // We already filter based on 'dep_d1' or 'dep_d2' above, so this may not be necessary
        }
    
        $dependents = $dependentsQuery->get();
    
        // Determine barangay for current 'slp'
        if ($members->isEmpty()) {
            $barangay = Member::where('slp', $slp)->value('barangay') ?: null;
        } else {
            $barangay = $members->first()->barangay ?: $members->first()->brgy_d2;
        }
        $district = $request->district;
        
        // Store results for this 'slp' if there are members or dependents
        if ($members->isNotEmpty() || $dependents->isNotEmpty()) {
            $slpData[] = [
                'slp' => $slp,
                'members' => $members,
                'barangay' => $barangay,
                'dependents' => $dependents,
                'district' => $district,
            ];
        }
    }
    
    // Return the results to the view
    return view('all-slp-list', compact('slpData'));
}


}
