let currentStep = 0; // Current step index
const formSections = document.querySelectorAll('.form-section');
const progressBar = document.getElementById('progressBar');
const totalSteps = formSections.length;
let medicineRowCount = 1; // To keep track of dynamically added medicine rows

// Medicine data from the PDF (translated and structured)
const medicineData = {
    "Vati": [
        "Arjun Tikadi (Arjun Pills)", "I.K. Compound", "Futaj Tikadi (Futaj Pills)", "Khadiradi Vati",
        "Chandraprabha Vati No. 2", "Chitrakadi Vati", "Triphala Tikadi (Triphala Pills)",
        "Mamejva Ghanvati", "Raj: Pravartani Vati", "Vasa Tikadi (Vasa Pills)",
        "Vishatinduk Vati", "Sanjivani Vati", "Sarivadi Vati", "Sudarshan Ghanvati",
        "Harade Tikadi (Harade Pills)", "Agnitundi Vati", "Chardiripu Vati",
        "Punarnavamandur Vati", "Prabhakar Vati", "Yashtimadh Vati", "Sanshamani Vati",
        "Gandhak Rasayan", "Stree Rasayan No. 1", "Stree Rasayan No. 2"
    ],
    "Churna": [
        "Arjun Churna (Arjun Powder)", "Ashwagandha Churna", "Amalaki Churna",
        "Keshshuddhi Churna", "Kher Chhal Churna", "Gokshara Churna", "Trikatu Churna",
        "Din Dayal Churna", "Panchsakar Churna", "Panchvalkal Churna", "Punarnava Churna",
        "Pushyanug Churna", "Baheda Churna", "Balchatur Bhadra Churna", "Rasayan Churna",
        "Shatavari Churna", "Shu. Gerik Churna", "Shu. Tankana Churna", "Shu. Sphatika Churna",
        "Swa. Virechan Churna", "Harade Churna", "Hingwashtak Churna", "Sitopaladi Churna",
        "Lavan Bhaskar Churna", "Vijaysar Churna", "Lodhra Churna", "Ajmodadi Churna",
        "Yashtimadhu Churna", "Talisadi Churna", "Shivakshar Pachan Churna", "Vasa Churna",
        "Dashansanskar Churna", "Dhatrinisha Churna", "Avipattikar Churna", "Mahasudarshan Churna",
        "Sunth Churna", "Kalmegh Churna"
    ],
    "Guggul": [
        "Kanchnar Guggul", "Keshor Guggul", "Gokshuradi Guggul", "Triphala Guggul",
        "Pathyadi Guggul", "Punarnava Guggul", "Laghu Yograj Guggul", "Rasnadi Guggul",
        "Sinhanad Guggul"
    ],
    "Malam": [
        "Gandhak Malam (Gandhak Ointment)"
    ],
    "Tail": [
        "Karanj Tail (Karanj Oil)", "Jatyadi Tail (Jatyadi Oil)", "Nirgundi Tail"
    ],
    "Kwath": [
        "Abhyadi Kwath", "Guduchyadi Kwath", "Pathyadi Kwath", "Bharajgyadi Kwath",
        "Triphala Kwath"
    ],
    "Syrup": [
        "Balchatur Bhadra Syrup"
    ],
    "Bhasma": [
        "Godanti Bhasma", "Shankh Bhasma", "Loh Bhasma"
    ],
    "Ras": [
        "Arshkuthar Ras", "Navjeevanras", "Laghu Vasant Malati Ras", "Shir:shuladi Vraj Ras",
        "Shonitargal Ras", "Sutshekhar Ras", "Arogyavardhini Ras", "Kamdudha Ras"
    ],
    "Lepa": [
        // Add specific Lepa items if available in the PDF, otherwise leave empty or add a placeholder
    ]
};

// Function to display a specific step
function showStep(n) {
    formSections.forEach((section, index) => {
        section.classList.remove('active');
    });
    formSections[n].classList.add('active');
    updateProgressBar();
}

