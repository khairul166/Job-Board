function sendAjaxRequest(action, method, data, successCallback, errorCallback) {
    const xhr = new XMLHttpRequest();
    xhr.open(method, ajaxurl, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                try {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        if (successCallback) successCallback(response);
                    } else {
                        if (errorCallback) errorCallback(response.message);
                        else alert(response.message);
                    }
                } catch (e) {
                    if (errorCallback) errorCallback('Invalid server response');
                    else alert('Invalid server response');
                }
            } else {
                if (errorCallback) errorCallback('Request failed with status: ' + xhr.status);
                else alert('Request failed with status: ' + xhr.status);
            }
        }
    };

    // Add action and nonce to data
    data.action = action;
    data.nonce = profile_nonce;

    // Convert data object to URL-encoded string
    const formData = new URLSearchParams();
    for (const key in data) {
        if (data.hasOwnProperty(key)) {
            formData.append(key, data[key]);
        }
    }

    xhr.send(formData.toString());
}

// File upload AJAX function
function sendFileUploadRequest(action, formData, successCallback, errorCallback) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', ajaxurl, true);
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                try {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        if (successCallback) successCallback(response);
                    } else {
                        if (errorCallback) errorCallback(response.message);
                        else alert(response.message);
                    }
                } catch (e) {
                    if (errorCallback) errorCallback('Invalid server response');
                    else alert('Invalid server response');
                }
            } else {
                if (errorCallback) errorCallback('Request failed with status: ' + xhr.status);
                else alert('Request failed with status: ' + xhr.status);
            }
        }
    };

    // Add action and nonce to formData
    formData.append('action', action);
    formData.append('nonce', profile_nonce);

    xhr.send(formData);
}

// Helper function to show success messages
function showSuccessMessage(message) {
    const successMessage = document.createElement('div');
    successMessage.className = 'alert alert-success alert-dismissible fade show position-fixed';
    successMessage.style.top = '20px';
    successMessage.style.right = '20px';
    successMessage.style.zIndex = '9999';
    successMessage.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
    document.body.appendChild(successMessage);

    // Auto-hide after 3 seconds
    setTimeout(() => {
        successMessage.remove();
    }, 3000);
}


