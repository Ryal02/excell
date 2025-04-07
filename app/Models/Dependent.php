<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dependent extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id', 'dependents', 'dep_age', 'dep_d2', 'dep_brgy_d2',
        'dep_d1', 'dep_brgy_d1'
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