// Function to move to the next or previous step
function nextPrev(n) {
    if (n === 1 && !validateForm()) {
        return false;
    }

    formSections[currentStep].classList.remove('active');

    const sexRadios = document.querySelectorAll('input[name="sex"]');
    let selectedSex = '';
    for (const radio of sexRadios) {
        if (radio.checked) {
            selectedSex = radio.value;
            break;
        }
    }

    if (n === 1) {
        if (currentStep === 4 && selectedSex !== 'FEMALE') {
            currentStep += 2;
        } else {
            currentStep += n;
        }
    } else if (n === -1) {
        if (currentStep === 6 && selectedSex !== 'FEMALE') {
            currentStep -= 2;
        } else {
            currentStep += n;
        }
    }

    if (currentStep >= totalSteps) {
        return false;
    }

    showStep(currentStep);
}

// Function to validate the current step's fields
function validateForm() {
    let valid = true;
    const currentSection = formSections[currentStep];
    const requiredInputs = currentSection.querySelectorAll('[required]');

    currentSection.querySelectorAll('.error-message').forEach(error => {
        error.style.display = 'none';
    });

    requiredInputs.forEach(input => {
        let inputValid = true;
        if (input.type === 'radio') {
            const radioGroupName = input.name;
            const radioGroup = document.querySelectorAll(`input[name="${radioGroupName}"]:checked`);
            if (radioGroup.length === 0) {
                inputValid = false;
            }
        } else if (input.value.trim() === '') {
            inputValid = false;
        }

        if (!inputValid) {
            valid = false;
            const errorElement = document.getElementById(`${input.id || input.name}-error`);
            if (errorElement) {
                errorElement.style.display = 'block';
            } else {
                const parentGroup = input.closest('.form-group') || input.closest('.chief-complaint-item');
                if (parentGroup) {
                    let genericError = document.createElement('p');
                    genericError.className = 'error-message';
                    genericError.textContent = 'This field is required.';
                    parentGroup.appendChild(genericError);
                    genericError.style.display = 'block';
                }
            }
        }
    });

    if (currentSection.id === 'step-2') {
        const chiefComplaintItems = currentSection.querySelectorAll('.chief-complaint-item');
        chiefComplaintItems.forEach(item => {
            const complaintInput = item.querySelector('input[name="chiefComplaint[]"]');
            const durationInput = item.querySelector('input[name="chiefComplaintDuration[]"]');
            const errorMsg = item.querySelector('.chief-complaint-error');

            if (complaintInput && durationInput) {
                if (complaintInput.value.trim() === '' || durationInput.value.trim() === '') {
                    valid = false;
                    if (errorMsg) errorMsg.style.display = 'block';
                } else {
                    if (errorMsg) errorMsg.style.display = 'none';
                }
            }
        });
    }

    return valid;
}

// Function to update the progress bar
function updateProgressBar() {
    const progress = ((currentStep + 1) / totalSteps) * 100;
    progressBar.style.width = progress + '%';
}

// Function to add a new chief complaint input pair
function addChiefComplaint() {
    const container = document.getElementById('chiefComplaintsContainer');
    const newItem = document.createElement('div');
    newItem.className = 'chief-complaint-item';
    newItem.innerHTML = `
        <input type="text" name="chiefComplaint[]" class="form-input" placeholder="Chief complaint (max. 100 characters)" maxlength="100" required>
        <input type="text" name="chiefComplaintDuration[]" class="form-input duration-input" placeholder="Duration" required>
        <button type="button" class="remove-button" onclick="removeChiefComplaint(this)">Remove</button>
        <p class="error-message chief-complaint-error">Complaint and duration are required.</p>
    `;
    container.appendChild(newItem);
}

// Function to remove a chief complaint input pair
function removeChiefComplaint(button) {
    const itemToRemove = button.closest('.chief-complaint-item');
    if (itemToRemove) {
        const container = document.getElementById('chiefComplaintsContainer');
        if (container.children.length > 1) {
            itemToRemove.remove();
        } else {
            const complaintInput = itemToRemove.querySelector('input[name="chiefComplaint[]"]');
            const durationInput = itemToRemove.querySelector('input[name="chiefComplaintDuration[]"]');
            if (complaintInput) complaintInput.value = '';
            if (durationInput) durationInput.value = '';
            const errorMsg = itemToRemove.querySelector('.chief-complaint-error');
            if (errorMsg) errorMsg.style.display = 'none';
        }
    }
}