document.addEventListener('DOMContentLoaded', function () {

    // Check if we've already initialized the education form
    if (window.educationFormInitialized) {
        return;
    }

    // Set the flag
    window.educationFormInitialized = true;




    // About Me AJAX
    document.getElementById('aboutMeForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const aboutText = document.getElementById('aboutMeTextarea').value;

        sendAjaxRequest(
            'update_about_me',
            'POST',
            { about: aboutText },
            function (response) {
                document.querySelector('.profile-section p').innerText = aboutText;
                const modal = bootstrap.Modal.getInstance(document.getElementById('editAboutModal'));
                modal.hide();
                showSuccessMessage('About Me updated successfully!');
            },
            function (error) {
                alert('Error updating About Me: ' + error);
            }
        );
    });

    // Personal Information AJAX
    let contactNumberInstance, altContactInstance;

    // Initialize intlTelInput when modal is shown
    document.getElementById('editPersonalInfoModal').addEventListener('show.bs.modal', function () {
        setTimeout(function () {
            // Initialize Contact Number input
            const contactNumberInput = document.getElementById('contactNumber');
            if (contactNumberInput && !contactNumberInstance) {
                contactNumberInstance = window.intlTelInput(contactNumberInput, {
                    initialCountry: "bd",
                    utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
                    separateDialCode: true,
                    formatOnDisplay: true,
                    nationalMode: false
                });
            }

            // Initialize Alternative Contact Number input
            const altContactInput = document.getElementById('altContact');
            if (altContactInput && !altContactInstance) {
                altContactInstance = window.intlTelInput(altContactInput, {
                    initialCountry: "bd",
                    utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
                    separateDialCode: true,
                    formatOnDisplay: true,
                    nationalMode: false
                });
            }
        }, 100);
    });

    document.getElementById('personalInfoForm').addEventListener('submit', function (e) {
        e.preventDefault();

        // Get all form data
        const formData = {
            fullName: this.fullName.value,
            fatherName: this.fatherName.value,
            motherName: this.motherName.value,
            dob: this.dob.value,
            gender: this.gender.value,
            bloodGroup: this.bloodGroup.value,
            nationality: this.nationality.value,
            birthCountry: this.birthCountry.value,
            contactNumber: contactNumberInstance ? contactNumberInstance.getNumber() : '',
            altContact: altContactInstance ? altContactInstance.getNumber() : '',
            email: this.email.value,
            presentAddress: this.presentAddress.value,
            permanentAddress: this.permanentAddress.value,
            presentcity: this.presentcity.value,
            placeofbirth: this.placeofbirth.value,
        };

        sendAjaxRequest(
            'update_personal_info',
            'POST',
            formData,
            function (response) {
                updatePersonalInfoDisplay(formData);
                const modal = bootstrap.Modal.getInstance(document.getElementById('editPersonalInfoModal'));
                modal.hide();
                showSuccessMessage('Personal information updated successfully!');
            },
            function (error) {
                alert('Error updating personal information: ' + error);
            }
        );
    });

    // Function to update the personal information display
    function updatePersonalInfoDisplay(data) {
        // Update each field directly
        updateField('fullName', data.fullName);
        updateField('fatherName', data.fatherName);
        updateField('motherName', data.motherName);
        updateField('dob', formatDate(data.dob));
        updateField('gender', data.gender);
        updateField('bloodGroup', data.bloodGroup);
        updateField('nationality', data.nationality);
        updateField('birthCountry', data.birthCountry);
        updateField('contactNumber', data.contactNumber);
        updateField('altContact', data.altContact);
        updateField('email', data.email);
        updateField('presentAddress', data.presentAddress);
        updateField('permanentAddress', data.permanentAddress);
        updateField('presentcity', data.presentcity);
        updateField('placeofbirth', data.placeofbirth);
    }

    // Helper function to update a field by form field name
    function updateField(fieldName, value) {
        // Map form field names to display field indices
        const fieldMap = {
            'fullName': { column: 0, index: 0 },
            'fatherName': { column: 0, index: 1 },
            'motherName': { column: 0, index: 2 },
            'dob': { column: 0, index: 3 },
            'gender': { column: 0, index: 4 },
            'bloodGroup': { column: 0, index: 5 },
            'nationality': { column: 0, index: 6 },
            'birthCountry': { column: 0, index: 7 },
            'contactNumber': { column: 1, index: 0 },
            'altContact': { column: 1, index: 1 },
            'email': { column: 1, index: 2 },
            'presentAddress': { column: 1, index: 3 },
            'permanentAddress': { column: 1, index: 4 },
            'presentcity': { column: 1, index: 5 },
            'placeofbirth': { column: 1, index: 6 }
        };

        const fieldInfo = fieldMap[fieldName];
        if (!fieldInfo) return;

        // Get the column
        const columns = document.querySelectorAll('.personalinfo-section .col-sm-6');
        if (fieldInfo.column >= columns.length) return;

        const column = columns[fieldInfo.column];

        // Get all field containers in this column
        const fieldContainers = column.querySelectorAll('.mb-4');
        if (fieldInfo.index >= fieldContainers.length) return;

        const container = fieldContainers[fieldInfo.index];

        // Find the p element and update its text
        const p = container.querySelector('p');
        if (p) {
            p.textContent = value;
        }
    }

    // Helper function to format date
    function formatDate(dateString) {
        if (!dateString) return '';
        const date = new Date(dateString);
        const day = date.getDate().toString().padStart(2, '0');
        const month = (date.getMonth() + 1).toString().padStart(2, '0');
        const year = date.getFullYear();
        return `${day}/${month}/${year}`;
    }

    // ============ Education AJAX Start ============//
    // Education AJAX
    const educationForm = document.getElementById('educationForm');
    const hiddenIdInput = document.createElement('input');
    hiddenIdInput.type = 'hidden';
    hiddenIdInput.id = 'educationId';
    hiddenIdInput.name = 'educationId';
    educationForm.appendChild(hiddenIdInput);

    // Generate year options
    const yearSelect = document.querySelector('select[name="passing_year[]"]');
    const currentYear = new Date().getFullYear();

    // Generate options from current year back 20 years
    for (let year = currentYear; year >= currentYear - 20; year--) {
        const option = document.createElement('option');
        option.value = year;
        option.textContent = year;
        yearSelect.appendChild(option);
    }

    // Handle GPA input visibility
    const resultSelect = document.getElementById('resultSelect');
    const gpaPointsInput = document.getElementById('gpaPoints');

    resultSelect.addEventListener('change', function () {
        if (this.value === 'gpa4' || this.value === 'gpa5') {
            gpaPointsInput.classList.remove('d-none');
            gpaPointsInput.required = true;
        } else {
            gpaPointsInput.classList.add('d-none');
            gpaPointsInput.required = false;
        }
    });

    // Add class to existing education entries (don't change IDs)
    const existingEducation = document.querySelectorAll('.education-section .mb-4.border-bottom');
    existingEducation.forEach((entry) => {
        entry.classList.add('education-item');
        // Don't modify the data-id - it should already be set correctly by the template
    });

    // Flag to prevent multiple submissions
    let isSubmitting = false;

    // Handle form submission - Use event delegation to avoid duplicate listeners
    document.addEventListener('submit', function (e) {
        // Check if the submitted form is the education form
        if (e.target && e.target.id === 'educationForm') {
            e.preventDefault();

            // Prevent multiple submissions
            if (isSubmitting) {
                console.log('Form already being submitted');
                return;
            }

            isSubmitting = true;

            // Get form values
            const educationId = document.getElementById('educationId').value;
            const edulevel = document.querySelector('select[name="edulevel[]"]').value;
            const degree = document.querySelector('input[name="degree[]"]').value.trim();
            const institution = document.querySelector('input[name="institution[]"]').value.trim();
            const majorsub = document.querySelector('input[name="majorsub[]"]').value.trim();
            const passing_year = document.querySelector('select[name="passing_year[]"]').value;
            const result = document.querySelector('select[name="result[]"]').value;
            const gpapoint = document.querySelector('input[name="gpapoint[]"]').value.trim();

            // Validate required fields
            if (!edulevel || !degree || !institution || !majorsub || !passing_year || !result) {
                alert('Please fill in all required fields.');
                isSubmitting = false;
                return;
            }

            // Validate GPA if required
            if ((result === 'gpa4' || result === 'gpa5') && !gpapoint) {
                alert('Please enter GPA points.');
                isSubmitting = false;
                return;
            }

            const formData = {
                educationId: educationId,
                edulevel: edulevel,
                degree: degree,
                institution: institution,
                majorsub: majorsub,
                passing_year: passing_year,
                result: result,
                gpapoint: gpapoint
            };

            // Disable submit button to prevent double submission
            const submitButton = educationForm.querySelector('button[type="submit"]');
            submitButton.disabled = true;
            submitButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';

            sendAjaxRequest(
                'update_education',
                'POST',
                formData,
                function (response) {
                    // response is the data part of the server response
                    const newId = response.id || 'edu_' + Date.now();

                    if (educationId) {
                        // Update existing entry
                        updateEducationEntry(educationId, degree, institution, majorsub, passing_year, result, gpapoint);
                    } else {
                        // Add new entry with the ID from the server
                        addEducationEntry(newId, degree, institution, majorsub, passing_year, result, gpapoint);
                    }

                    // Reset form and close modal
                    educationForm.reset();
                    document.getElementById('educationId').value = '';
                    gpaPointsInput.classList.add('d-none');
                    const modal = bootstrap.Modal.getInstance(document.getElementById('editEducationModal'));
                    modal.hide();
                    showSuccessMessage(response.message || 'Education updated successfully!');

                    // Re-enable submit button
                    submitButton.disabled = false;
                    submitButton.innerHTML = 'Save Changes';

                    // Reset submission flag
                    isSubmitting = false;
                },
                function (error) {
                    alert('Error updating education: ' + error);
                    // Re-enable submit button
                    submitButton.disabled = false;
                    submitButton.innerHTML = 'Save Changes';

                    // Reset submission flag
                    isSubmitting = false;
                }
            );
        }
    });

    // Function to add new education entry
    function addEducationEntry(id, degree, institution, majorsub, passing_year, result, gpapoint) {
        const educationSection = document.querySelector('.education-section');

        // Check if entry already exists to prevent duplicates
        if (document.querySelector(`.education-item[data-id="${id}"]`)) {
            console.warn('Education entry with ID ' + id + ' already exists');
            return;
        }

        // If there's a "No education information" message, remove it
        const noEducationMsg = educationSection.querySelector('p');
        if (noEducationMsg && noEducationMsg.textContent.includes('No education information')) {
            noEducationMsg.remove();
        }

        const educationEntry = document.createElement('div');
        educationEntry.className = 'mb-4 pb-3 border-bottom d-flex justify-content-between align-items-start education-item';
        educationEntry.setAttribute('data-id', id);

        // Format result text
        let resultText = '';
        if (result === 'gpa4' || result === 'gpa5') {
            resultText = `GPA: ${gpapoint}/${result === 'gpa4' ? '4.0' : '5.0'}`;
        } else {
            // Format division/class text
            resultText = result.replace(/([0-9]+)(st|nd|rd|th)([a-z]+)/, '$1$2 $3');
            resultText = resultText.charAt(0).toUpperCase() + resultText.slice(1);
        }

        // Create description
        let description = '';
        if (majorsub) {
            description = `Specialized in ${majorsub}. `;
        }
        description += resultText;

        educationEntry.innerHTML = `
        <div>
            <h5>${degree}</h5>
            <p class="text-muted mb-1">${institution} • ${passing_year}</p>
            <p>${description}</p>
        </div>
        <div class="ms-3 text-nowrap">
            <button class="btn btn-sm btn-outline-secondary me-1" data-bs-toggle="modal" data-bs-target="#editEducationModal">
                <i class="fas fa-edit"></i>
            </button>
            <button class="btn btn-sm btn-outline-danger">
                <i class="fas fa-trash-alt"></i>
            </button>
        </div>
    `;

        // Add to section
        educationSection.appendChild(educationEntry);
    }

    // Function to update existing education entry
    function updateEducationEntry(educationId, degree, institution, majorsub, passing_year, result, gpapoint) {
        const educationEntry = document.querySelector(`.education-item[data-id="${educationId}"]`);

        if (!educationEntry) {
            console.error('Education entry not found for ID:', educationId);
            return;
        }

        // Format result text
        let resultText = '';
        if (result === 'gpa4' || result === 'gpa5') {
            resultText = `GPA: ${gpapoint}/${result === 'gpa4' ? '4.0' : '5.0'}`;
        } else {
            // Format division/class text
            resultText = result.replace(/([0-9]+)(st|nd|rd|th)([a-z]+)/, '$1$2 $3');
            resultText = resultText.charAt(0).toUpperCase() + resultText.slice(1);
        }

        // Create description
        let description = '';
        if (majorsub) {
            description = `Specialized in ${majorsub}. `;
        }
        description += resultText;

        // Update entry
        educationEntry.querySelector('h5').textContent = degree;
        educationEntry.querySelector('.text-muted').textContent = `${institution} • ${passing_year}`;
        educationEntry.querySelector('p:last-of-type').textContent = description;
    }

    // Handle edit button clicks for education
    document.addEventListener('click', function (e) {
        // Check if the clicked element is an edit button in the education section
        if (e.target.closest('.education-section .btn-outline-secondary')) {
            // Prevent event propagation to avoid triggering other handlers
            e.stopPropagation();

            const educationEntry = e.target.closest('.mb-4');
            const educationId = educationEntry.getAttribute('data-id');

            // Set ID in form
            document.getElementById('educationId').value = educationId;

            // Get values from entry
            const title = educationEntry.querySelector('h5').textContent;
            const subtitle = educationEntry.querySelector('.text-muted').textContent;
            const description = educationEntry.querySelector('p:last-of-type').textContent;

            // Parse degree and institution/year
            let degree = title;
            let institution = "";
            let year = "";

            // Extract institution and year from subtitle
            const subtitleParts = subtitle.split(' • ');
            if (subtitleParts.length >= 2) {
                institution = subtitleParts[0];
                year = subtitleParts[1];
            }

            // Parse description for major and result
            let major = "";
            let result = "";
            let gpa = "";

            // Check if description contains "Specialized in"
            const specializedMatch = description.match(/Specialized in ([^.]+)\./);
            if (specializedMatch) {
                major = specializedMatch[1];
            }

            // Check for GPA
            const gpaMatch = description.match(/GPA: ([^\/]+)\/([0-9.]+)/);
            if (gpaMatch) {
                gpa = gpaMatch[1];
                const scale = gpaMatch[2];
                result = scale === '4.0' ? 'gpa4' : 'gpa5';
            } else {
                // Check for other result types
                const resultMatch = description.match(/([0-9]+)(st|nd|rd|th) ([Dd]ivision|[Cc]lass)/);
                if (resultMatch) {
                    const resultNumber = resultMatch[1];
                    const resultType = resultMatch[3].toLowerCase();
                    result = resultNumber + resultType;
                } else {
                    // Check for status types
                    const statusMatch = description.match(/(Appeared|Enrolled|Awarded)/);
                    if (statusMatch) {
                        result = statusMatch[1].toLowerCase();
                    }
                }
            }

            // Fill form fields
            document.querySelector('input[name="degree[]"]').value = degree;
            document.querySelector('input[name="institution[]"]').value = institution;
            document.querySelector('input[name="majorsub[]"]').value = major;
            document.querySelector('select[name="passing_year[]"]').value = year;

            // Set result type
            if (gpa) {
                document.querySelector('select[name="result[]"]').value = result;
                document.querySelector('input[name="gpapoint[]"]').value = gpa;
                gpaPointsInput.classList.remove('d-none');
            } else if (result) {
                document.querySelector('select[name="result[]"]').value = result;
                gpaPointsInput.classList.add('d-none');
            }

            // Update modal title
            document.getElementById('editEducationModalLabel').textContent = 'Edit Education';
        }
    });

    // Handle delete button clicks for education
    document.addEventListener('click', function (e) {
        // Check if the clicked element is a delete button in the education section
        if (e.target.closest('.education-section .btn-outline-danger')) {
            // Prevent event propagation to avoid triggering other handlers
            e.stopPropagation();

            const educationEntry = e.target.closest('.mb-4');
            const educationId = educationEntry.getAttribute('data-id');

            // Show confirmation modal
            const deleteModal = document.getElementById('deleteConfirmationModal');
            deleteModal.setAttribute('data-item-id', educationId);
            deleteModal.setAttribute('data-item-type', 'education');

            const modalInstance = new bootstrap.Modal(deleteModal);
            modalInstance.show();
        }
    });
    // ================== education ajax end =======================//


    // Training AJAX
    const trainingForm = document.getElementById('trainingForm');
    const trainingHiddenIdInput = document.createElement('input');
    trainingHiddenIdInput.type = 'hidden';
    trainingHiddenIdInput.id = 'trainingId';
    trainingHiddenIdInput.name = 'trainingId';
    trainingForm.appendChild(trainingHiddenIdInput);

    // Add class to existing training entries (don't change IDs)
    const existingTraining = document.querySelectorAll('.training-section .mb-4.border-bottom');
    existingTraining.forEach((entry) => {
        entry.classList.add('training-item');
        // Don't modify the data-id - it should already be set correctly by the template
    });

    // Flag to prevent multiple submissions
    let isTrainingSubmitting = false;

    // Handle form submission - Use event delegation to avoid duplicate listeners
    document.addEventListener('submit', function (e) {
        // Check if the submitted form is the training form
        if (e.target && e.target.id === 'trainingForm') {
            console.log('Training form submitted');

            e.preventDefault();

            // Prevent multiple submissions
            if (isTrainingSubmitting) {
                console.log('Training form already being submitted');
                return;
            }

            isTrainingSubmitting = true;
            console.log('Starting training form submission');

            // Get form values
            const trainingId = document.getElementById('trainingId').value;
            const title = document.getElementById('trainingTitle').value.trim();
            const institution = document.getElementById('institution').value.trim();
            const startYear = document.getElementById('startYear').value;
            const endYear = document.getElementById('endYear').value;
            const description = document.getElementById('description').value.trim();

            console.log('Training form data:', {
                trainingId, title, institution, startYear, endYear, description
            });

            // Validate required fields
            if (!title || !institution || !startYear || !endYear || !description) {
                alert('Please fill in all required fields.');
                isTrainingSubmitting = false;
                return;
            }

            // Validate date range
            if (new Date(startYear) > new Date(endYear)) {
                alert('Start year cannot be after end year.');
                isTrainingSubmitting = false;
                return;
            }

            const formData = {
                trainingId: trainingId,
                title: title,
                institution: institution,
                startYear: startYear,
                endYear: endYear,
                description: description
            };

            // Disable submit button to prevent double submission
            const submitButton = trainingForm.querySelector('button[type="submit"]');
            submitButton.disabled = true;
            submitButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';

            sendAjaxRequest(
                'update_training',
                'POST',
                formData,
                function (response) {
                    console.log('Training AJAX success:', response);

                    // response is the data part of the server response
                    const newId = response.id || 'training_' + Date.now();

                    if (trainingId) {
                        console.log('Updating existing training entry with ID:', trainingId);
                        // Update existing entry
                        updateTrainingEntry(trainingId, title, institution, startYear, endYear, description);
                    } else {
                        console.log('Adding new training entry with ID:', newId);
                        // Add new entry with the ID from the server
                        addTrainingEntry(newId, title, institution, startYear, endYear, description);
                    }

                    // Reset form and close modal
                    trainingForm.reset();
                    document.getElementById('trainingId').value = '';
                    const modal = bootstrap.Modal.getInstance(document.getElementById('editTrainingModal'));
                    modal.hide();
                    showSuccessMessage(response.message || 'Training updated successfully!');

                    // Re-enable submit button
                    submitButton.disabled = false;
                    submitButton.innerHTML = 'Save Training';

                    // Reset submission flag
                    isTrainingSubmitting = false;
                },
                function (error) {
                    console.error('Training AJAX error:', error);
                    alert('Error updating training: ' + error);
                    // Re-enable submit button
                    submitButton.disabled = false;
                    submitButton.innerHTML = 'Save Training';

                    // Reset submission flag
                    isTrainingSubmitting = false;
                }
            );
        }
    });

    // Function to add new training entry
    function addTrainingEntry(id, title, institution, startYear, endYear, description) {
        const trainingSection = document.querySelector('.training-section');

        // Check if entry already exists to prevent duplicates
        if (document.querySelector(`.training-item[data-id="${id}"]`)) {
            console.warn('Training entry with ID ' + id + ' already exists');
            return;
        }

        // If there's a "No training information" message, remove it
        const noTrainingMsg = trainingSection.querySelector('p');
        if (noTrainingMsg && noTrainingMsg.textContent.includes('No training information')) {
            noTrainingMsg.remove();
        }

        const trainingEntry = document.createElement('div');
        trainingEntry.className = 'mb-4 border-bottom pb-3 d-flex justify-content-between align-items-start training-item';
        trainingEntry.setAttribute('data-id', id);

        // Format years for display
        const startYearDisplay = new Date(startYear).getFullYear();
        const endYearDisplay = new Date(endYear).getFullYear();

        trainingEntry.innerHTML = `
        <div>
            <h5>${title}</h5>
            <p class="text-muted mb-1">${institution} • ${startYearDisplay} - ${endYearDisplay}</p>
            <p>${description}</p>
        </div>
        <div class="ms-3 text-nowrap">
            <button class="btn btn-sm btn-outline-secondary me-1" data-bs-toggle="modal" data-bs-target="#editTrainingModal">
                <i class="fas fa-edit"></i>
            </button>
            <button class="btn btn-sm btn-outline-danger">
                <i class="fas fa-trash-alt"></i>
            </button>
        </div>
    `;

        // Add to section
        trainingSection.appendChild(trainingEntry);
    }

    // Function to update existing training entry
    function updateTrainingEntry(trainingId, title, institution, startYear, endYear, description) {
        const trainingEntry = document.querySelector(`.training-item[data-id="${trainingId}"]`);

        if (!trainingEntry) {
            console.error('Training entry not found for ID:', trainingId);
            return;
        }

        // Format years for display
        const startYearDisplay = new Date(startYear).getFullYear();
        const endYearDisplay = new Date(endYear).getFullYear();

        // Update entry
        trainingEntry.querySelector('h5').textContent = title;
        trainingEntry.querySelector('.text-muted').textContent = `${institution} • ${startYearDisplay} - ${endYearDisplay}`;
        trainingEntry.querySelector('p:last-of-type').textContent = description;
    }

    // Handle edit button clicks for training
    document.addEventListener('click', function (e) {
        // Check if the clicked element is an edit button in the training section
        if (e.target.closest('.training-section .btn-outline-secondary')) {
            // Prevent event propagation to avoid triggering other handlers
            e.stopPropagation();

            const trainingEntry = e.target.closest('.mb-4');
            const trainingId = trainingEntry.getAttribute('data-id');

            console.log('Edit button clicked for training ID:', trainingId);

            // Set ID in form
            document.getElementById('trainingId').value = trainingId;

            // Get values from entry
            const title = trainingEntry.querySelector('h5').textContent;
            const subtitle = trainingEntry.querySelector('.text-muted').textContent;
            const description = trainingEntry.querySelector('p:last-of-type').textContent;

            // Parse institution, years, and duration
            let institution = "";
            let startDate = "";
            let endDate = "";
            let duration = "";

            // Extract institution and date info from subtitle
            const subtitleParts = subtitle.split(' • ');
            if (subtitleParts.length >= 2) {
                institution = subtitleParts[0].trim();

                // The last part could be either duration or date range
                const lastPart = subtitleParts[subtitleParts.length - 1].trim();

                // Check if last part is a duration (contains "year" or "month")
                if (lastPart.includes('year') || lastPart.includes('month')) {
                    duration = lastPart;
                    // Look for date range in other parts
                    for (let i = 1; i < subtitleParts.length - 1; i++) {
                        const part = subtitleParts[i].trim();
                        if (part.includes(' - ')) {
                            const dateParts = part.split(' - ');
                            startDate = dateParts[0].trim();
                            endDate = dateParts[1].trim();
                            break;
                        }
                    }
                } else if (lastPart.includes(' - ')) {
                    // If no duration shown, but has date range
                    const dateParts = lastPart.split(' - ');
                    startDate = dateParts[0].trim();
                    endDate = dateParts[1].trim();
                }
            }

            console.log('Filling form with data:', {
                title, institution, startDate, endDate, duration, description
            });

            // Fill form fields
            document.getElementById('trainingTitle').value = title;
            document.getElementById('institution').value = institution;
            document.getElementById('startDate').value = formatDateForInput(startDate);
            document.getElementById('endDate').value = formatDateForInput(endDate);
            document.getElementById('trainingDuration').textContent = duration || 'Duration will be calculated';
            document.getElementById('description').value = description;

            // Update modal title
            document.getElementById('editTrainingModalLabel').textContent = 'Edit Training';
        }
    });

    // Helper function to format date for input fields (YYYY-MM-DD)
    function formatDateForInput(dateString) {
        if (!dateString) return '';

        // Try parsing different date formats
        const date = new Date(dateString);
        if (isNaN(date.getTime())) {
            // If not a valid date, try parsing just year
            const yearMatch = dateString.match(/\d{4}/);
            if (yearMatch) {
                return `${yearMatch[0]}-01-01`; // Default to Jan 1st if only year is provided
            }
            return '';
        }

        // Format as YYYY-MM-DD
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');

        return `${year}-${month}-${day}`;
    }

    // Handle delete button clicks for training
    document.addEventListener('click', function (e) {
        // Check if the clicked element is a delete button in the training section
        if (e.target.closest('.training-section .btn-outline-danger')) {
            // Prevent event propagation to avoid triggering other handlers
            e.stopPropagation();

            const trainingEntry = e.target.closest('.mb-4');
            const trainingId = trainingEntry.getAttribute('data-id');


            // Show confirmation modal
            const deleteModal = document.getElementById('deleteConfirmationModal');
            deleteModal.setAttribute('data-item-id', trainingId);
            deleteModal.setAttribute('data-item-type', 'training');

            const modalInstance = new bootstrap.Modal(deleteModal);
            modalInstance.show();
        }
    });


    // Helper function to format date for input
    function formatDateForInput(yearStr) {
        // If the year is just a number (like "2015"), convert to a date string format
        if (/^\d{4}$/.test(yearStr)) {
            return `${yearStr}-01-01`; // Default to January 1st of that year
        }
        return yearStr; // Return as-is if already in a different format
    }




    // // Skills AJAX
    // document.getElementById('saveSkills').addEventListener('click', function () {
    //     const skillsInput = document.getElementById('skillsInput').value;

    //     if (skillsInput.trim()) {
    //         const skills = skillsInput.split(',').map(skill => skill.trim()).filter(skill => skill !== '');

    //         sendAjaxRequest(
    //             'update_skills',
    //             'POST',
    //             { skills: skills.join(',') },
    //             function (response) {
    //                 // Get the container where badges should be added
    //                 const skillContainer = document.querySelector('.profile-section .skill-section');

    //                 if (skillContainer) {
    //                     // Clear existing skills
    //                     skillContainer.innerHTML = '';

    //                     // Add each skill as a badge
    //                     skills.forEach(skill => {
    //                         const badge = document.createElement('span');
    //                         badge.className = 'skill-badge rounded-pill';
    //                         badge.textContent = skill;
    //                         skillContainer.appendChild(badge);
    //                     });
    //                 }

    //                 // Reset the form and close modal
    //                 document.getElementById('skillsForm').reset();
    //                 const modal = bootstrap.Modal.getInstance(document.getElementById('addSkillsModal'));
    //                 modal.hide();
    //                 showSuccessMessage('Skills updated successfully!');
    //             },
    //             function (error) {
    //                 alert('Error updating skills: ' + error);
    //             }
    //         );
    //     }
    // });


    // Skills AJAX

// Add multiple skills
document.getElementById('saveSkills').addEventListener('click', function () {
    const input = document.getElementById('skillsInput').value;
    if (!input.trim()) return;

    // Split by comma, trim, filter empty
    const newSkills = input.split(',').map(s => s.trim()).filter(s => s);

    if (newSkills.length === 0) return;

    sendAjaxRequest(
        'add_skills',
        'POST',
        { skills: newSkills },
        function (response) {
            // Append each new skill to DOM
            const skillSection = document.querySelector('.skill-section');
            newSkills.forEach(skill => {
                const span = document.createElement('span');
                span.className = 'skill-badge rounded-pill';
                span.innerHTML = `${skill}
                    <button type="button" class="btn btn-sm btn-link text-danger ms-2 p-0 remove-skill-btn" data-skill="${skill}" title="Remove">
                        <i class="fas fa-times"></i>
                    </button>`;
                skillSection.appendChild(span);
            });
            document.getElementById('skillsInput').value = '';
            bootstrap.Modal.getInstance(document.getElementById('addSkillsModal')).hide();
            showSuccessMessage('Skills added successfully!');
        },
        function (error) {
            alert('Error adding skills: ' + error);
        }
    );
});

// Remove skill
document.addEventListener('click', function (e) {
    if (e.target.closest('.remove-skill-btn')) {
        const btn = e.target.closest('.remove-skill-btn');
        const skill = btn.getAttribute('data-skill');
        sendAjaxRequest(
            'remove_skill',
            'POST',
            { skill: skill },
            function (response) {
                btn.parentElement.remove(); // Remove badge from DOM
                showSuccessMessage('Skill removed successfully!');
            },
            function (error) {
                alert('Error removing skill: ' + error);
            }
        );
    }
});

// Add multiple languages
document.getElementById('saveLangguage').addEventListener('click', function () {
    const input = document.getElementById('langguageInput').value;
    if (!input.trim()) return;

    // Split by comma, trim, filter empty
    const newLanguages = input.split(',').map(s => s.trim()).filter(s => s);

    if (newLanguages.length === 0) return;

    sendAjaxRequest(
        'add_languages',
        'POST',
        { languages: newLanguages },
        function (response) {
            // Append each new language to DOM
            const langSection = document.querySelector('.langguage-section');
            newLanguages.forEach(language => {
                const span = document.createElement('span');
                span.className = 'langguage-badge rounded-pill';
                span.innerHTML = `${language}
                    <button type="button" class="btn btn-sm btn-link text-danger ms-2 p-0 remove-language-btn" data-language="${language}" title="Remove">
                        <i class="fas fa-times"></i>
                    </button>`;
                langSection.appendChild(span);
            });
            document.getElementById('langguageInput').value = '';
            bootstrap.Modal.getInstance(document.getElementById('addlangguageModal')).hide();
            showSuccessMessage('Languages added successfully!');
        },
        function (error) {
            alert('Error adding languages: ' + error);
        }
    );
});

// Remove language
document.addEventListener('click', function (e) {
    if (e.target.closest('.remove-language-btn')) {
        const btn = e.target.closest('.remove-language-btn');
        const language = btn.getAttribute('data-language');
        sendAjaxRequest(
            'remove_language',
            'POST',
            { language: language },
            function (response) {
                btn.parentElement.remove(); // Remove badge from DOM
                showSuccessMessage('Language removed successfully!');
            },
            function (error) {
                alert('Error removing language: ' + error);
            }
        );
    }
});
    // References AJAX
    const referenceForm = document.getElementById('referenceForm');
    const referenceHiddenIdInput = document.createElement('input');
    referenceHiddenIdInput.type = 'hidden';
    referenceHiddenIdInput.id = 'referenceId';
    referenceHiddenIdInput.name = 'referenceId';
    referenceForm.appendChild(referenceHiddenIdInput);


    // Handle save button click
    document.getElementById('saveReference').addEventListener('click', function () {
        const referenceId = document.getElementById('referenceId').value;
        const name = document.getElementById('referenceName').value.trim();
        const position = document.getElementById('referencePosition').value.trim();
        const company = document.getElementById('referenceCompany').value.trim();
        const email = document.getElementById('referenceEmail').value.trim();
        const phone = document.getElementById('referencePhone').value.trim();

        // Validate required fields
        if (!name || !position || !company || !email) {
            alert('Please fill in all required fields.');
            return;
        }

        // Email validation
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            alert('Please enter a valid email address.');
            return;
        }

        const formData = {
            referenceId: referenceId,
            name: name,
            position: position,
            company: company,
            email: email,
            phone: phone
        };

        sendAjaxRequest(
            'update_references',
            'POST',
            formData,
            function (response) {
                if (referenceId) {
                    // Editing existing reference
                    const referenceEntry = document.querySelector(`.reference-entry[data-id="${referenceId}"]`);
                    if (referenceEntry) {
                        updateReferenceEntry(referenceEntry, name, position, company, email, phone);
                    }
                } else {
                    // Adding new reference
                    addReferenceEntry(name, position, company, email, phone);
                }

                // Reset form and close modal
                referenceForm.reset();
                document.getElementById('referenceId').value = '';
                const modal = bootstrap.Modal.getInstance(document.getElementById('referenceModal'));
                modal.hide();
                showSuccessMessage('Reference updated successfully!');
            },
            function (error) {
                alert('Error updating reference: ' + error);
            }
        );
    });

    // Function to add a new reference entry
    function addReferenceEntry(name, position, company, email, phone) {
        const referencesSection = document.querySelector('.reference-section');
        const referenceId = 'ref_' + Date.now();

        const referenceEntry = document.createElement('div');
        referenceEntry.className = 'mb-4 border-bottom pb-3 d-flex justify-content-between align-items-start reference-entry';
        referenceEntry.setAttribute('data-id', referenceId);

        referenceEntry.innerHTML = `
            <div>
                <h5>${name}</h5>
                <p class="text-muted mb-1">${position}, ${company}</p>
                <p><i class="fas fa-envelope me-2"></i>${email}</p>
                ${phone ? `<p><i class="fas fa-phone me-2"></i>${phone}</p>` : ''}
            </div>
            <div class="ms-3 text-nowrap">
                <button class="btn btn-sm btn-outline-secondary me-1 edit-reference-btn" data-bs-toggle="modal" data-bs-target="#referenceModal">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn btn-sm btn-outline-danger delete-reference-btn">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </div>
        `;

        referencesSection.appendChild(referenceEntry);
    }

    // Function to update an existing reference entry
    function updateReferenceEntry(referenceEntry, name, position, company, email, phone) {
        referenceEntry.querySelector('h5').textContent = name;
        referenceEntry.querySelector('.text-muted').textContent = `${position}, ${company}`;

        const emailElement = referenceEntry.querySelector('p:has(.fa-envelope)');
        const phoneElement = referenceEntry.querySelector('p:has(.fa-phone)');

        if (emailElement) {
            emailElement.innerHTML = `<i class="fas fa-envelope me-2"></i>${email}`;
        } else if (email) {
            const newEmailElement = document.createElement('p');
            newEmailElement.innerHTML = `<i class="fas fa-envelope me-2"></i>${email}`;
            referenceEntry.querySelector('div').appendChild(newEmailElement);
        }

        if (phoneElement) {
            phoneElement.innerHTML = `<i class="fas fa-phone me-2"></i>${phone}`;
        } else if (phone) {
            const newPhoneElement = document.createElement('p');
            newPhoneElement.innerHTML = `<i class="fas fa-phone me-2"></i>${phone}`;
            referenceEntry.querySelector('div').appendChild(newPhoneElement);
        }
    }

    // Resume Upload AJAX
    document.getElementById('resumeUploadForm').addEventListener('submit', function (e) {
        e.preventDefault();
        e.stopPropagation(); // Prevent event bubbling

        console.log('Resume form submitted'); // Add this for debugging

        const fileInput = document.getElementById('resumeFile');
        const message = document.getElementById('resumeUploadMessage');
        const resumeuploadsection = document.getElementById('resume-upload-section');

        if (fileInput.files.length === 0) {
            alert('Please select a file to upload.');
            return;
        }

        const file = fileInput.files[0];
        const allowedExtensions = ['pdf', 'doc', 'docx'];
        const fileSizeLimit = 5 * 1024 * 1024; // 5MB
        const fileExt = file.name.split('.').pop().toLowerCase();

        if (!allowedExtensions.includes(fileExt)) {
            alert('Invalid file type. Please upload PDF, DOC, or DOCX.');
            return;
        }

        if (file.size > fileSizeLimit) {
            alert('File size exceeds 5MB limit.');
            return;
        }

        const formData = new FormData();
        formData.append('resumeFile', file);

        // Disable submit button to prevent double submission
        const submitButton = this.querySelector('button[type="submit"]');
        const originalButtonText = submitButton.innerHTML;
        submitButton.disabled = true;
        submitButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Uploading...';

        sendFileUploadRequest(
            'upload_resume',
            formData,
            function (response) {
                console.log('Resume upload successful:', response);

                try {
                    // Hide the placeholder message if it exists
                    const placeholder = document.querySelector('.no-resume-placeholder');
                    if (placeholder) {
                        placeholder.style.display = 'none';
                    }

                    // Get the resume section
                    const resumeSection = document.querySelector('.resume-section');
                    const resumepreviewbt = document.querySelector('.resume_upload-btn');
                    if (!resumeSection) {
                        throw new Error('Resume section not found');
                    }

                    // Get or create the resume preview
                    let resumePreview = document.querySelector('.resume-preview');
                    if (!resumePreview) {
                        resumePreview = document.createElement('div');
                        resumePreview.className = 'resume-preview mb-3';
                        resumeSection.appendChild(resumePreview);
                    }

                    // Get or create the resume actions
                    let resumeActions = document.querySelector('.resume-actions');
                    if (!resumeActions) {
                        resumeActions = document.createElement('div');
                        resumeActions.className = 'd-grid gap-2 resume-actions';
                        resumeSection.appendChild(resumeActions);
                    }

                    // Update the resume preview content
                    const fileName = response.data.filename;
                    const currentDate = new Date();
                    const formattedDate = currentDate.toLocaleDateString('en-US', {
                        year: 'numeric',
                        month: 'short',
                        day: 'numeric'
                    });

                    resumePreview.innerHTML = `
            <i class="fas fa-file-pdf fa-3x text-danger mb-2"></i>
            <p class="mb-1">${fileName}</p>
            <small class="text-muted">Uploaded: ${formattedDate}</small>
        `;

                    // Update the resume actions
                    resumeActions.innerHTML = `
            <a href="${response.data.url}" class="btn btn-outline-primary" download>
                <i class="fas fa-download me-1"></i> Download
            </a>
            <button class="btn btn-outline-danger" id="removeResume">
                <i class="fas fa-trash me-1"></i> Remove
            </button>
        `;

                    // Make sure both elements are visible
                    resumePreview.style.display = 'block';
                    resumeActions.style.display = 'block';
                    resumepreviewbt.style.display = 'none';

                    // Reset the form and close modal after a delay
                    setTimeout(() => {
                        fileInput.value = ''; // reset input

                        // Check if modal exists before trying to close it
                        const modalElement = document.getElementById('resumeUploadModal');
                        if (modalElement) {
                            const modal = bootstrap.Modal.getInstance(modalElement);
                            if (modal) {
                                modal.hide();
                            }
                        }

                        // Check if showSuccessMessage exists before calling it
                        if (typeof showSuccessMessage === 'function') {
                            showSuccessMessage('Resume uploaded successfully!');
                        } else {
                            console.log('Resume uploaded successfully!');
                        }

                        // Re-enable submit button
                        submitButton.disabled = false;
                        submitButton.innerHTML = originalButtonText;
                    }, 500);
                } catch (error) {
                    console.error('Error in success callback:', error);
                    alert('Error processing upload: ' + error.message);

                    // Re-enable submit button
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalButtonText;
                }
            },
            function (error) {
                console.error('Resume upload error:', error); // Add this for debugging
                alert('Error uploading resume: ' + error);

                // Re-enable submit button
                submitButton.disabled = false;
                submitButton.innerHTML = originalButtonText;
            }
        );
    });

    // Profile Picture Upload AJAX
    document.getElementById('profilepicUploadForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const fileInput = document.getElementById('profilepic');

        if (fileInput.files.length === 0) {
            alert('Please select a profile picture.');
            return;
        }

        const file = fileInput.files[0];
        const allowedTypes = ['image/jpeg', 'image/jpg'];
        const maxSize = 2 * 1024 * 1024; // 2MB

        // Validate file type
        if (!allowedTypes.includes(file.type)) {
            alert('Please select a JPG image file.');
            return;
        }

        // Validate file size
        if (file.size > maxSize) {
            alert('File size exceeds 2MB limit.');
            return;
        }

        const formData = new FormData();
        formData.append('profilepic', file);

        sendFileUploadRequest(
            'upload_profile_picture',
            formData,
            function (response) {
                // Update the profile picture in the main view
                const reader = new FileReader();

                reader.onload = function (e) {
                    document.querySelector('.profile-pic').src = e.target.result;
                }

                reader.readAsDataURL(file);

                // Reset form and close modal
                document.getElementById('profilepicUploadForm').reset();
                const modal = bootstrap.Modal.getInstance(document.getElementById('profilepicUploadModal'));
                modal.hide();
                showSuccessMessage('Profile picture updated successfully!');
            },
            function (error) {
                alert('Error uploading profile picture: ' + error);
            }
        );
    });

    // Delete Item AJAX (Education, Training, Work Experience, References)
    document.getElementById('confirmDeleteReference').addEventListener('click', function () {
        const modal = document.getElementById('deleteConfirmationModal');
        const itemId = modal.getAttribute('data-item-id');
        const itemType = modal.getAttribute('data-item-type');

        if (itemId && itemType) {
            sendAjaxRequest(
                'delete_item',
                'POST',
                { itemId: itemId, itemType: itemType },
                function (response) {
                    let itemEntry;
                    if (itemType === 'reference') {
                        itemEntry = document.querySelector(`.reference-entry[data-id="${itemId}"]`);
                    } else if (itemType === 'education') {
                        itemEntry = document.querySelector(`.education-item[data-id="${itemId}"]`);
                    } else if (itemType === 'training') {
                        itemEntry = document.querySelector(`.training-item[data-id="${itemId}"]`);
                    } else if (itemType === 'experience') {
                        itemEntry = document.querySelector(`.experience-item[data-id="${itemId}"]`);
                    }

                    if (itemEntry) {
                        itemEntry.remove();
                    }

                    // Hide modal
                    const modalInstance = bootstrap.Modal.getInstance(modal);
                    modalInstance.hide();
                    showSuccessMessage('Item deleted successfully!');
                },
                function (error) {
                    alert('Error deleting item: ' + error);
                }
            );
        }
    });



    // Handle edit button clicks for training
    document.addEventListener('click', function (e) {
        if (e.target.closest('.training-section .btn-outline-secondary')) {
            const trainingEntry = e.target.closest('.mb-4');
            const trainingId = trainingEntry.getAttribute('data-id');

            // Set ID in form
            document.getElementById('trainingId').value = trainingId;

            // Get values from entry
            const title = trainingEntry.querySelector('h5').textContent;
            const subtitle = trainingEntry.querySelector('.text-muted').textContent;
            const description = trainingEntry.querySelector('p:last-of-type').textContent;

            // Parse institution and years
            const subtitleParts = subtitle.split(' • ');
            const institution = subtitleParts[0];
            const years = subtitleParts[1];

            // Parse start and end years
            const yearParts = years.split(' - ');
            const startYear = yearParts[0];
            const endYear = yearParts[1];

            // Fill form fields
            document.getElementById('trainingTitle').value = title;
            document.getElementById('institution').value = institution;
            document.getElementById('startYear').value = formatDateForInput(startYear);
            document.getElementById('endYear').value = formatDateForInput(endYear);
            document.getElementById('description').value = description;

            // Update modal title
            document.getElementById('editTrainingModalLabel').textContent = 'Edit Training';
        }
    });




    // Handle edit button clicks for references
    document.addEventListener('click', function (e) {
        if (e.target.closest('.edit-reference-btn')) {
            const referenceEntry = e.target.closest('.mb-4');
            const referenceId = referenceEntry.getAttribute('data-id');

            // Set the reference ID in the hidden input
            document.getElementById('referenceId').value = referenceId;

            const name = referenceEntry.querySelector('h5').textContent;
            const positionCompany = referenceEntry.querySelector('.text-muted').textContent;
            const emailElement = referenceEntry.querySelector('p:has(.fa-envelope)');
            const phoneElement = referenceEntry.querySelector('p:has(.fa-phone)');

            // Parse position and company
            const [position, company] = positionCompany.split(', ');

            // Parse email and phone
            const email = emailElement ? emailElement.textContent.trim() : '';
            const phone = phoneElement ? phoneElement.textContent.trim() : '';

            // Fill form fields
            document.getElementById('referenceName').value = name;
            document.getElementById('referencePosition').value = position;
            document.getElementById('referenceCompany').value = company;
            document.getElementById('referenceEmail').value = email;
            document.getElementById('referencePhone').value = phone;

            // Set modal title to edit
            document.getElementById('referenceModalLabel').textContent = 'Edit Reference';
        }
    });

    // Handle delete button clicks for references
    document.addEventListener('click', function (e) {
        if (e.target.closest('.reference-section .btn-outline-danger')) {
            const referenceEntry = e.target.closest('.mb-4'); // same as your experience code
            const referenceId = referenceEntry.getAttribute('data-id');

            // Show confirmation modal
            const deleteModal = document.getElementById('deleteConfirmationModal');
            deleteModal.setAttribute('data-item-id', referenceId);
            deleteModal.setAttribute('data-item-type', 'reference');

            const modalInstance = new bootstrap.Modal(deleteModal);
            modalInstance.show();
        }
    });

    // Helper function to parse date for input
    function parseDateForInput(dateStr) {
        // If the date is in format like "Jan 2019"
        if (/^[A-Za-z]{3} \d{4}$/.test(dateStr)) {
            const date = new Date(dateStr);
            return date.toISOString().split('T')[0];
        }
        return dateStr; // Return as-is if already in a different format
    }

    // Helper function to format date for input
    function formatDateForInput(yearStr) {
        // If the year is just a number (like "2015"), convert to a date string format
        if (/^\d{4}$/.test(yearStr)) {
            return `${yearStr}-01-01`; // Default to January 1st of that year
        }
        return yearStr; // Return as-is if already in a different format
    }

    // Reset modal when closed
    document.getElementById('editAboutModal').addEventListener('hidden.bs.modal', function () {
        document.getElementById('aboutMeForm').reset();
    });

    document.getElementById('editPersonalInfoModal').addEventListener('hidden.bs.modal', function () {
        document.getElementById('personalInfoForm').reset();
        // Destroy intlTelInput instances
        if (contactNumberInstance) {
            contactNumberInstance.destroy();
            contactNumberInstance = null;
        }
        if (altContactInstance) {
            altContactInstance.destroy();
            altContactInstance = null;
        }
    });

    document.getElementById('editEducationModal').addEventListener('hidden.bs.modal', function () {
        educationForm.reset();
        document.getElementById('educationId').value = '';
        gpaPointsInput.classList.add('d-none');
        document.getElementById('editEducationModalLabel').textContent = 'Add Education';
    });

    document.getElementById('editTrainingModal').addEventListener('hidden.bs.modal', function () {
        trainingForm.reset();
        document.getElementById('trainingId').value = '';
        document.getElementById('editTrainingModalLabel').textContent = 'Add Training';
    });

    document.getElementById('workExperienceModal').addEventListener('hidden.bs.modal', function () {
        workExperienceForm.reset();
        quill.setText('');
        document.getElementById('experienceId').value = '';
        endDateInput.disabled = false;
        document.getElementById('workExperienceModalLabel').textContent = 'Add Work Experience';
    });

    document.getElementById('referenceModal').addEventListener('hidden.bs.modal', function () {
        referenceForm.reset();
        document.getElementById('referenceId').value = '';
        document.getElementById('referenceModalLabel').textContent = 'Add Reference';
    });

    document.getElementById('profilepicUploadModal').addEventListener('hidden.bs.modal', function () {
        document.getElementById('profilepicUploadForm').reset();
    });

    // Handle resume removal
    document.addEventListener('click', function (e) {
        if (e.target.closest('#removeResume')) {
            // Remove the resume preview content
            const resumePreview = document.querySelector('.resume-preview');
            resumePreview.innerHTML = `
                <div class="text-center text-muted py-4">
                    <i class="fas fa-file-upload fa-3x mb-3"></i>
                    <p>No resume uploaded</p>
                </div>
            `;

            // Hide the download and remove buttons
            const buttonContainer = document.querySelector('.resume-actions');
            const resumepreviewbt = document.querySelector('.resume_upload-btn');
            // const resumeuploadsection =document.querySelector('resume-upload-section');

            if (buttonContainer) {
                buttonContainer.style.setProperty('display', 'none', 'important');
            }
            resumepreviewbt.style.display = 'block';

            // resumeuploadsection.classList.remove('d-none');
            // Also delete from server
            sendAjaxRequest(
                'delete_resume',
                'POST',
                {},
                function (response) {
                    showSuccessMessage('Resume removed successfully!');
                },
                function (error) {
                    alert('Error removing resume: ' + error);
                }
            );
        }
    });
});






