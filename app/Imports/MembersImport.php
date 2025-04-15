<?php

namespace App\Imports;

use App\Models\Member;
use App\Models\Dependent;
use App\Models\Redun_dependent;
use App\Models\Redun_member;
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
            $normalizedMemberName = strtolower(trim($row['member']));
            $existingMember = Member::whereRaw('LOWER(member) = ?', [$normalizedMemberName])
            ->where('age', $age)
            ->first();

            if ($existingMember) {
                // 1. Save to Redun_member table
                Redun_member::create([
                    'member'      => $row['member'],
                    'barangay'    => $row['barangay'] ?? '',
                    'slp'         => $row['slp'] ?? '',
                    'age'         => $age ?? 0,
                    'gender'      => $row['gender'] ?? '',
                    'birthdate'   => $birthdate,
                    'sitio_zone'  => $row['sitio_zone'] ?? '',
                    'cellphone'   => $row['cellphone'] ?? '',
                    'd2'          => $row['d2'] ?? '',
                    'brgy_d2'     => $row['brgy_d2'] ?? '',
                    'd1'          => $row['d1'] ?? '',
                    'brgy_d1'     => $row['brgy_d1'] ?? '',
                    'batch'       => $this->batch,
                ]);
            
                // 2. Update only the fields that are null/empty in DB but provided in new data
                $fieldsToUpdate = [
                    'barangay'   => $row['barangay'] ?? null,
                    'slp'        => $row['slp'] ?? null,
                    'gender'     => $row['gender'] ?? null,
                    'birthdate'  => $birthdate ?? null,
                    'sitio_zone' => $row['sitio_zone'] ?? null,
                    'cellphone'  => $row['cellphone'] ?? null,
                    'd2'         => $row['d2'] ?? null,
                    'brgy_d2'    => $row['brgy_d2'] ?? null,
                    'd1'         => $row['d1'] ?? null,
                    'brgy_d1'    => $row['brgy_d1'] ?? null,
                ];
            
                $updated = false;
                foreach ($fieldsToUpdate as $field => $newValue) {
                    if (!empty($newValue) && empty($existingMember->$field)) {
                        $existingMember->$field = $newValue;
                        $updated = true;
                    }
                }
            
                if ($updated) {
                    $existingMember->save();
                    \Log::info("Updated existing member ID {$existingMember->id} with new non-empty fields.");
                }
            
                $lastSavedMemberId = $existingMember->id;
            }
            
            // Create the member
            $member = Member::firstOrCreate([
                'member' => $row['member'],
                'age' => $age ?? 0,
            ], [
                'barangay' => $row['barangay'] ?? '',
                'slp' => $row['slp'] ?? '',
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
            $depAge = '';
                    // Check if dependent data is valid (non-empty 'dependents' and valid 'dep_age')
            if (!empty($row['dependents']) && (!empty($row['dep_age']) || !empty($row['dep_d2']) || !empty($row['dep_brgy_d2']) || !empty($row['dep_d1']) || !empty($row['dep_brgy_d1']))) {
                // Set dep_age to NULL if it's empty
                $depAge = !empty($row['dep_age']) && is_numeric($row['dep_age']) ? (int)$row['dep_age'] : null;

                $existingDependent = Dependent::where('member_id', $lastSavedMemberId)
                    ->where('dependents', $row['dependents'])
                    ->first();
                
                // Insert the dependent record if data is valid
                $dependentName = trim($row['dependents']);
                $alreadyADependent = Dependent::whereRaw('LOWER(dependents) = ?', [strtolower($dependentName)])->exists();
                $alreadyAMember = Member::whereRaw('LOWER(member) = ?', [strtolower($dependentName)])->exists();

                    if (!$existingDependent && !$alreadyAMember && !$alreadyADependent) {
                        Dependent::create([
                            'member_id'    => $lastSavedMemberId,
                            'dependents'   => $dependentName,
                            'dep_age'      => $row['dep_age'],
                            'dep_d2'       => $row['dep_d2'] ?? '',
                            'dep_brgy_d2'  => $row['dep_brgy_d2'] ?? '',
                            'dep_d1'       => $row['dep_d1'] ?? '',
                            'dep_brgy_d1'  => $row['dep_brgy_d1'] ?? '',
                            'batch_belong'  => $this->batch,
                        ]);
                    } else {
                        // Save as redundant dependent
                        Redun_dependent::create([
                            'member_id'    => $lastSavedMemberId,
                            'dependents'   => $dependentName,
                            'dep_age'      => $row['dep_age'],
                            'dep_d2'       => $row['dep_d2'] ?? '',
                            'dep_brgy_d2'  => $row['dep_brgy_d2'] ?? '',
                            'dep_d1'       => $row['dep_d1'] ?? '',
                            'dep_brgy_d1'  => $row['dep_brgy_d1'] ?? '',
                            'batch_belong'  => $this->batch,
                        ]);

                        $existingDependent = Dependent::whereRaw('LOWER(dependents) = ?', [strtolower($dependentName)])
                            ->where('dep_age', $depAge)->first();
                
                        $fieldsToUpdate = [
                            'dep_age'      => $depAge,
                            'dep_d2'       => $row['dep_d2'] ?? '',
                            'dep_brgy_d2'  => $row['dep_brgy_d2'] ?? '',
                            'dep_d1'       => $row['dep_d1'] ?? '',
                            'dep_brgy_d1'  => $row['dep_brgy_d1'] ?? '',
                        ];
                    
                        $updated = false;
                        foreach ($fieldsToUpdate as $field => $newValue) {
                            if (!empty($newValue) && empty($existingDependent->$field)) {
                                $existingDependent->$field = $newValue;
                                $updated = true;
                            }
                        }
                    
                        if ($updated) {
                            $existingDependent->save();
                            \Log::info("Updated existing dependent ID {$existingDependent->id} with new non-empty fields.");
                        }
                    }
                
                
            }


            return $member;
        }

        // If the row contains only dependents (no valid member data), save as dependent for the last valid member_id
        if (!empty($row['dependents']) && $lastSavedMemberId !== null) {
          
            $existingDependent = Dependent::where('member_id', $lastSavedMemberId)
            ->where('dependents', $row['dependents'])
            ->first();
            // Save the dependent using the last valid member_id
            $dependentName = trim($row['dependents']);
            $alreadyADependent = Dependent::whereRaw('LOWER(dependents) = ?', [strtolower($dependentName)])->exists();
            $alreadyAMember = Member::whereRaw('LOWER(member) = ?', [strtolower($dependentName)])->exists();
            
            if (!$existingDependent && !$alreadyAMember && !$alreadyADependent) {
                Dependent::create([
                    'member_id'    => $lastSavedMemberId,
                    'dependents'   => $dependentName,
                    'dep_age'      => $row['dep_age'],
                    'dep_d2'       => $row['dep_d2'] ?? '',
                    'dep_brgy_d2'  => $row['dep_brgy_d2'] ?? '',
                    'dep_d1'       => $row['dep_d1'] ?? '',
                    'dep_brgy_d1'  => $row['dep_brgy_d1'] ?? '',
                    'batch_belong'  => $this->batch,
                ]);
            } else {
                // Save as redundant dependent
                Redun_dependent::create([
                    'member_id'    => $lastSavedMemberId,
                    'dependents'   => $dependentName,
                    'dep_age'      => $row['dep_age'],
                    'dep_d2'       => $row['dep_d2'] ?? '',
                    'dep_brgy_d2'  => $row['dep_brgy_d2'] ?? '',
                    'dep_d1'       => $row['dep_d1'] ?? '',
                    'dep_brgy_d1'  => $row['dep_brgy_d1'] ?? '',
                    'batch_belong'  => $this->batch ?? '',
                ]);
                $existingDependent = Dependent::whereRaw('LOWER(dependents) = ?', [strtolower($dependentName)])
                    ->where('dep_age', $depAge)->first();
        
                $fieldsToUpdate = [
                    'dep_age'      => $depAge,
                    'dep_d2'       => $row['dep_d2'] ?? '',
                    'dep_brgy_d2'  => $row['dep_brgy_d2'] ?? '',
                    'dep_d1'       => $row['dep_d1'] ?? '',
                    'dep_brgy_d1'  => $row['dep_brgy_d1'] ?? '',
                ];
            
                $updated = false;
                foreach ($fieldsToUpdate as $field => $newValue) {
                    if (!empty($newValue) && empty($existingDependent->$field)) {
                        $existingDependent->$field = $newValue;
                        $updated = true;
                    }
                }
            
                if ($updated) {
                    $existingDependent->save();
                    \Log::info("Updated existing dependent ID {$existingDependent->id} with new non-empty fields.");
                }
            }
        }

        return null; // Return null to avoid saving the row as a member if it only contains dependents
    }
}
