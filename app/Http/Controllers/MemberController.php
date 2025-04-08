<?php

// app/Http/Controllers/MemberController.php
namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Dependent;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        // Start a query for members, eager load dependents
        $query = Member::with('dependents');
        $search = $request->get('search', ''); // Default to an empty string if no search term is provided


        // Apply the search filter if the search query is present
        if ($search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                // Filter by multiple columns in the members table
                $q->where('barangay', 'like', "%$search%")
                  ->orWhere('slp', 'like', "%$search%")
                  ->orWhere('member', 'like', "%$search%")
                  ->orWhere('age', 'like', "%$search%")
                  ->orWhere('gender', 'like', "%$search%")
                  ->orWhere('birthdate', 'like', "%$search%")
                  ->orWhere('sitio_zone', 'like', "%$search%")
                  ->orWhere('cellphone', 'like', "%$search%")
                  ->orWhere('d2', 'like', "%$search%")
                  ->orWhere('brgy_d2', 'like', "%$search%")
                  ->orWhere('d1', 'like', "%$search%")
                  ->orWhere('brgy_d1', 'like', "%$search%")
                  ->orWhereHas('dependents', function($q) use ($search) {
                      // Filter by dependent columns
                      $q->where('dependents', 'like', "%$search%")
                        ->orWhere('dep_age', 'like', "%$search%")
                        ->orWhere('dep_d2', 'like', "%$search%")
                        ->orWhere('dep_brgy_d2', 'like', "%$search%")
                        ->orWhere('dep_d1', 'like', "%$search%")
                        ->orWhere('dep_brgy_d1', 'like', "%$search%");
                  });
            });
        }

        // Paginate the filtered members (you can adjust the number of items per page as needed)
        $members = $query->paginate(10);
        foreach ($members as $member) {
            // Remove duplicates by using the unique method on the dependents collection
            $member->dependents = $member->dependents->unique('dependents');
        }
        $slpmembers = Member::all();
        // Pass the search term to the view to highlight matching results
        return view('import', compact('members', 'search', 'slpmembers'));
    }

    public function store(Request $request)
    {
        // You can remove the `required` validation for optional fields
        $validated = $request->validate([
            'barangay' => 'nullable|string|max:255',
            'slp' => 'nullable|string|max:255',
            'member' => 'nullable|string|max:255',
            'age' => 'nullable|integer',
            'gender' => 'nullable|string|max:10',
            'birthdate' => 'nullable|date',
            'sitio_zone' => 'nullable|string|max:255',
            'cellphone' => 'nullable|string|max:15',
            'd2' => 'nullable|string|max:255',
            'brgy_d2' => 'nullable|string|max:255',
            'd1' => 'nullable|string|max:255',
            'brgy_d1' => 'nullable|string|max:255',
            // Dependents is now nullable, meaning it’s optional and can be an empty array
            'dependents' => 'nullable|array',
            'dependents.*.name' => 'nullable|string|max:255',
            'dependents.*.age' => 'nullable|integer',
            'dependents.*.d2' => 'nullable|string|max:255',
            'dependents.*.brgy_d2' => 'nullable|string|max:255',
            'dependents.*.d1' => 'nullable|string|max:255',
            'dependents.*.brgy_d1' => 'nullable|string|max:255',
        ]);
    
        // Now, you can handle storing the member data without worrying about validation failures for empty fields
        $member = new Member($validated); // Assuming you have a Member model
        $member->save();
    
        // Handle saving dependents, but only if they exist
        if ($request->has('dependents')) {
            foreach ($request->dependents as $dependentData) {
                $dependent = new Dependent($dependentData);
                $member->dependents()->save($dependent);
            }
        }
    
        // Send a success response with a message
        return response()->json(['message' => 'Member saved successfully!']);
    }
    public function edit($id)
    {
        $member = Member::findOrFail($id);
        
        // Return JSON response
        return response()->json([
            'success' => true,
            'member' => $member,
        ]);
    }
    
    

    public function update(Request $request, $id)
    {
        // Find the member by ID
        $member = Member::find($id);
        
        if (!$member) {
            return response()->json(['message' => 'Member not found'], 404);
        }

        // Validate the request data
        $validated = $request->validate([
            'barangay' => 'nullable|string|max:255',
            'slp' => 'nullable|string|max:255',
            'member' => 'nullable|string|max:255',
            'age' => 'nullable|integer',
            'gender' => 'nullable|string|max:10',
            'birthdate' => 'nullable|date',
            'sitio_zone' => 'nullable|string|max:255',
            'cellphone' => 'nullable|string|max:15',
            'd2' => 'nullable|string|max:255',
            'brgy_d2' => 'nullable|string|max:255',
            'd1' => 'nullable|string|max:255',
            'brgy_d1' => 'nullable|string|max:255',
            // Same validation for dependents
            'dependents' => 'nullable|array',
            'dependents.*.name' => 'nullable|string|max:255',
            'dependents.*.age' => 'nullable|integer',
            'dependents.*.d2' => 'nullable|string|max:255',
            'dependents.*.brgy_d2' => 'nullable|string|max:255',
            'dependents.*.d1' => 'nullable|string|max:255',
            'dependents.*.brgy_d1' => 'nullable|string|max:255',
        ]);

        // Update the member with validated data
        $member->update($validated);

        // Update the dependents if they exist
        if ($request->has('dependents')) {
            // Delete existing dependents
            $member->dependents()->delete();
            
            // Save new dependents
            foreach ($request->dependents as $dependentData) {
                $dependent = new Dependent($dependentData);
                $member->dependents()->save($dependent);
            }
        }

        // Return a success message
        return response()->json(['message' => 'Member updated successfully!']);
    }

    public function viewListing(Request $request)
    {
        // Fetch all distinct barangays
        $barangays = Member::select('brgy_d2')
            ->whereNotNull('brgy_d2')  // Exclude NULL values
            ->where('brgy_d2', '!=', '')  // Exclude empty strings
            ->distinct()->get();
        // Initialize an array to store the data
        $listingData = [];
    
        foreach ($barangays as $barangay) {
            $brgyD2 = $barangay->brgy_d2;
            // Get count of total members in the barangay
            $D2GoodMembers = Member::where('brgy_d2', $barangay->brgy_d2)
                ->whereNotNull('d2')
                ->where('d2', '!=', '')  // Also exclude empty strings
                ->count();

            $D2BadMembers = Member::where('barangay', $barangay->brgy_d2)
                ->where(function ($query) {
                    $query->whereNull('d2')  // Ensure we're checking for NULL specifically
                        ->orWhere('d2', ''); // Also include empty string if applicable
                })
                ->count();

            $D2Gooddependent = Dependent::whereHas('member', function ($query) use ($barangay) {
                    $query->where('brgy_d2', $barangay->brgy_d2);
                })
                ->whereNotNull('dep_d2')
                ->distinct('dependents')
                ->where('dep_d2', '!=', '') // If you want distinct dependent names
                ->count();
                
                // Counting dependents without dep_d2
            $D2Baddependent = Dependent::whereHas('member', function ($query) use ($barangay) {
                    $query->where('brgy_d2', $barangay->brgy_d2);
                })
                ->where(function ($query) {
                    // Check for dep_d2 being either NULL or an empty string
                    $query->whereNull('dep_d2')
                          ->orWhere('dep_d2', '');
                })
                ->count();

            $memberdistrict1 = Member::where('barangay', $brgyD2)
                ->whereNotNull('d1')
                ->where('d1', '!=', '')
                ->count();
    
            // Get count of members who have d2
            $dependentdistrict1 = Dependent::whereHas('member', function ($query) use ($brgyD2) {
                    $query->where('brgy_d2', $brgyD2);
                })
                ->whereNotNull('dep_d1')
                ->distinct('dependents')
                ->where('dep_d1', '!=', '') // If you want distinct dependent names
                ->count();
                
            $totalmember = Member::count();
            $totalDependent = Dependent::count();
    
            // Store the data for each barangay
            $listingData[] = [
                'barangay' => $barangay->brgy_d2,
                'D2_good_member' => $D2GoodMembers,
                'D2_good_dependent' => $D2Gooddependent,
                'D2_bad_member' => $D2BadMembers,
                'D2_bad_dependent' => $D2Baddependent,
                'member_district1' => $memberdistrict1,
                'dependent_distric1' => $dependentdistrict1,
                'total_member' => $totalmember,
                'total_dependent' => $totalDependent,
            ];
        }
    
        // Return the view with the listing data
        return view('listing', compact('listingData'));
    }
    
    //SLP
    public function getDependentsBySlp($slp)
    {
        // Fetch all members with the given 'slp'
        $members = Member::where('slp', $slp)->with('dependents')->get();
        $barangay = $members->first()->barangay; // Adjust the 'barangay' attribute if needed

        // Return a view to display the dependents
        return view('slp-list', compact('members', 'barangay', 'slp'));
    }
}
