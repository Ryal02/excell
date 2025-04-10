<?php

namespace App\Imports;

use App\Models\Member;
use App\Models\Dependent;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Facades\Log;

class MembersImport implements ToModel, WithHeadingRow
{
    protected $batch;

    public function __construct($batch)
    {
        $this->batch = $batch;
    }

    public function model(array $row)
    {
        // Trim spaces from each value to avoid hidden characters
        $row = array_map('trim', $row);

        static $lastSavedMemberId = null;

        // Check if the row contains valid member data (name, age, etc.)
        if (!empty($row['member'])) {
            // Valid member data: save the member
            // Handle birthdate field
            $birthdate = $row['birthdate'] ?? null;
            if (!empty($birthdate)) {
                if (is_numeric($birthdate)) {
                    try {
                        $birthdateObj = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($birthdate);
                        if ($birthdateObj->getTimestamp() === false || $birthdateObj->format('Y-m-d') == '0000-00-00') {
                            $birthdate = null;
                        } else {
                            $birthdate = $birthdateObj->format('Y-m-d');
                        }
                    } catch (\Exception $e) {
                        $birthdate = null;
                    }
                } else {
                    $birthdate = null;
                }
            } else {
                $birthdate = null;
            }

            // Handle age field
            $age = null;
            if (!empty($row['age']) && is_numeric($row['age'])) {
                $age = (int) $row['age'];
            }

            // Create the member
            $member = Member::firstOrCreate([
                'member' => $row['member'],
            ], [
                'barangay' => $row['barangay'] ?? '',
                'slp' => $row['slp'] ?? '',
                'age' => $age,
                'gender' => $row['gender'] ?? '',
                'birthdate' => $birthdate,
                'sitio_zone' => $row['sitio_zone'] ?? '',
                'cellphone' => $row['cellphone'] ?? '',
                'd2' => $row['d2'] ?? '',
                'brgy_d2' => $row['brgy_d2'] ?? '',
                'd1' => $row['d1'] ?? '',
                'brgy_d1' => $row['brgy_d1'] ?? '',
                'batch' => $this->batch,
            ]);

            // Store the member_id of the last saved member
            $lastSavedMemberId = $member->id;
                    // Check if dependent data is valid (non-empty 'dependents' and valid 'dep_age')
            if (!empty($row['dependents']) && (!empty($row['dep_age']) || !empty($row['dep_d2']) || !empty($row['dep_brgy_d2']) || !empty($row['dep_d1']) || !empty($row['dep_brgy_d1']))) {
                // Set dep_age to NULL if it's empty
                $depAge = !empty($row['dep_age']) && is_numeric($row['dep_age']) ? (int)$row['dep_age'] : null;

                // Log the valid dependent data for debugging
                Log::info('Saving Dependent for Member ID ' . $lastSavedMemberId . ': ' . $row['dependents']);
                $existingDependent = Dependent::where('member_id', $lastSavedMemberId)
                    ->where('dependents', $row['dependents'])
                    ->first();
                if (!$existingDependent) {
                // Insert the dependent record if data is valid
                    Dependent::create([
                        'member_id'    => $lastSavedMemberId,
                        'dependents'   => $row['dependents'],
                        'dep_age'      => $depAge, // Set dep_age to NULL if it's empty
                        'dep_d2'       => $row['dep_d2'] ?? '',
                        'dep_brgy_d2'  => $row['dep_brgy_d2'] ?? '',
                        'dep_d1'       => $row['dep_d1'] ?? '',
                        'dep_brgy_d1'  => $row['dep_brgy_d1'] ?? '',
                    ]);
                }
            }


            return $member;
        }

        // If the row contains only dependents (no valid member data), save as dependent for the last valid member_id
        if (!empty($row['dependents']) && $lastSavedMemberId !== null) {
            // Log each dependent to verify
            Log::info('Saving Dependent for Member ID ' . $lastSavedMemberId . ': ' . $row['dependents']);
            $existingDependent = Dependent::where('member_id', $lastSavedMemberId)
            ->where('dependents', $row['dependents'])
            ->first();
            // Save the dependent using the last valid member_id
            if (!$existingDependent) {
                Dependent::create([
                    'member_id'    => $lastSavedMemberId,
                    'dependents'   => $row['dependents'], // Save the dependent's name
                    'dep_age'      => $row['dep_age'] ?? '',
                    'dep_d2'       => $row['dep_d2'] ?? '',
                    'dep_brgy_d2'  => $row['dep_brgy_d2'] ?? '',
                    'dep_d1'       => $row['dep_d1'] ?? '',
                    'dep_brgy_d1'  => $row['dep_brgy_d1'] ?? '',
                ]);
            }
        }

        return null; // Return null to avoid saving the row as a member if it only contains dependents
    }
}