// Function to toggle the visibility of a text box based on radio button selection
function toggleTextBox(id, show) {
    const textBox = document.getElementById(id);
    if (show) {
        textBox.classList.remove('hidden');
        const inputElement = textBox.querySelector('input');
        if (inputElement) {
            inputElement.setAttribute('required', 'true');
        }
    } else {
        textBox.classList.add('hidden');
        const inputElement = textBox.querySelector('input');
        if (inputElement) {
            inputElement.removeAttribute('required');
            inputElement.value = '';
        }
    }
}

// --- Custom Dropdown Logic for Medicines ---

// Global state for keyboard navigation
let lastTypedChar = '';
let lastTypedTime = 0;
const TYPING_TIMEOUT = 1000; // milliseconds

// Closes all open dropdowns except the one passed as an argument
function closeAllDropdowns(exceptDropdown = null) {
    document.querySelectorAll('.dropdown-list.active').forEach(list => {
        const header = list.previousElementSibling; // Get the header div
        const dropdown = header.closest('.custom-dropdown');
        if (dropdown !== exceptDropdown) {
            list.classList.remove('active');
            header.classList.remove('active');
        }
    });
}

// Toggles the main dropdown visibility (categories or medicines)
function toggleMainDropdown(headerElement) {
    const dropdownList = headerElement.nextElementSibling;
    const parentDropdown = headerElement.closest('.custom-dropdown');
    const selectedCategoryInput = parentDropdown.querySelector('.selected-category-input');

    closeAllDropdowns(parentDropdown); // Close other dropdowns

    dropdownList.classList.toggle('active');
    headerElement.classList.toggle('active');

    // Determine which view to show when opening
    if (dropdownList.classList.contains('active')) {
        if (selectedCategoryInput.value) {
            // If a category is already selected, show medicines for that category
            showMedicinesForCategory(selectedCategoryInput.value, parentDropdown);
        } else {
            // Otherwise, show categories
            populateCategories(parentDropdown);
        }
        // Attach keyboard listener when dropdown is active
        document.addEventListener('keydown', handleDropdownKeydown);
    } else {
        // Remove keyboard listener when dropdown is closed
        document.removeEventListener('keydown', handleDropdownKeydown);
        lastTypedChar = ''; // Reset typing state
        lastTypedTime = 0;
    }
}

// Populates the category list within a custom dropdown
function populateCategories(parentDropdown) {
    const categoryListView = parentDropdown.querySelector('.category-list-view');
    const medicineListView = parentDropdown.querySelector('.medicine-list-view');
    
    categoryListView.innerHTML = ''; // Clear previous content
    medicineListView.classList.add('hidden'); // Hide medicine list
    categoryListView.classList.remove('hidden'); // Show category list

    for (const category in medicineData) {
        const categoryItem = document.createElement('div');
        categoryItem.className = 'dropdown-item';
        categoryItem.textContent = categoryNames[category] || category; // Use translated name or original
        categoryItem.dataset.category = category;
        categoryItem.onclick = (event) => showMedicinesForCategory(event.target.dataset.category, parentDropdown);
        categoryListView.appendChild(categoryItem);
    }
}