// =================== Work Experience AJAX ==============//
const workExperienceForm = document.getElementById('workExperienceForm');
const experienceHiddenIdInput = document.createElement('input');
experienceHiddenIdInput.type = 'hidden';
experienceHiddenIdInput.id = 'experienceId';
experienceHiddenIdInput.name = 'experienceId';
workExperienceForm.appendChild(experienceHiddenIdInput);

// Initialize Quill editor
const quill = new Quill('#quillEditor', {
    theme: 'snow',
    placeholder: 'Describe your responsibilities...',
});

// Handle currently working checkbox
const currentlyWorking = document.getElementById('currentlyWorking');
const endDateInput = document.getElementById('endDate');

currentlyWorking.addEventListener('change', function () {
    if (this.checked) {
        endDateInput.disabled = true;
        endDateInput.value = '';
    } else {
        endDateInput.disabled = false;
    }
});

// Handle save button click
document.getElementById('saveWorkExperience').addEventListener('click', function () {
    // Update the hidden responsibilities field with the Quill content
    document.getElementById('responsibilities').value = quill.root.innerHTML;

    // Get form values
    const experienceId = document.getElementById('experienceId').value;
    const jobTitle = document.getElementById('jobTitle').value.trim();
    const companyName = document.getElementById('companyName').value.trim();
    const startDate = document.getElementById('startDate').value;
    const endDate = currentlyWorking.checked ? 'Present' : document.getElementById('endDate').value;
    const responsibilities = quill.root.innerHTML;

    // Validate required fields
    if (!jobTitle || !companyName || !startDate || (!endDate && !currentlyWorking.checked)) {
        alert('Please fill in all required fields.');
        return;
    }

    // Validate date range
    if (!currentlyWorking.checked && new Date(startDate) > new Date(endDate)) {
        alert('Start date cannot be after end date.');
        return;
    }

    // Disable submit button to prevent double submission
    const submitButton = document.getElementById('saveWorkExperience');
    submitButton.disabled = true;
    submitButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';

    const formData = {
        experienceId: experienceId,
        jobTitle: jobTitle,
        companyName: companyName,
        startDate: startDate,
        endDate: endDate,
        responsibilities: responsibilities
    };

    sendAjaxRequest(
        'update_work_experience',
        'POST',
        formData,
        function (response) {
            // If we're adding a new entry, use the ID returned from the server
            const newId = response.id || 'exp_' + Date.now();

            if (experienceId) {
                // Update existing entry
                updateExperienceEntry(experienceId, jobTitle, companyName, startDate, endDate, responsibilities);
            } else {
                // Add new entry with the ID from the server
                addExperienceEntry(newId, jobTitle, companyName, startDate, endDate, responsibilities);
            }

            // Reset form and close modal
            workExperienceForm.reset();
            quill.setText('');
            document.getElementById('experienceId').value = '';
            endDateInput.disabled = false;
            const modal = bootstrap.Modal.getInstance(document.getElementById('workExperienceModal'));
            modal.hide();
            showSuccessMessage('Work experience updated successfully!');

            // Re-enable submit button
            submitButton.disabled = false;
            submitButton.innerHTML = 'Save';
        },
        function (error) {
            alert('Error updating work experience: ' + error);
            // Re-enable submit button
            submitButton.disabled = false;
            submitButton.innerHTML = 'Save';
        }
    );
});

