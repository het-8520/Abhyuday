<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LongForm; // Assuming your model is named LongForm
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log; // Corrected: Use Laravel's Log facade

class LongFormController extends Controller
{
    /**
     * Display the patient case taking form.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('long-form'); // Assuming your blade file is resources/views/long-form.blade.php
    }

    /**
     * Store a newly created patient record in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            // 1. Validate the incoming request data
            $validatedData = $request->validate([
                // Step 1: Patient Information
                'name' => 'required|string|max:255',
                'age' => 'nullable|integer|min:0',
                'sex' => ['required', 'string', Rule::in(['MALE', 'FEMALE', 'OTHER'])],
                'address' => 'nullable|string',
                'occupation' => 'nullable|string|max:255',
                'religion' => 'nullable|string|max:255',
                'maritalStatus' => ['required', 'string', Rule::in(['single', 'married', 'divorced', 'widow', 'live_in'])],
                'socioEconomicStatus' => 'nullable|string|max:255',
                'dateOfConsultation' => 'nullable|date',
                'outpatientNumber' => 'nullable|string|max:255|unique:patients,outpatient_number', // Adjusted table name
                'inpatientNumber' => 'nullable|string|max:255|unique:patients,inpatient_number', // Adjusted table name

                // Step 2: Known case of and Chief Complaint with duration
                'knownCaseOf' => 'nullable|string',
                'chiefComplaint' => 'nullable|array',
                'chiefComplaint.*.complaint' => 'nullable|string|max:100',
                'chiefComplaint.*.duration' => 'nullable|string|max:255',

                // Step 3: History of Illness
                'historyOfPresentIllness' => 'nullable|string',
                'historyOfPastIllness' => 'nullable|string',

                // Step 4: Drug, Surgical, Allergic, Family, and Social History
                'drugHistory' => 'nullable|string',
                'surgicalHistory' => 'nullable|string',
                'allergicHistory' => 'nullable|string',
                'familyHistory' => 'nullable|string',
                'alcoholIntake' => ['nullable', 'string', Rule::in(['Yes', 'No'])],
                'alcoholIntakeDetails' => 'nullable|string',
                'tobaccoUse' => ['nullable', 'string', Rule::in(['Yes', 'No'])],
                'tobaccoUseDetails' => 'nullable|string',
                'travelHistory' => ['nullable', 'string', Rule::in(['Yes', 'No'])],
                'travelHistoryDetails' => 'nullable|string',
                'employmentHistory' => 'nullable|string|max:250',
                'homeSituation' => 'nullable|string|max:250',
                'furtherSocialHistory' => 'nullable|string|max:250',

                // Step 5: Personal History
                'diet' => ['required', 'string', Rule::in(['Vegetarian', 'Non-vegetarian', 'Mixed', 'Vegan'])],
                'breakfast' => 'nullable|string|max:255',
                'lunch' => 'nullable|string|max:255',
                'midAfternoon' => 'nullable|string|max:255',
                'dinner' => 'nullable|string|max:255',
                'bowelFrequency' => ['required', 'string', Rule::in(['1', '2', '3'])],
                'bowelConsistency' => ['required', 'string', Rule::in(['Hard', 'Semisolid', 'Liquid'])],
                'bowelSatisfactory' => ['required', 'string', Rule::in(['Satisfactory', 'Unsatisfactory'])],
                'bowelBloodMucus' => 'nullable|boolean',
                'bowelStraining' => 'nullable|boolean',
                'bowelChange' => 'nullable|boolean',
                'bladderDayNightFrequency' => 'nullable|string|max:255',
                'bladderWait' => 'nullable|boolean',
                'bladderWaitDetails' => 'nullable|string',
                'bladderIncreasedFrequency' => 'nullable|boolean',
                'bladderIncreasedFrequencyDetails' => 'nullable|string',
                'sleepDurationPattern' => 'nullable|string|max:255',
                'hobbies' => 'nullable|string|max:255',
                'jobSatisfaction' => ['nullable', 'string', Rule::in(['Satisfied', 'Neutral', 'Dissatisfied', 'N/A'])],
                'handedness' => ['nullable', 'string', Rule::in(['Lefty', 'Righty', 'Ambidextrous'])],

                // Step 6: Menstrual & Obstetric History
                'fmp' => 'nullable|string|max:255',
                'menstrualDuration' => 'nullable|string|max:255',
                'menstrualInterval' => 'nullable|string|max:255',
                'menstrualRegularity' => ['nullable', 'string', Rule::in(['regular', 'irregular', 'regularly-irregular', 'irregularly-regular'])],
                'menstrualPain' => ['nullable', 'string', Rule::in(['painful', 'painless'])],
                'menstrualPainLevel' => 'nullable|array',
                'menstrualFlow' => ['nullable', 'string', Rule::in(['scanty', 'moderate', 'excessive'])],
                'lmp' => 'nullable|date',
                'obstetricHistory' => 'nullable|array',
                'obstetricHistory.gravida' => 'nullable|integer|min:0',
                'obstetricHistory.para' => 'nullable|integer|min:0',
                'obstetricHistory.abortions' => 'nullable|integer|min:0',
                'obstetricHistory.living' => 'nullable|integer|min:0',
                'obstetricHistory.deaths' => 'nullable|integer|min:0',

                // Step 7, 8, 9, 10: General Physical Examination, Systemic Examination, Investigations, Diagnosis
                'bp' => 'nullable|string|max:255',
                'pulse' => 'nullable|string|max:255',
                'respiratoryRate' => 'nullable|string|max:255',
                'temperature' => 'nullable|string|max:255',
                'postureGait' => 'nullable|string|max:255',
                'builtNourishment' => 'nullable|string|max:255',
                'consciousnessOrientation' => 'nullable|string|max:255',
                'pallor' => 'nullable|string|max:255',
                'icterus' => 'nullable|string|max:255',
                'cyanosis' => 'nullable|string|max:255',
                'clubbing' => 'nullable|string|max:255',
                'lymphadenopathy' => 'nullable|string|max:255',
                'edema' => 'nullable|string|max:255',
                'dehydration' => 'nullable|string|max:255',
                'height' => 'nullable|string|max:255',
                'weight' => 'nullable|string|max:255',
                'systemicExamination' => 'nullable|string',
                'investigations' => 'nullable|string',
                'diagnosis' => 'nullable|string',

                // Step 11: Treatment
                'medicines' => 'nullable|array',
                'medicines.*.category' => 'nullable|string|max:255',
                'medicines.*.name' => 'nullable|string|max:255',
                'medicines.*.dose' => 'nullable|string|max:255',
                'medicines.*.anupaan' => 'nullable|string|max:255',
                'upashay' => 'nullable|string|max:255',
                'anupashay' => 'nullable|string',
            ]);

            // 2. Prepare data for storage, converting camelCase to snake_case and handling JSON fields
            $patientData = [];
            foreach ($validatedData as $key => $value) {
                $snakeCaseKey = Str::snake($key);
                $patientData[$snakeCaseKey] = $value;
            }

            // Handle boolean fields from 'Yes'/'No' radio buttons
            $patientData['bowel_blood_mucus'] = $request->input('bowelBloodMucus') === 'Yes';
            $patientData['bowel_straining'] = $request->input('bowelStraining') === 'Yes';
            $patientData['bowel_change'] = $request->input('bowelChange') === 'Yes';
            $patientData['bladder_wait'] = $request->input('bladderWait') === 'Yes';
            $patientData['bladder_increased_frequency'] = $request->input('bladderIncreasedFrequency') === 'Yes';

            // Ensure JSON fields are properly assigned as arrays/objects
            if (isset($validatedData['chiefComplaint'])) {
                $patientData['chief_complaints'] = $validatedData['chiefComplaint'];
            }
            if (isset($validatedData['menstrualPainLevel'])) {
                $patientData['menstrual_pain_level'] = $validatedData['menstrualPainLevel'];
            }
            if (isset($validatedData['obstetricHistory'])) {
                $patientData['obstetric_history'] = $validatedData['obstetricHistory'];
            }
            if (isset($validatedData['medicines'])) {
                $patientData['medicines'] = $validatedData['medicines'];
            }

            // 3. Create a new Patient record
            // Assuming your model is LongForm, not Patient
            LongForm::create($patientData);

            // 4. Return a success JSON response
            return response()->json(['message' => 'Patient record saved successfully!'], 200);

        } catch (ValidationException $e) {
            // Return validation errors as JSON
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422); // 422 Unprocessable Entity
        } catch (\Exception $e) {
            // Log the general error
            Log::error('Error saving patient record: ' . $e->getMessage() . ' Stack: ' . $e->getTraceAsString());
            // Return a generic error message as JSON
            return response()->json(['message' => 'An internal server error occurred. Please try again later.'], 500);
        }
    }
}
