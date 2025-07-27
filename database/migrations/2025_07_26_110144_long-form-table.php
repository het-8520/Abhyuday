<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();

            // Step 1: Patient Information
            $table->string('name');
            $table->integer('age')->nullable();
            $table->string('sex'); // MALE, FEMALE, OTHER
            $table->text('address')->nullable();
            $table->string('occupation')->nullable();
            $table->string('religion')->nullable();
            $table->string('marital_status'); // single, married, divorced, widow, live_in
            $table->string('socio_economic_status')->nullable();
            $table->date('date_of_consultation')->nullable();
            $table->string('outpatient_number')->nullable()->unique();
            $table->string('inpatient_number')->nullable()->unique();

            // Step 2: Known case of and Chief Complaint with duration
            $table->text('known_case_of')->nullable();
            $table->json('chief_complaints')->nullable(); // Stores array of objects: [{ complaint: '...', duration: '...' }]

            // Step 3: History of Illness
            $table->text('history_of_present_illness')->nullable();
            $table->text('history_of_past_illness')->nullable();

            // Step 4: Drug, Surgical, Allergic, Family, and Social History
            $table->text('drug_history')->nullable();
            $table->text('surgical_history')->nullable();
            $table->text('allergic_history')->nullable();
            $table->text('family_history')->nullable();
            $table->string('alcohol_intake')->nullable(); // Yes, No
            $table->text('alcohol_intake_details')->nullable();
            $table->string('tobacco_use')->nullable(); // Yes, No
            $table->text('tobacco_use_details')->nullable();
            $table->string('travel_history')->nullable(); // Yes, No
            $table->text('travel_history_details')->nullable();
            $table->text('employment_history')->nullable();
            $table->text('home_situation')->nullable();
            $table->text('further_social_history')->nullable();

            // Step 5: Personal History
            $table->string('diet')->nullable(); // Vegetarian, Non-vegetarian, Mixed, Vegan
            $table->string('breakfast')->nullable();
            $table->string('lunch')->nullable();
            $table->string('mid_afternoon')->nullable();
            $table->string('dinner')->nullable();
            $table->string('bowel_frequency')->nullable(); // 1, 2, 3
            $table->string('bowel_consistency')->nullable(); // Hard, Semisolid, Liquid
            $table->string('bowel_satisfactory')->nullable(); // Satisfactory, Unsatisfactory
            $table->boolean('bowel_blood_mucus')->nullable();
            $table->boolean('bowel_straining')->nullable();
            $table->boolean('bowel_change')->nullable();
            $table->string('bladder_day_night_frequency')->nullable();
            $table->boolean('bladder_wait')->nullable();
            $table->text('bladder_wait_details')->nullable();
            $table->boolean('bladder_increased_frequency')->nullable();
            $table->text('bladder_increased_frequency_details')->nullable();
            $table->string('sleep_duration_pattern')->nullable();
            $table->string('hobbies')->nullable();
            $table->string('job_satisfaction')->nullable(); // Satisfied, Neutral, Dissatisfied, N/A
            $table->string('handedness')->nullable(); // Lefty, Righty, Ambidextrous

            // Step 6: Menstrual & Obstetric History
            $table->string('fmp')->nullable();
            $table->string('menstrual_duration')->nullable();
            $table->string('menstrual_interval')->nullable();
            $table->string('menstrual_regularity')->nullable(); // regular, irregular, regularly-irregular, irregularly-regular
            $table->string('menstrual_pain')->nullable(); // painful, painless
            $table->json('menstrual_pain_level')->nullable(); // Stores array of strings: ['+1', '+2']
            $table->string('menstrual_flow')->nullable(); // scanty, moderate, excessive
            $table->date('lmp')->nullable();
            $table->json('obstetric_history')->nullable(); // Stores object: { gravida: 0, para: 0, abortions: 0, living: 0, deaths: 0 }


            // Step 7: General Physical Examination
            $table->string('bp')->nullable();
            $table->string('pulse')->nullable();
            $table->string('respiratory_rate')->nullable();
            $table->string('temperature')->nullable();
            $table->string('posture_gait')->nullable();
            $table->string('built_nourishment')->nullable();
            $table->string('consciousness_orientation')->nullable();
            $table->string('pallor')->nullable();
            $table->string('icterus')->nullable();
            $table->string('cyanosis')->nullable();
            $table->string('clubbing')->nullable();
            $table->string('lymphadenopathy')->nullable();
            $table->string('edema')->nullable();
            $table->string('dehydration')->nullable();
            $table->string('height')->nullable();
            $table->string('weight')->nullable();

            // Step 8: Systemic Examination
            $table->text('systemic_examination')->nullable();

            // Step 9: Investigations
            $table->text('investigations')->nullable();

            // Step 10: Diagnosis
            $table->text('diagnosis')->nullable();

            // Step 11: Treatment
            $table->json('medicines')->nullable(); // Stores array of objects: [{ category: '...', name: '...', dose: '1-1-1', anupaan: '...' }]
            $table->string('upashay')->nullable();
            $table->text('anupashay')->nullable();

            $table->timestamps(); // Adds created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};

