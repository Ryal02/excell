<?php

namespace App\Exports;

use App\Models\Member;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SlpExport implements FromCollection, WithHeadings, WithMapping
{
    
    public function collection()
    {
        return Member::select('slp')->distinct()->get();
    }

    public function headings(): array
    {
        return [
            'SLP'
        ];
    }

    public function map($member): array
    {
        return [
            $member->slp,
        ];
    }
}
