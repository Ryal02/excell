<?php

namespace App\Exports;

use App\Models\Member;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class MembersBatchExport implements FromCollection, WithHeadings, WithMapping
{
    protected $batch;

    public function __construct($batch)
    {
        $this->batch = $batch;
    }

    public function collection()
    {
        return Member::with('dependents')
            ->where('batch', $this->batch)
            ->get();
    }

    public function headings(): array
    {
        return [
            'BARANGAY', 'SLP', 'MEMBER', 'AGE', 'GENDER', 'BIRTHDATE', 'SITIO/ZONE', 'CELLPHONE', 'D2', 'BRGY D2', 'D1', 'BRGY D1', 
            'DEPENDENT', 'DEP_AGE', 'DEP_D2', 'DEP_BRGY_D2', 'DEP_D1', 'DEP_BRGY_D1'
        ];
    }

    public function map($member): array
    {
        $exportData = [];

        $firstRow = [
            $member->barangay,
            $member->slp,
            $member->member,
            $member->age,
            $member->gender,
            $member->birthdate,
            $member->sitio_zone,
            $member->cellphone,
            $member->d2,
            $member->brgy_d2,
            $member->d1,
            $member->brgy_d1,
        ];

        if ($member->dependents->isNotEmpty()) {
            $firstDependent = $member->dependents->first();
            $firstRow[] = $firstDependent->dependents;
            $firstRow[] = $firstDependent->dep_age;
            $firstRow[] = $firstDependent->dep_d2;
            $firstRow[] = $firstDependent->dep_brgy_d2;
            $firstRow[] = $firstDependent->dep_d1;
            $firstRow[] = $firstDependent->dep_brgy_d1;
            $exportData[] = $firstRow;

            foreach ($member->dependents->slice(1) as $dependent) {
                $exportData[] = [
                    $member->barangay,
                    $member->slp,
                    null, null, null, null, null, null, null, null, null, null,
                    $dependent->dependents,
                    $dependent->dep_age,
                    $dependent->dep_d2,
                    $dependent->dep_brgy_d2,
                    $dependent->dep_d1,
                    $dependent->dep_brgy_d1,
                ];
            }
        } else {
            $firstRow = array_merge($firstRow, array_fill(0, 6, null));
            $exportData[] = $firstRow;
        }

        return $exportData;
    }
}