// Function to add new experience entry
function addExperienceEntry(id, jobTitle, companyName, startDate, endDate, responsibilities) {
    const experienceSection = document.querySelector('.experiance-section');

    // Check if entry already exists to prevent duplicates
    if (document.querySelector(`.experience-item[data-id="${id}"]`)) {
        console.warn('Experience entry with ID ' + id + ' already exists');
        return;
    }

    const experienceEntry = document.createElement('div');
    experienceEntry.className = 'mb-4 border-bottom pb-3 d-flex justify-content-between align-items-start experience-item';
    experienceEntry.setAttribute('data-id', id);

    // Format dates for display
    const startDateDisplay = formatDateForDisplay(startDate);
    const endDateDisplay = endDate === 'Present' ? 'Present' : formatDateForDisplay(endDate);

    // Calculate duration
    const duration = calculateDuration(startDate, endDate === 'Present' ? new Date() : endDate);

    experienceEntry.innerHTML = `
            <div>
                <h5>${jobTitle}</h5>
                <p class="text-muted mb-1">${companyName} • ${startDateDisplay} - ${endDateDisplay} • ${duration}</p>
                <p>${responsibilities}</p>
            </div>
            <div class="ms-3 text-nowrap">
                <button class="btn btn-sm btn-outline-secondary me-1" data-bs-toggle="modal" data-bs-target="#workExperienceModal">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn btn-sm btn-outline-danger">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </div>
        `;

    // Add to section
    experienceSection.appendChild(experienceEntry);
}


