<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class MemberDepExport implements FromCollection, WithHeadings
{
    private $data;

    // Constructor to accept data passed from the route or request
    public function __construct($data)
    {
        $this->data = $data;
    }

    // Implement the collection() method to return the data as a collection
    public function collection()
    {
        // Return the data as a collection
        return collect($this->data);
    }

    // Implement the headings() method to return the column headers
    public function headings(): array
    {
        return [
            'Member Name',
            'Birthdate',
            'Zone/Sitio',
            'Cellphone',
            'Dependent Name',
            'Dependent Age',
            'Dependent Cellphone',
            'Dependent Barangay',
        ];
    }
}
