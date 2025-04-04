<?php

namespace App\Imports;

use App\Models\Member;
use App\Models\Dependent;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class MembersImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Create the member
        $birthdate = $row['birthdate'];

        // If birthdate is not empty or null, process it
        if (!empty($birthdate)) {
            // Check if the birthdate is numeric (Excel date timestamp)
            if (is_numeric($birthdate)) {
                try {
                    // Convert Excel date to DateTime object
                    $birthdateObj = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($birthdate);
    
                    // If the date is out of range or invalid, treat it as null
                    if ($birthdateObj->getTimestamp() === false || $birthdateObj->format('Y-m-d') == '0000-00-00') {
                        $birthdate = null; // Invalid date, set as null
                    } else {
                        // Format as 'Y-m-d' for database insertion
                        $birthdate = $birthdateObj->format('Y-m-d');
                    }
                } catch (\Exception $e) {
                    // If conversion fails, set date to null
                    $birthdate = null;
                }
            } else {
                // If it's not numeric (already a string), set it to null
                $birthdate = null;
            }
        } else {
            // If no birthdate is provided (null or empty), set it as null
            $birthdate = null;
        }


        $member = Member::create([
            'barangay'    => $row['barangay'],
            'slp'         => $row['slp'],
            'member'      => $row['agusan_member'],
            'age'         => $row['age'],
            'gender'      => $row['gender'],
            'birthdate'   => $birthdate, // Use the validated birthdate
            'sitio_zone'  => $row['sitio_zone'],
            'cellphone'   => $row['cellphone'],
            'd2'          => $row['d2'],
            'brgy_2'      => $row['barangay'],
            'd1'          => $row['d1'],
            'brgy_1'      => $row['brgy_2'],
        ]);

        // Check if dependents exist, and insert them if needed
        if (!empty($row['dependent_name'])) {
            Dependent::create([
                'member_id'         => $member->id,
                'dependent_name'    => $row['dependent_name'],
                'dependent_age'     => $row['dependent_age'],
                'dependent_d2'      => $row['dependent_d2'],
                'dependent_brgy_d2' => $row['dependent_brgy_d2'],
                'dependent_d1'      => $row['dependent_d1'],
                'dependent_brgy_d1' => $row['dependent_brgy_d1'],
            ]);
        }

        return $member;
    }
}