// Shows medicines for a selected category
function showMedicinesForCategory(category, parentDropdown) {
    const categoryListView = parentDropdown.querySelector('.category-list-view');
    const medicineListView = parentDropdown.querySelector('.medicine-list-view');
    const selectedCategoryInput = parentDropdown.querySelector('.selected-category-input');

    selectedCategoryInput.value = category; // Update hidden input for category

    categoryListView.classList.add('hidden'); // Hide category list
    medicineListView.classList.remove('hidden'); // Show medicine list
    
    let medicineListContent = medicineListView.querySelector('.nested-dropdown-list');
    if (!medicineListContent) { // Create if it doesn't exist
        medicineListContent = document.createElement('div');
        medicineListContent.className = 'nested-dropdown-list';
        medicineListView.appendChild(medicineListContent);
    }
    medicineListContent.innerHTML = ''; // Clear previous medicines

    // Add "Back to Categories" button
    const backButton = medicineListView.querySelector('.back-button-item');
    if (backButton) {
        backButton.onclick = () => {
            selectedCategoryInput.value = ''; // Clear selected category
            populateCategories(parentDropdown); // Go back to categories view
        };
    }

    if (medicineData[category]) {
        medicineData[category].forEach(medicine => {
            const medicineItem = document.createElement('div');
            medicineItem.className = 'nested-dropdown-item';
            medicineItem.textContent = medicine;
            medicineItem.dataset.medicine = medicine;
            medicineItem.onclick = (event) => selectMedicine(event.target, parentDropdown);
            medicineListContent.appendChild(medicineItem);
        });
    }
}

// Handles medicine selection
function selectMedicine(medicineItem, parentDropdown) {
    const selectedMedicine = medicineItem.dataset.medicine;
    const dropdownHeader = parentDropdown.querySelector('.dropdown-header');
    const selectedValueSpan = dropdownHeader.querySelector('.selected-value');
    const medicineInput = parentDropdown.querySelector('.selected-medicine-input');
    const dropdownList = parentDropdown.querySelector('.dropdown-list');
    const categoryInput = parentDropdown.querySelector('.selected-category-input');

    medicineInput.value = selectedMedicine; // Update hidden input for medicine name
    selectedValueSpan.textContent = selectedMedicine; // Display selected medicine name
    // Ensure category is also stored if not already
    if (!categoryInput.value) {
        const currentCategoryView = parentDropdown.querySelector('.medicine-list-view');
        if (currentCategoryView && !currentCategoryView.classList.contains('hidden')) {
            // This means we are in medicine list view, category must have been selected
            // This is a fallback, ideally categoryInput.value is already set by showMedicinesForCategory
            // For now, we assume it's set.
        }
    }


    // Close the dropdown
    dropdownList.classList.remove('active');
    dropdownHeader.classList.remove('active');

    // Mark selected item
    parentDropdown.querySelectorAll('.dropdown-item, .nested-dropdown-item').forEach(item => item.classList.remove('selected', 'focused'));
    medicineItem.classList.add('selected');

    // Remove keyboard listener after selection
    document.removeEventListener('keydown', handleDropdownKeydown);
    lastTypedChar = ''; // Reset typing state
    lastTypedTime = 0;
}

// Keyboard navigation handler
function handleDropdownKeydown(event) {
    const activeDropdownList = document.querySelector('.dropdown-list.active');
    if (!activeDropdownList) return;

    const isCategoryView = !activeDropdownList.querySelector('.category-list-view').classList.contains('hidden');
    const itemsContainer = isCategoryView ? activeDropdownList.querySelector('.category-list-view') : activeDropdownList.querySelector('.nested-dropdown-list');
    
    if (!itemsContainer) return;

    const items = Array.from(itemsContainer.children).filter(item => !item.classList.contains('back-button-item'));
    if (items.length === 0) return;

    let currentFocusedIndex = items.findIndex(item => item.classList.contains('focused'));

    // Clear previous focus
    items.forEach(item => item.classList.remove('focused'));

    if (event.key === 'ArrowDown') {
        event.preventDefault();
        currentFocusedIndex = (currentFocusedIndex + 1) % items.length;
        items[currentFocusedIndex].classList.add('focused');
        items[currentFocusedIndex].scrollIntoView({ block: 'nearest' });
    } else if (event.key === 'ArrowUp') {
        event.preventDefault();
        currentFocusedIndex = (currentFocusedIndex - 1 + items.length) % items.length;
        items[currentFocusedIndex].classList.add('focused');
        items[currentFocusedIndex].scrollIntoView({ block: 'nearest' });
    } else if (event.key === 'Enter') {
        event.preventDefault();
        if (currentFocusedIndex !== -1) {
            items[currentFocusedIndex].click(); // Simulate click on the focused item
        }
    } else if (event.key.length === 1 && event.key.match(/[a-zA-Z0-9]/)) { // Alphanumeric keys for type-ahead
        const char = event.key.toLowerCase();
        const currentTime = Date.now();

        if (currentTime - lastTypedTime > TYPING_TIMEOUT) {
            lastTypedChar = char; // Start new sequence
        } else {
            lastTypedChar += char; // Append to current sequence
        }
        lastTypedTime = currentTime;

        let foundIndex = -1;
        let startIndex = currentFocusedIndex !== -1 ? (currentFocusedIndex + 1) % items.length : 0;

        // Search from current focused item + 1
        for (let i = 0; i < items.length; i++) {
            const idx = (startIndex + i) % items.length;
            if (items[idx].textContent.toLowerCase().startsWith(lastTypedChar)) {
                foundIndex = idx;
                break;
            }
        }

        if (foundIndex === -1 && lastTypedChar.length > 1) {
            // If multi-character search failed, try single character search from beginning
            lastTypedChar = char;
            for (let i = 0; i < items.length; i++) {
                if (items[i].textContent.toLowerCase().startsWith(lastTypedChar)) {
                    foundIndex = i;
                    break;
                }
            }
        }

        if (foundIndex !== -1) {
            items[foundIndex].classList.add('focused');
            items[foundIndex].scrollIntoView({ block: 'nearest' });
        }
    }
}


