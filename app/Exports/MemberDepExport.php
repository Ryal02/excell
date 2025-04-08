<?php

namespace App\Exports;

use App\Models\Member;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MemberDepExport implements FromCollection, WithHeadings
{
    public function export(Request $request)
    {
        // Decode the data passed from frontend
        $data = json_decode($request->input('data'), true);

        // Return the data as an Excel download
        return Excel::download(new class($data) implements FromCollection, WithHeadings {
            private $data;

            public function __construct($data)
            {
                $this->data = $data;
            }

            public function collection()
            {
                return collect($this->data);
            }

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
        }, 'visible_members.xlsx');
    }
}