function updateExperienceEntry(experienceId, jobTitle, companyName, startDate, endDate, responsibilities) {
    const experienceEntry = document.querySelector(`.experience-item[data-id="${experienceId}"]`);
    if (!experienceEntry) return;

    // Format dates and duration
    const startDateDisplay = formatDateForDisplay(startDate);
    const endDateDisplay = endDate === 'Present' ? 'Present' : formatDateForDisplay(endDate);
    const duration = calculateDuration(startDate, endDate === 'Present' ? new Date() : endDate);

    // Update job title and metadata
    experienceEntry.querySelector('h5').textContent = jobTitle;
    experienceEntry.querySelector('.text-muted').textContent =
        `${companyName} • ${startDateDisplay} - ${endDateDisplay} • ${duration}`;

    // --- Fix: Replace ALL responsibility content after the marker ---
    const responsibilityContainer = experienceEntry.querySelector('.responsibiltiy');
    if (responsibilityContainer) {
        // Remove all siblings after the marker
        let nextSibling;
        while ((nextSibling = responsibilityContainer.nextElementSibling)) {
            nextSibling.remove();
        }
        // Insert new responsibilities
        responsibilityContainer.insertAdjacentHTML('afterend', responsibilities);
    }
}


function formatDateForDisplay(dateString) {
    const date = new Date(dateString);

    const day = String(date.getDate()).padStart(2, '0'); // 01, 02, ...
    const month = date.toLocaleString('en-US', { month: 'short' }); // Jan, Feb, Mar...
    const year = date.getFullYear();

    return `${day} ${month}, ${year}`;
}


