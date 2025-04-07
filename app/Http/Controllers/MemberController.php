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

        // Pass the search term to the view to highlight matching results
        return view('import', compact('members', 'search'));
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

    public function viewListing(Request $request)
    {
        // Fetch all distinct barangays
        $barangays = Member::select('barangay')->distinct()->get();
    
        // Initialize an array to store the data
        $listingData = [];
    
        foreach ($barangays as $barangay) {
            // Get count of total members in the barangay
            $totalMembers = Member::where('barangay', $barangay->barangay)->count();
    
            // Get count of members who have d1
            $district1 = Member::where('barangay', $barangay->barangay)
                                ->whereNotNull('d1')
                                ->count();
    
            // Get count of members who do not have d1
            $district1_bad = $totalMembers - $district1;
    
            // Get count of members who have d2
            $district2 = Member::where('barangay', $barangay->barangay)
                                ->whereNotNull('d2')
                                ->count();
    
            // Get count of members who do not have d2
            $district2_bad = $totalMembers - $district2;
    
            // Store the data for each barangay
            $listingData[] = [
                'barangay' => $barangay->barangay,
                'total_members' => $totalMembers,
                'district1_good' => $district1,
                'district1_bad' => $district1_bad,
                'district2_good' => $district2,
                'district2_bad' => $district2_bad,
            ];
        }
    
        // Return the view with the listing data
        return view('listing', compact('listingData'));
    }
    

}
