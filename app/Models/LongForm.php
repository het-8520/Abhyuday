<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LongForm extends Model
{
    protected $table = 'patients'; // Specify the table name if different from the default
    protected $primaryKey = 'id'; // Specify the primary key if different from the default
    public $timestamps = true; // Enable timestamps if your table has created_at and updated_at columns

    // Define the casts for attributes that should be automatically converted
    protected $casts = [
        'chief_complaints' => 'array',
        'history_of_present_illness' => 'string', // Assuming these are text fields
        'history_of_past_illness' => 'string',
        'drug_history' => 'string',
        'surgical_history' => 'string',
        'allergic_history' => 'string',
        'family_history' => 'string',
        'travel_history' => 'string',
        'employment_history' => 'string',
        'home_situation' => 'string',
        'further_social_history' => 'string',
        'diet' => 'string',
        'menstrual_pain_level' => 'array', // This needs to be cast as array
        'obstetric_history' => 'array', // This needs to be cast as array
        'medicines' => 'array', // This needs to be cast as array
        'bowel_blood_mucus' => 'boolean',
        'bowel_straining' => 'boolean',
        'bowel_change' => 'boolean',
        'bladder_wait' => 'boolean',
        'bladder_increased_frequency' => 'boolean',
        'date_of_consultation' => 'date', // Cast date fields to date objects
        'lmp' => 'date', // Cast date fields to date objects
    ];

    protected $fillable = [
        'name', 'age', 'sex', 'address', 'occupation', 'religion', 'marital_status',
        'socio_economic_status', 'date_of_consultation', 'outpatient_number', 'inpatient_number',
        'known_case_of', 'chief_complaints', 'history_of_present_illness', 'history_of_past_illness',
        'drug_history', 'surgical_history', 'allergic_history', 'family_history', 'alcohol_intake',
        'alcohol_intake_details', 'tobacco_use', 'tobacco_use_details', 'travel_history',
        'travel_history_details', 'employment_history', 'home_situation', 'further_social_history',
        'diet', 'breakfast', 'lunch', 'mid_afternoon', 'dinner', 'bowel_frequency', 'bowel_consistency',
        'bowel_satisfactory', 'bowel_blood_mucus', 'bowel_straining', 'bowel_change',
        'bladder_day_night_frequency', 'bladder_wait', 'bladder_wait_details', 'bladder_increased_frequency',
        'bladder_increased_frequency_details', 'sleep_duration_pattern', 'hobbies', 'job_satisfaction',
        'handedness', 'fmp', 'menstrual_duration', 'menstrual_interval', 'menstrual_regularity',
        'menstrual_pain', 'menstrual_pain_level', 'menstrual_flow', 'lmp', 'obstetric_history',
        'bp', 'pulse', 'respiratory_rate', 'temperature', 'posture_gait', 'built_nourishment',
        'consciousness_orientation', 'pallor', 'icterus', 'cyanosis', 'clubbing', 'lymphadenopathy',
        'edema', 'dehydration', 'height', 'weight', 'systemic_examination', 'investigations',
        'diagnosis', 'medicines', 'upashay', 'anupashay'
    ];
}