// Helper function to calculate duration
function calculateDuration(startDate, endDate) {
    const start = new Date(startDate);
    const end = endDate === 'Present' ? new Date() : new Date(endDate);

    // Calculate the difference in months
    let months = (end.getFullYear() - start.getFullYear()) * 12;
    months += end.getMonth() - start.getMonth();

    // If end day is less than start day, subtract a month
    if (end.getDate() < start.getDate()) {
        months--;
    }

    const years = Math.floor(months / 12);
    const remainingMonths = months % 12;

    let duration = '';
    if (years > 0) {
        duration += `${years} yr${years > 1 ? 's' : ''}`;
    }
    if (remainingMonths > 0) {
        if (duration) duration += ' ';
        duration += `${remainingMonths} mo${remainingMonths > 1 ? 's' : ''}`;
    }

    return duration || '0 month';
}


document.addEventListener('click', function (e) {
    const editBtn = e.target.closest('.experiance-section .btn-outline-secondary');
    if (!editBtn) return;

    // If your button has data-bs-toggle, prevent Bootstrap’s auto open (we'll open manually)
    e.preventDefault();
    e.stopPropagation();

    const experienceEntry = editBtn.closest('.experience-item'); // safer than .mb-4
    if (!experienceEntry) return;

    const experienceId = experienceEntry.dataset.id || '';
    document.getElementById('experienceId')?.setAttribute('value', experienceId);
    console.log('Editing experience with ID:', experienceId);

    // Get form elements
    const jobTitleInput = document.getElementById('jobTitle');
    const companyNameInput = document.getElementById('companyName');
    const startDateInput = document.getElementById('startDate');
    const endDateInput = document.getElementById('endDate');
    const currentlyWorking = document.getElementById('currentlyWorking');

    // Values from entry
    const title = experienceEntry.querySelector('h5')?.textContent.trim() || '';
    const company = experienceEntry.querySelector('.company')?.textContent.trim() || '';
    const startDateStr = experienceEntry.querySelector('.start-date')?.textContent.trim() || '';
    const endDateStr = (experienceEntry.querySelector('.end-date')?.textContent || '').trim();

    // Fill fields
    jobTitleInput.value = title;
    companyNameInput.value = company;

    startDateInput.value = formatDateForInput(startDateStr);

    const isPresent = /^present$/i.test(endDateStr);
    currentlyWorking.checked = isPresent;
    endDateInput.disabled = isPresent;
    endDateInput.value = isPresent ? '' : formatDateForInput(endDateStr);

    // Responsibilities (uses your marker <p class="responsibiltiy"></p>)
    const respHTML = getResponsibilitiesHTML(experienceEntry);

    if (typeof quill !== 'undefined' && quill) {
        // Better than setting quill.root.innerHTML
        quill.clipboard.dangerouslyPasteHTML(respHTML || '');
    } else {
        const hidden = document.getElementById('responsibilities');
        if (hidden) hidden.value = respHTML || '';
    }

    // Modal title and show
    document.getElementById('workExperienceModalLabel').textContent = 'Edit Work Experience';
    bootstrap.Modal.getOrCreateInstance(document.getElementById('workExperienceModal')).show();
});