// Add an event listener to the document to close dropdowns when clicking outside
document.addEventListener('click', (event) => {
    if (!event.target.closest('.custom-dropdown')) {
        closeAllDropdowns();
    }
});

// Add a new medicine row with custom dropdown
function addMedicineRow() {
    const container = document.getElementById('medicineRowsContainer');
    const newRow = document.createElement('div');
    newRow.className = 'treatment-item-row';
    newRow.dataset.rowIndex = medicineRowCount;
    newRow.innerHTML = `
        <div class="form-group flex-1">
            <label class="form-label">Medicine:</label>
            <div class="custom-dropdown" id="medicineDropdown_${medicineRowCount}">
                <div class="dropdown-header" onclick="toggleMainDropdown(this)">
                    <span class="selected-value">Select Medicine</span>
                    <span class="arrow">&#9660;</span>
                </div>
                <div class="dropdown-list">
                    <div class="category-list-view">
                        <!-- Categories will be populated here -->
                    </div>
                    <div class="medicine-list-view hidden">
                        <div class="dropdown-item back-button-item" onclick="showCategories(this)">← Back to Categories</div>
                        <!-- Medicines will be populated here -->
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
            <label for="medicineAnupaan_${medicineRowCount}" class="form-label">Anupaan:</label>
            <input type="text" id="medicineAnupaan_${medicineRowCount}" name="medicineAnupaan[]" class="form-input" placeholder="e.g., with warm water">
        </div>
        <button type="button" class="remove-button self-center" onclick="removeMedicineRow(this)">Remove</button>
    `;
    container.appendChild(newRow);
    // Initialize the custom dropdown for the new row
    const newDropdown = document.getElementById(`medicineDropdown_${medicineRowCount}`);
    populateCategories(newDropdown); // Populate categories for the new dropdown
    medicineRowCount++;
}

