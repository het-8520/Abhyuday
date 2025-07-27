<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Case Taking Form</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/long-form.css') }}">

    <script>
        // Define global JavaScript variables for routes and other data
        const patientStoreRoute = "{{ route('patient.store') }}";
        // You can add other global data here if needed
    </script>
</head>
<body class="p-4 sm:p-6 lg:p-8">
    <div class="form-container">
        <h1 class="text-3xl font-bold text-center mb-6 text-indigo-700">Patient Case Taking Form</h1>

        <div class="progress-bar-container">
            <div class="progress-bar" id="progressBar"></div>
        </div>
        <div id="errorMessage" class="success-message bg-red-100 text-red-800" style="display: none;">
            <!-- Error messages will be displayed here -->
        </div>
        <form id="patientForm" action="{{ route('patient.store') }}" class="relative min-h-[600px]">
            @csrf
            <!-- Step 1: Patient Information -->
            <section class="form-section active" id="step-1">
                <h2 class="text-2xl font-semibold mb-4 text-indigo-600">Patient Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-group">
                        <label for="name" class="form-label">Name:</label>
                        <input type="text" id="name" name="name" class="form-input" required>
                        <p class="error-message" id="name-error">Name is required.</p>
                    </div>
                    <div class="form-group">
                        <label for="age" class="form-label">Age:</label>
                        <input type="number" id="age" name="age" class="form-input" min="0">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Sex:</label>
                        <div class="radio-group">
                            <label><input type="radio" name="sex" value="MALE" required> MALE</label>
                            <label><input type="radio" name="sex" value="FEMALE"> FEMALE</label>
                            <label><input type="radio" name="sex" value="OTHER"> OTHER</label>
                        </div>
                        <p class="error-message" id="sex-error">Sex is required.</p>
                    </div>
                    <div class="form-group">
                        <label for="address" class="form-label">Address:</label>
                        <textarea id="address" name="address" rows="2" class="form-textarea"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="occupation" class="form-label">Occupation (Present/Past):</label>
                        <input type="text" id="occupation" name="occupation" class="form-input">
                    </div>
                    <div class="form-group">
                        <label for="religion" class="form-label">Religion:</label>
                        <input type="text" id="religion" name="religion" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Marital Status:</label>
                        <div class="radio-group">
                            <label><input type="radio" name="maritalStatus" value="single" required> single</label>
                            <label><input type="radio" name="maritalStatus" value="married"> married</label>
                            <label><input type="radio" name="maritalStatus" value="divorced"> divorced</label>
                            <label><input type="radio" name="maritalStatus" value="widow"> widow</label>
                            <label><input type="radio" name="maritalStatus" value="live_in"> live in</label>
                        </div>
                        <p class="error-message" id="maritalStatus-error">Marital Status is required.</p>
                    </div>
                    <div class="form-group">
                        <label for="socioEconomicStatus" class="form-label">Socio economic status:</label>
                        <input type="text" id="socioEconomicStatus" name="socioEconomicStatus" class="form-input">
                    </div>
                    <div class="form-group">
                        <label for="dateOfConsultation" class="form-label">Date of Consultation:</label>
                        <input type="date" id="dateOfConsultation" name="dateOfConsultation" class="form-input">
                    </div>
                    <div class="form-group">
                        <label for="outpatientNumber" class="form-label">Outpatient Number:</label>
                        <input type="text" id="outpatientNumber" name="outpatientNumber" class="form-input">
                    </div>
                    <div class="form-group">
                        <label for="inpatientNumber" class="form-label">Inpatient Number:</label>
                        <input type="text" id="inpatientNumber" name="inpatientNumber" class="form-input">
                    </div>
                </div>
                <div class="button-group">
                    <button type="button" class="nav-button" onclick="nextPrev(1)">Next</button>
                </div>
            </section>

            <!-- Step 2: Known case of and Chief Complaint with duration -->
            <section class="form-section" id="step-2">
                <h2 class="text-2xl font-semibold mb-4 text-indigo-600">Known Case of & Chief Complaint</h2>
                <div class="md:col-span-2 form-group">
                    <label for="knownCaseOf" class="form-label">Known case of:</label>
                    <textarea id="knownCaseOf" name="knownCaseOf" rows="2" class="form-textarea"></textarea>
                </div>

                <div class="md:col-span-2 form-group">
                    <label class="form-label">Chief complaint with duration:</label>
                    <div id="chiefComplaintsContainer">
                        <!-- Initial chief complaint input -->
                        <div class="chief-complaint-item">
                            <input type="text" name="chiefComplaint[]" class="form-input" placeholder="Chief complaint (max. 100 characters)" maxlength="100" required>
                            <input type="text" name="chiefComplaintDuration[]" class="form-input duration-input" placeholder="Duration" required>
                            <button type="button" class="remove-button" onclick="removeChiefComplaint(this)">Remove</button>
                            <p class="error-message chief-complaint-error">Complaint and duration are required.</p>
                        </div>
                    </div>
                    <button type="button" class="add-button mt-4" onclick="addChiefComplaint()">Add New Complaint</button>
                </div>

                <div class="button-group">
                    <button type="button" class="nav-button" onclick="nextPrev(-1)">Previous</button>
                    <button type="button" class="nav-button" onclick="nextPrev(1)">Next</button>
                </div>
            </section>

            <!-- Step 3: History of Present Illness & Past Illness -->
            <section class="form-section" id="step-3">
                <h2 class="text-2xl font-semibold mb-4 text-indigo-600">History of Illness</h2>
                <div class="md:col-span-2 form-group">
                    <label for="historyOfPresentIllness" class="form-label">History of present illness:</label>
                    <textarea id="historyOfPresentIllness" name="historyOfPresentIllness" rows="3" class="form-textarea"></textarea>
                </div>
                <div class="md:col-span-2 form-group">
                    <label for="historyOfPastIllness" class="form-label">History of past illness:</label>
                    <textarea id="historyOfPastIllness" name="historyOfPastIllness" rows="3" class="form-textarea"></textarea>
                </div>
                <div class="button-group">
                    <button type="button" class="nav-button" onclick="nextPrev(-1)">Previous</button>
                    <button type="button" class="nav-button" onclick="nextPrev(1)">Next</button>
                </div>
            </section>

            <!-- Step 4: Drug, Surgical, Allergic, Family, and Social History -->
            <section class="form-section" id="step-4">
                <h2 class="text-2xl font-semibold mb-4 text-indigo-600">Medical & Social History</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2 form-group">
                        <label for="drugHistory" class="form-label">Drug history:</label>
                        <textarea id="drugHistory" name="drugHistory" rows="2" class="form-textarea"></textarea>
                    </div>
                    <div class="md:col-span-2 form-group">
                        <label for="surgicalHistory" class="form-label">SURGICAL HISTORY:</label>
                        <textarea id="surgicalHistory" name="surgicalHistory" rows="2" class="form-textarea"></textarea>
                    </div>
                    <div class="md:col-span-2 form-group">
                        <label for="allergicHistory" class="form-label">Allergic history:</label>
                        <textarea id="allergicHistory" name="allergicHistory" rows="2" class="form-textarea"></textarea>
                    </div>
                    <div class="md:col-span-2 form-group">
                        <label for="familyHistory" class="form-label">Family history:</label>
                        <textarea id="familyHistory" name="familyHistory" rows="2" class="form-textarea"></textarea>
                    </div>

                    <!-- Social History Fields -->
                    <div class="md:col-span-2">
                        <h3 class="text-xl font-medium mb-2 text-gray-700">Social History:</h3>
                        <div class="form-group">
                            <label class="form-label">Alcohol intake:</label>
                            <div class="radio-group">
                                <label><input type="radio" name="alcoholIntake" value="Yes" onclick="toggleTextBox('alcoholIntakeText', true)"> Yes</label>
                                <label><input type="radio" name="alcoholIntake" value="No" onclick="toggleTextBox('alcoholIntakeText', false)" checked> No</label>
                            </div>
                            <div id="alcoholIntakeText" class="mt-2 hidden">
                                <input type="text" name="alcoholIntakeDetails" class="form-input" placeholder="Details of alcohol intake">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tobacco use:</label>
                            <div class="radio-group">
                                <label><input type="radio" name="tobaccoUse" value="Yes" onclick="toggleTextBox('tobaccoUseText', true)"> Yes</label>
                                <label><input type="radio" name="tobaccoUse" value="No" onclick="toggleTextBox('tobaccoUseText', false)" checked> No</label>
                            </div>
                            <div id="tobaccoUseText" class="mt-2 hidden">
                                <input type="text" name="tobaccoUseDetails" class="form-input" placeholder="Details of tobacco use">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Travel history:</label>
                            <div class="radio-group">
                                <label><input type="radio" name="travelHistory" value="Yes" onclick="toggleTextBox('travelHistoryText', true)"> Yes</label>
                                <label><input type="radio" name="travelHistory" value="No" onclick="toggleTextBox('travelHistoryText', false)" checked> No</label>
                            </div>
                            <div id="travelHistoryText" class="mt-2 hidden">
                                <input type="text" name="travelHistoryDetails" class="form-input" placeholder="Details of travel history">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="employmentHistory" class="form-label">Employment history:</label>
                            <textarea id="employmentHistory" name="employmentHistory" rows="2" maxlength="250" class="form-textarea"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="homeSituation" class="form-label">Home situation:</label>
                            <textarea id="homeSituation" name="homeSituation" rows="2" maxlength="250" class="form-textarea"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="furtherSocialHistory" class="form-label">Further social history:</label>
                            <textarea id="furtherSocialHistory" name="furtherSocialHistory" rows="2" maxlength="250" class="form-textarea"></textarea>
                        </div>
                    </div>
                </div>
                <div class="button-group">
                    <button type="button" class="nav-button" onclick="nextPrev(-1)">Previous</button>
                    <button type="button" class="nav-button" onclick="nextPrev(1)">Next</button>
                </div>
            </section>

            <!-- Step 5: Personal History -->
            <section class="form-section" id="step-5">
                <h2 class="text-2xl font-semibold mb-4 text-indigo-600">Personal History</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-group">
                        <label class="form-label">Diet:</label>
                        <div class="radio-group">
                            <label><input type="radio" name="diet" value="Vegetarian" required> Vegetarian</label>
                            <label><input type="radio" name="diet" value="Non-vegetarian"> Non-vegetarian</label>
                            <label><input type="radio" name="diet" value="Mixed"> Mixed</label>
                            <label><input type="radio" name="diet" value="Vegan"> Vegan</label>
                        </div>
                        <p class="error-message" id="diet-error">Diet is required.</p>
                    </div>
                    <div class="md:col-span-2 form-group">
                        <label for="breakfast" class="form-label">Breakfast:</label>
                        <input type="text" id="breakfast" name="breakfast" class="form-input" placeholder="Tea, Milk, Bhakhari, Chapati, Khakhra, Bread, Biscuit, fruits..">
                    </div>
                    <div class="md:col-span-2 form-group">
                        <label for="lunch" class="form-label">Lunch:</label>
                        <input type="text" id="lunch" name="lunch" class="form-input" placeholder="Chapatis, Vegetables, Dal, Rice, Mutton, Chicken, Fish...">
                    </div>
                    <div class="md:col-span-2 form-group">
                        <label for="midAfternoon" class="form-label">Mid-afternoon:</label>
                        <input type="text" id="midAfternoon" name="midAfternoon" class="form-input" placeholder="Snacks, Tea......">
                    </div>
                    <div class="md:col-span-2 form-group">
                        <label for="dinner" class="form-label">Dinner:</label>
                        <input type="text" id="dinner" name="dinner" class="form-input" placeholder="Bhakhari, Chapati, Rotlo, Vegetables, Milk...">
                    </div>

                    <div class="md:col-span-2 form-group">
                        <label class="form-label">Bowel Habits:</label>
                        <div class="flex flex-col gap-2">
                            <div class="flex items-center gap-4">
                                <label for="bowelFrequency" class="w-auto">Frequency:</label>
                                <div class="radio-group">
                                    <label><input type="radio" name="bowelFrequency" value="1" required> 1</label>
                                    <label><input type="radio" name="bowelFrequency" value="2"> 2</label>
                                    <label><input type="radio" name="bowelFrequency" value="3"> 3</label>
                                </div>
                                <span class="ml-2">times / day</span>
                                <p class="error-message" id="bowelFrequency-error">Frequency is required.</p>
                            </div>
                            <div class="flex items-center gap-4">
                                <label for="bowelConsistency" class="w-auto">Consistency:</label>
                                <div class="radio-group">
                                    <label><input type="radio" name="bowelConsistency" value="Hard" required> Hard</label>
                                    <label><input type="radio" name="bowelConsistency" value="Semisolid"> Semisolid</label>
                                    <label><input type="radio" name="bowelConsistency" value="Liquid"> Liquid</label>
                                </div>
                                <p class="error-message" id="bowelConsistency-error">Consistency is required.</p>
                            </div>
                            <div class="flex items-center gap-4">
                                <label for="bowelSatisfactory" class="w-auto">Satisfactory / Unsatisfactory:</label>
                                <div class="radio-group">
                                    <label><input type="radio" name="bowelSatisfactory" value="Satisfactory" required> Satisfied</label>
                                    <label><input type="radio" name="bowelSatisfactory" value="Unsatisfactory"> Unsatisfactory</label>
                                </div>
                                <p class="error-message" id="bowelSatisfactory-error">Satisfaction is required.</p>
                            </div>
                            <div class="flex items-center gap-4">
                                <label for="bowelBloodMucus" class="w-auto">With blood / Mucus:</label>
                                <div class="radio-group">
                                    <label><input type="radio" name="bowelBloodMucus" value="Yes"> Yes</label>
                                    <label><input type="radio" name="bowelBloodMucus" value="No"> No</label>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <label for="bowelStraining" class="w-auto">Straining / any difficulty/pain in Defecation:</label>
                                <div class="radio-group">
                                    <label><input type="radio" name="bowelStraining" value="Yes"> Yes</label>
                                    <label><input type="radio" name="bowelStraining" value="No"> No</label>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <label for="bowelChange" class="w-auto">Any recent change in bowel habits:</label>
                                <div class="radio-group">
                                    <label><input type="radio" name="bowelChange" value="Yes"> Yes</label>
                                    <label><input type="radio" name="bowelChange" value="No"> No</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="md:col-span-2 form-group">
                        <label class="form-label">Bladder Function:</label>
                        <div class="space-y-2">
                            <div class="form-group">
                                <label for="bladderDayNightFrequency" class="form-label">Note day/night time frequency:</label>
                                <input type="text" id="bladderDayNightFrequency" name="bladderDayNightFrequency" class="form-input">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Ask if patient has to wait before passing the urine:</label>
                                <div class="radio-group">
                                    <label><input type="radio" name="bladderWait" value="Yes" onclick="toggleTextBox('bladderWaitText', true)"> Yes</label>
                                    <label><input type="radio" name="bladderWait" value="No" onclick="toggleTextBox('bladderWaitText', false)" checked> No</label>
                                </div>
                                <div id="bladderWaitText" class="mt-2 hidden">
                                    <input type="text" name="bladderWaitDetails" class="form-input" placeholder="Details if patient has to wait">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">There is increased frequency urination especially during night:</label>
                                <div class="radio-group">
                                    <label><input type="radio" name="bladderIncreasedFrequency" value="Yes" onclick="toggleTextBox('bladderIncreasedFrequencyText', true)"> Yes</label>
                                    <label><input type="radio" name="bladderIncreasedFrequency" value="No" onclick="toggleTextBox('bladderIncreasedFrequencyText', false)" checked> No</label>
                                </div>
                                <div id="bladderIncreasedFrequencyText" class="mt-2 hidden">
                                    <input type="text" name="bladderIncreasedFrequencyDetails" class="form-input" placeholder="Details of increased night frequency">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="sleepDurationPattern" class="form-label">Sleep duration and Pattern:</label>
                        <input type="text" id="sleepDurationPattern" name="sleepDurationPattern" class="form-input">
                    </div>
                    <div class="form-group">
                        <label for="hobbies" class="form-label">Hobbies:</label>
                        <input type="text" id="hobbies" name="hobbies" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Job Satisfaction:</label>
                        <div class="radio-group">
                            <label><input type="radio" name="jobSatisfaction" value="Satisfied"> Satisfied</label>
                            <label><input type="radio" name="jobSatisfaction" value="Neutral"> Neutral</label>
                            <label><input type="radio" name="jobSatisfaction" value="Dissatisfied"> Dissatisfied</label>
                            <label><input type="radio" name="jobSatisfaction" value="N/A"> N/A</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Handedness (to identify the dominant hemisphere):</label>
                        <div class="radio-group">
                            <label><input type="radio" name="handedness" value="Lefty"> Lefty</label>
                            <label><input type="radio" name="handedness" value="Righty"> Righty</label>
                            <label><input type="radio" name="handedness" value="Ambidextrous"> Ambidextrous</label>
                        </div>
                    </div>
                </div>
                <div class="button-group">
                    <button type="button" class="nav-button" onclick="nextPrev(-1)">Previous</button>
                    <button type="button" class="nav-button" onclick="nextPrev(1)">Next</button>
                </div>
            </section>

            <!-- Step 6: Menstrual & Obstetric History -->
            <section class="form-section" id="step-6">
                <h2 class="text-2xl font-semibold mb-4 text-indigo-600">Menstrual & Obstetric History</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-group">
                        <label for="fmp" class="form-label">FMP:</label>
                        <input type="text" id="fmp" name="fmp" class="form-input">
                    </div>
                    <div class="md:col-span-2">
                        <h3 class="text-xl font-medium mb-2 text-gray-700">MENSTRUAL HISTORY:</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-group">
                                <label for="menstrualDuration" class="form-label">Duration:</label>
                                <input type="text" id="menstrualDuration" name="menstrualDuration" class="form-input">
                            </div>
                            <div class="form-group">
                                <label for="menstrualInterval" class="form-label">Interval:</label>
                                <input type="text" id="menstrualInterval" name="menstrualInterval" class="form-input">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Regular/Irregular:</label>
                                <div class="radio-group">
                                    <label><input type="radio" name="menstrualRegularity" value="regular"> regular</label>
                                    <label><input type="radio" name="menstrualRegularity" value="irregular"> irregular</label>
                                    <label><input type="radio" name="menstrualRegularity" value="regularly-irregular"> regularly-irregular</label>
                                    <label><input type="radio" name="menstrualRegularity" value="irregularly-regular"> irregularly-regular</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Painful/Painless:</label>
                                <div class="radio-group">
                                    <label><input type="radio" name="menstrualPain" value="painful"> painful</label>
                                    <label><input type="radio" name="menstrualPain" value="painless"> painless</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Pain Level:</label>
                                <div class="radio-group">
                                    <label><input type="radio" name="menstrualPainLevel" value="+1"> +1</label>
                                    <label><input type="radio" name="menstrualPainLevel" value="+2"> +2</label>
                                    <label><input type="radio" name="menstrualPainLevel" value="+3"> +3</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Flow:</label>
                                <div class="radio-group">
                                    <label><input type="radio" name="menstrualFlow" value="scanty"> scanty</label>
                                    <label><input type="radio" name="menstrualFlow" value="moderate"> moderate</m-label>
                                    <label><input type="radio" name="menstrualFlow" value="excessive"> excessive</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="lmp" class="form-label">LMP:</label>
                                <input type="date" id="lmp" name="lmp" class="form-input">
                            </div>
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <h3 class="text-xl font-medium mb-2 text-gray-700">OBSTETRIC HISTORY</h3>
                        <div class="form-group obstetric-input-group">
                            <label for="gravida">G</label>
                            <input type="number" id="gravida" name="gravida" class="form-input" min="0" value="0">
                            <label for="para">P</label>
                            <input type="number" id="para" name="para" class="form-input" min="0" value="0">
                            <label for="abortions">A</label>
                            <input type="number" id="abortions" name="abortions" class="form-input" min="0" value="0">
                            <label for="living">L</label>
                            <input type="number" id="living" name="living" class="form-input" min="0" value="0">
                            <label for="deaths">D</label>
                            <input type="number" id="deaths" name="deaths" class="form-input" min="0" value="0">
                        </div>
                    </div>
                </div>
                <div class="button-group">
                    <button type="button" class="nav-button" onclick="nextPrev(-1)">Previous</button>
                    <button type="button" class="nav-button" onclick="nextPrev(1)">Next</button>
                </div>
            </section>

            <!-- Step 7: General Physical Examination -->
            <section class="form-section" id="step-7">
                <h2 class="text-2xl font-semibold mb-4 text-indigo-600">General Physical Examination</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-group">
                        <label for="bp" class="form-label">BP:</label>
                        <input type="text" id="bp" name="bp" class="form-input">
                    </div>
                    <div class="form-group">
                        <label for="pulse" class="form-label">PULSE:</label>
                        <input type="text" id="pulse" name="pulse" class="form-input">
                    </div>
                    <div class="form-group">
                        <label for="respiratoryRate" class="form-label">RESPIRATORY RATE:</label>
                        <input type="text" id="respiratoryRate" name="respiratoryRate" class="form-input">
                    </div>
                    <div class="form-group">
                        <label for="temperature" class="form-label">TEMPERATURE:</label>
                        <input type="text" id="temperature" name="temperature" class="form-input">
                    </div>
                    <div class="form-group">
                        <label for="postureGait" class="form-label">Posture and Gait:</label>
                        <input type="text" id="postureGait" name="postureGait" class="form-input">
                    </div>
                    <div class="form-group">
                        <label for="builtNourishment" class="form-label">Built and Nourishment:</label>
                        <input type="text" id="builtNourishment" name="builtNourishment" class="form-input">
                    </div>
                    <div class="form-group">
                        <label for="consciousnessOrientation" class="form-label">Consciousness and Orientation:</label>
                        <input type="text" id="consciousnessOrientation" name="consciousnessOrientation" class="form-input">
                    </div>
                    <div class="form-group">
                        <label for="pallor" class="form-label">Pallor:</label>
                        <input type="text" id="pallor" name="pallor" class="form-input">
                    </div>
                    <div class="form-group">
                        <label for="icterus" class="form-label">Icterus:</label>
                        <input type="text" id="icterus" name="icterus" class="form-input">
                    </div>
                    <div class="form-group">
                        <label for="cyanosis" class="form-label">Cyanosis:</label>
                        <input type="text" id="cyanosis" name="cyanosis" class="form-input">
                    </div>
                    <div class="form-group">
                        <label for="clubbing" class="form-label">Clubbing:</label>
                        <input type="text" id="clubbing" name="clubbing" class="form-input">
                    </div>
                    <div class="form-group">
                        <label for="lymphadenopathy" class="form-label">Lymphadenopathy:</label>
                        <input type="text" id="lymphadenopathy" name="lymphadenopathy" class="form-input">
                    </div>
                    <div class="form-group">
                        <label for="edema" class="form-label">Edema:</label>
                        <input type="text" id="edema" name="edema" class="form-input">
                    </div>
                    <div class="form-group">
                        <label for="dehydration" class="form-label">Dehydration:</label>
                        <input type="text" id="dehydration" name="dehydration" class="form-input">
                    </div>
                    <div class="form-group">
                        <label for="height" class="form-label">Height:</label>
                        <input type="text" id="height" name="height" class="form-input">
                    </div>
                    <div class="form-group">
                        <label for="weight" class="form-label">Weight:</label>
                        <input type="text" id="weight" name="weight" class="form-input">
                    </div>
                </div>
                <div class="button-group">
                    <button type="button" class="nav-button" onclick="nextPrev(-1)">Previous</button>
                    <button type="button" class="nav-button" onclick="nextPrev(1)">Next</button>
                </div>
            </section>

            <!-- Step 8: Systemic Examination -->
            <section class="form-section" id="step-8">
                <h2 class="text-2xl font-semibold mb-4 text-indigo-600">SYSTEMIC EXAMINATION</h2>
                <div class="form-group">
                    <textarea id="systemicExamination" name="systemicExamination" rows="4" class="form-textarea"></textarea>
                </div>
                <div class="button-group">
                    <button type="button" class="nav-button" onclick="nextPrev(-1)">Previous</button>
                    <button type="button" class="nav-button" onclick="nextPrev(1)">Next</button>
                </div>
            </section>

            <!-- Step 9: Investigations -->
            <section class="form-section" id="step-9">
                <h2 class="text-2xl font-semibold mb-4 text-indigo-600">Investigations</h2>
                <div class="form-group">
                    <textarea id="investigations" name="investigations" rows="4" class="form-textarea"></textarea>
                </div>
                <div class="button-group">
                    <button type="button" class="nav-button" onclick="nextPrev(-1)">Previous</button>
                    <button type="button" class="nav-button" onclick="nextPrev(1)">Next</button>
                </div>
            </section>

            <!-- Step 10: Diagnosis -->
            <section class="form-section" id="step-10">
                <h2 class="text-2xl font-semibold mb-4 text-indigo-600">Diagnosis</h2>
                <div class="form-group">
                    <textarea id="diagnosis" name="diagnosis" rows="4" class="form-textarea"></textarea>
                </div>
                <div class="button-group">
                    <button type="button" class="nav-button" onclick="nextPrev(-1)">Previous</button>
                    <button type="button" class="nav-button" onclick="nextPrev(1)">Next</button>
                </div>
            </section>

            <!-- Step 11: Treatment -->
            <section class="form-section" id="step-11">
                <h2 class="text-2xl font-semibold mb-4 text-indigo-600">Treatment</h2>
                <div class="space-y-4">
                    <!-- Dynamic rows for adding multiple medicines -->
                    <div id="medicineRowsContainer">
                        <!-- Initial medicine row -->
                        <div class="treatment-item-row" data-row-index="0">
                            <div class="form-group flex-1">
                                <label class="form-label">Medicine:</label>
                                <div class="custom-dropdown" id="medicineDropdown_0">
                                    <div class="dropdown-header" onclick="toggleMainDropdown(this)">
                                        <span class="selected-value">Select Medicine</span> <!-- Displays final selection -->
                                        <span class="arrow">&#9660;</span>
                                    </div>
                                    <div class="dropdown-list">
                                        <div class="category-list-view">
                                            <!-- Categories populated here -->
                                        </div>
                                        <div class="medicine-list-view hidden">
                                            <div class="dropdown-item back-button-item" onclick="showCategories(this)">← Back to Categories</div>
                                            <!-- Medicines populated here -->
                                        </div>
                                    </div>
                                    <input type="hidden" name="medicineCategory[]" class="selected-category-input">
                                    <input type="hidden" name="medicineName[]" class="selected-medicine-input">
                                </div>
                            </div>
                            <div class="form-group flex-1">
                                <label class="form-label dose-label">Dose (M-N-N):</label>
                                <div class="dose-inputs">
                                    <input type="number" name="doseMorning[]" class="form-input" placeholder="M" value="1" min="0">
                                    <span>-</span>
                                    <input type="number" name="doseNoon[]" class="form-input" placeholder="N" value="1" min="0">
                                    <span>-</span>
                                    <input type="number" name="doseNight[]" class="form-input" placeholder="N" value="1" min="0">
                                </div>
                            </div>
                            <div class="form-group flex-1">
                                <label for="medicineAnupaan_0" class="form-label">Anupaan:</label>
                                <input type="text" id="medicineAnupaan_0" name="medicineAnupaan[]" class="form-input" placeholder="e.g., with warm water">
                            </div>
                            <button type="button" class="remove-button self-center" onclick="removeMedicineRow(this)">Remove</button>
                        </div>
                    </div>
                    <button type="button" class="add-button mt-4" onclick="addMedicineRow()">Add Another Medicine</button>

                    <div class="form-group">
                        <label for="upashay" class="form-label">Upashay (Palliative):</label>
                        <input type="text" id="upashay" name="upashay" class="form-input">
                    </div>
                    <div class="form-group">
                        <label for="anupashay" class="form-label">Anupashay (Contra-palliative):</label>
                        <textarea id="anupashay" name="anupashay" rows="2" class="form-textarea"></textarea>
                    </div>
                </div>
                <div class="button-group">
                    <button type="button" class="nav-button" onclick="nextPrev(-1)">Previous</button>
                    <button type="submit" class="submit-button">Submit Patient Case</button>
                </div>
            </section>

            <div id="successMessage" class="success-message">
                Form submitted successfully! (Check console for data)
            </div>

        </form>
    </div>

    <!-- Link to external JavaScript file -->
    <script src="{{ asset('js/long-form.js') }}"></script>
</body>
</html>