// Collect everything after the <p class="responsibiltiy"></p> marker inside the content column
function getResponsibilitiesHTML(entry) {
    const marker = entry.querySelector('.responsibiltiy'); // matches your DOM typo
    if (!marker) return '';
    const parts = [];
    let el = marker.nextElementSibling;
    while (el) {
        parts.push(el.outerHTML);
        el = el.nextElementSibling;
    }
    return parts.join('').trim();
}

// Parse "30 Mar, 2025" -> "2025-03-30"
function formatDateForInput(label) {
    if (!label) return '';
    const clean = label.replace(',', '').trim(); // "30 Mar 2025"
    const m = clean.match(/^(\d{1,2})\s+([A-Za-z]+)\s+(\d{4})$/);
    if (!m) return '';
    const [, ddStr, monStr, yyyy] = m;

    const months = {
        jan: '01', january: '01',
        feb: '02', february: '02',
        mar: '03', march: '03',
        apr: '04', april: '04',
        may: '05',
        jun: '06', june: '06',
        jul: '07', july: '07',
        aug: '08', august: '08',
        sep: '09', sept: '09', september: '09',
        oct: '10', october: '10',
        nov: '11', november: '11',
        dec: '12', december: '12'
    };

    const mm = months[monStr.toLowerCase()] || '01';
    const dd = String(parseInt(ddStr, 10)).padStart(2, '0');
    return `${yyyy}-${mm}-${dd}`;
}




// Handle delete button clicks for work experience
document.addEventListener('click', function (e) {
    if (e.target.closest('.experiance-section .btn-outline-danger')) {
        const experienceEntry = e.target.closest('.mb-4');
        const experienceId = experienceEntry.getAttribute('data-id');

        // Show confirmation modal
        const deleteModal = document.getElementById('deleteConfirmationModal');
        deleteModal.setAttribute('data-item-id', experienceId);
        deleteModal.setAttribute('data-item-type', 'experience');

        const modalInstance = new bootstrap.Modal(deleteModal);
        modalInstance.show();
    }
});

// Helper function to parse date for input
function parseDateForInput(dateStr) {
    // If the date is in format like "Jan 2019"
    if (/^[A-Za-z]{3} \d{4}$/.test(dateStr)) {
        const date = new Date(dateStr);
        return date.toISOString().split('T')[0];
    }
    return dateStr; // Return as-is if already in a different format
}









