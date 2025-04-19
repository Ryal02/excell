<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;

class EditMemberController extends Controller
{
    public function edit($id)
    {
        $member = Member::with('dependents')->findOrFail($id);
        return response()->json($member);
    }
    
    public function update(Request $request)
    {
        $member = Member::findOrFail($request->id);
    
        $member->update($request->only([
            'member', 'barangay', 'slp', 'age', 'gender',
            'birthdate', 'cellphone'
        ]));
    
        if ($request->has('dependents')) {
            $member->dependents()->delete();
            foreach ($request->dependents as $dep) {
                $member->dependents()->create($dep);
            }
        }
    
        return redirect()->route('dashboard')->with('success', 'Member updated successfully.');
    }
}
