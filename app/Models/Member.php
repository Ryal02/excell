<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'barangay', 'slp', 'member', 'age', 'gender', 'birthdate', 'sitio_zone', 'cellphone',
        'd2', 'brgy_d2', 'd1', 'brgy_d1', 'batch'
    ];

    public function dependents()
    {
        return $this->hasMany(Dependent::class);
    }
}
