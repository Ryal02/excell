<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Redun_member;
use App\Models\Redun_dependent;

class RedundantController extends Controller
{
    public function index()
    {
        // Get all unique batches
        $batches = Redun_member::select('batch')->distinct()->pluck('batch');

        return view('redundant.index', compact('batches'));
    }

    public function showBatch($batch)
    {
        $members = Redun_member::where('batch', $batch)->get();

        // Get dependents grouped by member_id
        $dependents = Redun_dependent::where('batch_belong', $batch)->get();

        return view('redundant.batch', compact('members', 'dependents', 'batch'));
    }
    public function showAll()
    {
        $members = Redun_member::all();
        $dependents = Redun_dependent::all();
        $batch = 'All';
    
        return view('redundant.batch', compact('members', 'dependents', 'batch'));
    }
}