// Function to remove a medicine row
function removeMedicineRow(button) {
    const rowToRemove = button.closest('.treatment-item-row');
    if (rowToRemove) {
        const container = document.getElementById('medicineRowsContainer');
        if (container.children.length > 1) { // Ensure at least one row remains
            rowToRemove.remove();
        } else {
            // If only one row, clear its contents instead of removing it
            const dropdownHeaderSpan = rowToRemove.querySelector('.dropdown-header .selected-value');
            const categoryInput = rowToRemove.querySelector('.selected-category-input');
            const medicineInput = rowToRemove.querySelector('.selected-medicine-input');
            const doseMorningInput = rowToRemove.querySelector('input[name="doseMorning[]"]');
            const doseNoonInput = rowToRemove.querySelector('input[name="doseNoon[]"]');
            const doseNightInput = rowToRemove.querySelector('input[name="doseNight[]"]');
            const anupaanInput = rowToRemove.querySelector('input[name="medicineAnupaan[]"]');

            if (dropdownHeaderSpan) dropdownHeaderSpan.textContent = 'Select Medicine';
            if (categoryInput) categoryInput.value = '';
            if (medicineInput) medicineInput.value = '';
            if (doseMorningInput) doseMorningInput.value = '1';
            if (doseNoonInput) doseNoonInput.value = '1';
            if (doseNightInput) doseNightInput.value = '1';
            if (anupaanInput) anupaanInput.value = '';

            // Reset the custom dropdown to category view
            const customDropdown = rowToRemove.querySelector('.custom-dropdown');
            if (customDropdown) {
                populateCategories(customDropdown);
            }
        }
    }
}

// Category names for display (translation)
const categoryNames = {
    "Vati": "Vati (Pills)",
    "Churna": "Churna (Powders)",
    "Guggul": "Guggul (Resin Pills)",
    "Malam": "Malam (Ointments)",
    "Tail": "Tail (Oils)",
    "Kwath": "Kwath (Decoctions)",
    "Syrup": "Syrup",
    "Bhasma": "Bhasma (Calcined Powders)",
    "Ras": "Ras (Metallic/Mineral Preparations)",
    "Lepa": "Lepa (Pastes)",
};


// Initial display of the first step and progress bar
document.addEventListener('DOMContentLoaded', () => {
    showStep(currentStep);
    // Initialize dynamic text boxes based on default 'No' selection
    toggleTextBox('alcoholIntakeText', false);
    toggleTextBox('tobaccoUseText', false);
    toggleTextBox('travelHistoryText', false);
    toggleTextBox('bladderWaitText', false);
    toggleTextBox('bladderIncreasedFrequencyText', false);
    
    // Populate categories for the initial medicine row's custom dropdown
    const initialDropdown = document.getElementById('medicineDropdown_0');
    if (initialDropdown) {
        populateCategories(initialDropdown);
    }
});

