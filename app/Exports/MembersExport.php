<?php

namespace App\Exports;

use App\Models\Member;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class MembersExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Member::with('dependents')->get(); // Eager load dependents
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

        // Prepare the first row with member data and first dependent
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

        // Check if there are any dependents
        if ($member->dependents->isNotEmpty()) {
            // Add first dependent data into the first row
            $firstDependent = $member->dependents->first();

            // Extend the first row with the first dependent details
            $firstRow[] = $firstDependent->dependents;
            $firstRow[] = $firstDependent->dep_age;
            $firstRow[] = $firstDependent->dep_d2;
            $firstRow[] = $firstDependent->dep_brgy_d2;
            $firstRow[] = $firstDependent->dep_d1;
            $firstRow[] = $firstDependent->dep_brgy_d1;

            // Add the first row to export data
            $exportData[] = $firstRow;

            // Loop through remaining dependents and add them in new rows
            $remainingDependents = $member->dependents->slice(1);
            foreach ($remainingDependents as $dependent) {
                $dependentData = [
                    $member->barangay,    // Keep the same barangay for dependents
                    $member->slp,         // Keep the same SLP for dependents
                    null,                 // MEMBER should be null for dependents
                    null,                 // AGE should be null for dependents
                    null,                 // GENDER should be null for dependents
                    null,                 // BIRTHDATE should be null for dependents
                    null,                 // SITIO/ZONE should be null for dependents
                    null,                 // CELLPHONE should be null for dependents
                    null,                 // D2 should be null for dependents
                    null,                 // BRGY D2 should be null for dependents
                    null,                 // D1 should be null for dependents
                    null,                 // BRGY D1 should be null for dependents
                    $dependent->dependents,
                    $dependent->dep_age,
                    $dependent->dep_d2,
                    $dependent->dep_brgy_d2,
                    $dependent->dep_d1,
                    $dependent->dep_brgy_d1,
                ];
                $exportData[] = $dependentData;  // Add each dependent in new row
            }
        } else {
            // If no dependents, just add the member data in the first row with null for dependent fields
            $firstRow[] = null; // DEPENDENT
            $firstRow[] = null; // DEP_AGE
            $firstRow[] = null; // DEP_D2
            $firstRow[] = null; // DEP_BRGY_D2
            $firstRow[] = null; // DEP_D1
            $firstRow[] = null; // DEP_BRGY_D1
            $exportData[] = $firstRow;
        }

        return $exportData;
    }
}
