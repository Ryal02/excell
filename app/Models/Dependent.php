<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dependent extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id', 'dependent_name', 'dependent_age', 'dependent_d2', 'dependent_brgy_d2',
        'dependent_d1', 'dependent_brgy_d1'
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