// Form submission handler
document.getElementById('patientForm').addEventListener('submit', async function(event) {
    event.preventDefault(); // Prevent default form submission

    // If we are on the last step and submission is triggered, collect all data
    if (currentStep === totalSteps - 1 && validateForm()) {
        const form = event.target;
        const formData = new FormData(form);
        const data = {};

        // Special handling for chief complaints
        const chiefComplaints = [];
        const complaintInputs = form.querySelectorAll('input[name="chiefComplaint[]"]');
        const durationInputs = form.querySelectorAll('input[name="chiefComplaintDuration[]"]');

        for (let i = 0; i < complaintInputs.length; i++) {
            if (complaintInputs[i].value.trim() !== '' || durationInputs[i].value.trim() !== '') {
                chiefComplaints.push({
                    complaint: complaintInputs[i].value.trim(),
                    duration: durationInputs[i].value.trim()
                });
            }
        }
        if (chiefComplaints.length > 0) {
            data['chiefComplaint'] = chiefComplaints; // Changed to 'chiefComplaint' to match Laravel's expected input for validation
        }

        // Special handling for obstetric history
        data['obstetricHistory'] = {
            gravida: parseInt(form.elements.gravida.value) || 0,
            para: parseInt(form.elements.para.value) || 0,
            abortions: parseInt(form.elements.abortions.value) || 0,
            living: parseInt(form.elements.living.value) || 0,
            deaths: parseInt(form.elements.deaths.value) || 0
        };

        // Special handling for dynamic medicine rows (custom dropdowns)
        const medicines = [];
        const medicineRows = form.querySelectorAll('.treatment-item-row');
        medicineRows.forEach(row => {
            const medicineCategory = row.querySelector('.selected-category-input').value;
            const medicineName = row.querySelector('.selected-medicine-input').value;
            const doseMorning = row.querySelector('input[name="doseMorning[]"]').value;
            const doseNoon = row.querySelector('input[name="doseNoon[]"]').value;
            const doseNight = row.querySelector('input[name="doseNight[]"]').value;
            const anupaan = row.querySelector('input[name="medicineAnupaan[]"]').value;

            if (medicineName) { // Only add if a medicine is selected
                medicines.push({
                    category: medicineCategory,
                    name: medicineName,
                    dose: `${doseMorning}-${doseNoon}-${doseNight}`,
                    anupaan: anupaan
                });
            }
        });
        if (medicines.length > 0) {
            data['medicines'] = medicines;
        }


        // Iterate over other form data and populate the 'data' object
        for (let [key, value] of formData.entries()) {
            // Exclude dynamic chief complaint, obstetric history, and medicine row fields as they are handled above
            if (key !== 'chiefComplaint[]' && key !== 'chiefComplaintDuration[]' &&
                key !== 'gravida' && key !== 'para' && key !== 'abortions' && key !== 'living' && key !== 'deaths' &&
                key !== 'medicineCategory[]' && key !== 'medicineName[]' && // Exclude custom dropdown hidden inputs here
                key !== 'doseMorning[]' && key !== 'doseNoon[]' && key !== 'doseNight[]' && key !== 'medicineAnupaan[]') {
                // For checkboxes, collect all selected values into an array
                if (key === 'menstrualPainLevel') {
                    if (!data[key]) {
                        data[key] = [];
                    }
                    data[key].push(value);
                } else {
                    data[key] = value;
                }
            }
        }

        // Handle boolean fields from 'Yes'/'No' radio buttons
        data['bowelBloodMucus'] = form.querySelector('input[name="bowelBloodMucus"]:checked')?.value === 'Yes';
        data['bowelStraining'] = form.querySelector('input[name="bowelStraining"]:checked')?.value === 'Yes';
        data['bowelChange'] = form.querySelector('input[name="bowelChange"]:checked')?.value === 'Yes';
        data['bladderWait'] = form.querySelector('input[name="bladderWait"]:checked')?.value === 'Yes';
        data['bladderIncreasedFrequency'] = form.querySelector('input[name="bladderIncreasedFrequency"]:checked')?.value === 'Yes';

        // --- ADD THIS LINE FOR DEBUGGING THE URL ---
        console.log('Attempting to send data to:', patientStoreRoute);
        // --- END DEBUGGING LINE ---

        // Send data to Laravel backend using fetch API
        try {
            // Use the global variable patientStoreRoute which is defined in long-form.blade.php
            const response = await fetch(patientStoreRoute, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json', // Explicitly request JSON response
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') // Get CSRF token
                },
                body: JSON.stringify(data)
            });

            // Check if the response is actually JSON before parsing
            const contentType = response.headers.get("content-type");
            if (contentType && contentType.indexOf("application/json") !== -1) {
                const result = await response.json(); // Attempt to parse as JSON

                const successMessageDiv = document.getElementById('successMessage');
                const errorMessageDiv = document.getElementById('errorMessage'); // Get the error message div

                if (response.ok) { // Check if HTTP status is 2xx
                    successMessageDiv.textContent = result.message || 'Patient record saved successfully!';
                    successMessageDiv.classList.remove('hidden');
                    successMessageDiv.style.display = 'block';
                    if (errorMessageDiv) errorMessageDiv.style.display = 'none'; // Hide error message if success

                    // Reset form after successful submission
                    setTimeout(() => {
                        successMessageDiv.style.display = 'none';
                        form.reset();
                        currentStep = 0;
                        showStep(currentStep);
                        // Reset dynamic chief complaints to initial state
                        const chiefComplaintsContainer = document.getElementById('chiefComplaintsContainer');
                        chiefComplaintsContainer.innerHTML = `
                            <div class="chief-complaint-item">
                                <input type="text" name="chiefComplaint[]" class="form-input" placeholder="Chief complaint (max. 100 characters)" maxlength="100" required>
                                <input type="text" name="chiefComplaintDuration[]" class="form-input duration-input" placeholder="Duration" required>
                                <button type="button" class="remove-button" onclick="removeChiefComplaint(this)">Remove</button>
                                <p class="error-message chief-complaint-error">Complaint and duration are required.</p>
                            </div>
                        `;
                        // Reset dynamic text boxes to hidden
                        toggleTextBox('alcoholIntakeText', false);
                        toggleTextBox('tobaccoUseText', false);
                        toggleTextBox('travelHistoryText', false);
                        document.querySelector('input[name="alcoholIntake"][value="No"]').checked = true;
                        document.querySelector('input[name="tobaccoUse"][value="No"]').checked = true;
                        document.querySelector('input[name="travelHistory"][value="No"]').checked = true;
                        // Reset bladder function dynamic text boxes
                        toggleTextBox('bladderWaitText', false);
                        toggleTextBox('bladderIncreasedFrequencyText', false);
                        document.querySelector('input[name="bladderWait"][value="No"]').checked = true;
                        document.querySelector('input[name="bladderIncreasedFrequency"][value="No"]').checked = true;
                        
                        // Reset medicine rows
                        const medicineRowsContainer = document.getElementById('medicineRowsContainer');
                        medicineRowsContainer.innerHTML = `
                            <div class="treatment-item-row" data-row-index="0">
                                <div class="form-group flex-1">
                                    <label class="form-label">Medicine:</label>
                                    <div class="custom-dropdown" id="medicineDropdown_0">
                                        <div class="dropdown-header" onclick="toggleMainDropdown(this)">
                                            <span class="selected-value">Select Medicine</span>
                                            <span class="arrow">&#9660;</span>
                                        </div>
                                        <div class="dropdown-list">
                                            <div class="category-list-view">
                                                <!-- Categories will be populated here -->
                                            </div>
                                            <div class="medicine-list-view hidden">
                                                <div class="dropdown-item back-button-item" onclick="showCategories(this)">← Back to Categories</div>
                                                <!-- Medicines will be populated here -->
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
                        `;
                        medicineRowCount = 1;
                        const initialDropdownAfterReset = document.getElementById('medicineDropdown_0');
                        if (initialDropdownAfterReset) {
                            populateCategories(initialDropdownAfterReset);
                        }
                    }, 5000);
                } else {
                    // Handle non-2xx responses (e.g., validation errors, server errors)
                    let errorMessage = 'An error occurred. Please check your input.';
                    if (result.message) {
                        errorMessage = result.message; // Laravel validation errors often come with a message
                    } else if (result.errors) {
                        // If Laravel returns detailed validation errors, you can parse them
                        errorMessage = Object.values(result.errors).flat().join('\n');
                    }
                    
                    if (errorMessageDiv) {
                        errorMessageDiv.textContent = errorMessage;
                        errorMessageDiv.classList.remove('hidden');
                        errorMessageDiv.style.display = 'block';
                    } else {
                        alert(errorMessage); // Fallback to alert if no error div
                    }
                    successMessageDiv.style.display = 'none'; // Hide success message
                }
            } else {
                // If response is not JSON, read as text and log for debugging
                const errorText = await response.text();
                console.error('Server responded with non-JSON:', errorText);
                const errorMessageDiv = document.getElementById('errorMessage');
                if (errorMessageDiv) {
                    errorMessageDiv.textContent = 'Server error: Received unexpected response. Check console for details.';
                    errorMessageDiv.classList.remove('hidden');
                    errorMessageDiv.style.display = 'block';
                } else {
                    alert('Server error: Received unexpected response. Check console for details.');
                }
                successMessageDiv.style.display = 'none';
            }
        } catch (error) {
            console.error('Fetch error:', error);
            const errorMessageDiv = document.getElementById('errorMessage');
            if (errorMessageDiv) {
                errorMessageDiv.textContent = 'Network error or server unreachable. Please try again.';
                errorMessageDiv.classList.remove('hidden');
                errorMessageDiv.style.display = 'block';
            } else {
                alert('Network error or server unreachable. Please try again.');
            }
            const successMessageDiv = document.getElementById('successMessage');
            successMessageDiv.style.display = 'none'; // Hide success message
        }
    }
});
