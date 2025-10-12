function sendAjaxRequest(action, method, data, successCallback, errorCallback) {
    const ajaxUrl = window.ajax_common_vars?.ajaxurl || ajaxurl;
    const nonce = window.ajax_common_vars?.profile_nonce || profile_nonce;

    const xhr = new XMLHttpRequest();
    xhr.open(method, ajaxUrl, true);
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
    data.nonce = nonce;

    // Convert data object to URL-encoded string
    const formData = new URLSearchParams();
    for (const key in data) {
        if (data.hasOwnProperty(key)) {
            formData.append(key, data[key]);
        }
    }

    xhr.send(formData.toString());
}


// function sendAjaxRequest(action, method, data, successCallback, errorCallback) {
//     const ajaxUrl = ajax_common_vars?.ajaxurl || '<?php echo admin_url("admin-ajax.php"); ?>';
//     const xhr = new XMLHttpRequest();
//     xhr.open(method, ajaxUrl, true);
//     xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
//     xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    
//     xhr.onreadystatechange = function () {
//         if (xhr.readyState === 4) {
//             if (xhr.status === 200) {
//                 try {
//                     const response = JSON.parse(xhr.responseText);
//                     console.log('AJAX response:', response); // Debug log
//                     if (response.success) {
//                         if (successCallback) successCallback(response);
//                     } else {
//                         if (errorCallback) errorCallback(response.data || response.message);
//                         else alert(response.data || response.message);
//                     }
//                 } catch (e) {
//                     console.error('AJAX response parsing error:', e); // Debug log
//                     if (errorCallback) errorCallback('Invalid server response');
//                     else alert('Invalid server response');
//                 }
//             } else {
//                 console.error('AJAX request failed with status:', xhr.status); // Debug log
//                 if (errorCallback) errorCallback('Request failed with status: ' + xhr.status);
//                 else alert('Request failed with status: ' + xhr.status);
//             }
//         }
//     };
    
//     // Add action and nonce
//     data.action = action;
//     data.nonce = ajax_common_vars.profile_nonce;
    
//     // Convert to URL-encoded string
//     const formData = new URLSearchParams();
//     for (const key in data) {
//         if (data.hasOwnProperty(key)) {
//             formData.append(key, data[key]);
//         }
//     }
    
//     console.log('Sending AJAX request:', {
//         action: action,
//         data: data
//     });
    
//     xhr.send(formData.toString());
// }

// Updated sendFileUploadRequest function
function sendFileUploadRequest(action, formData, successCallback, errorCallback) {
    const ajaxUrl = ajax_common_vars?.ajaxurl || '<?php echo admin_url("admin-ajax.php"); ?>';
    const xhr = new XMLHttpRequest();
    xhr.open('POST', ajaxUrl, true);
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                try {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        if (successCallback) successCallback(response);
                    } else {
                        if (errorCallback) errorCallback(response.data || response.message);
                        else alert(response.data || response.message);
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

    formData.append('action', action);
    // Nonce is already included in FormData from the form handler
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

    // Get the form element first
    const aboutMeForm = document.getElementById('aboutMeForm');

    // Only attach event listener if form exists
    if (aboutMeForm) {
        aboutMeForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const aboutMeTextarea = document.getElementById('aboutMeTextarea');

            if (!aboutMeTextarea) {
                console.error('About Me textarea not found');
                return;
            }

            const aboutText = aboutMeTextarea.value;
            console.log('Submitting About Me:', aboutText);
            const submitButton = aboutMeForm.querySelector('button[type="submit"]');
            submitButton.disabled = true;
            submitButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';

            sendAjaxRequest(
                'update_about_me',
                'POST',
                { about: aboutText },
                function (response) {
                    const profileSection = document.querySelector('.profile-section p');
                    if (profileSection) {
                        profileSection.innerText = aboutText;
                    }

                    const editAboutModal = document.getElementById('editAboutModal');
                    if (editAboutModal) {
                        const modal = bootstrap.Modal.getInstance(editAboutModal);
                        if (modal) modal.hide();
                    }

                    showSuccessMessage('About Me updated successfully!');
                },
                function (error) {
                    alert('Error updating About Me: ' + error);
                }
            );
        });
    } else {
        // console.error('About Me form not found');
    }



    // Initialize modal functionality
    const editPersonalInfoModal = document.getElementById('editPersonalInfoModal');
    if (editPersonalInfoModal) {
        let contactNumberInstance, altContactInstance;
        
        // Initialize intlTelInput when modal is shown
        editPersonalInfoModal.addEventListener('shown.bs.modal', function () {
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
                
                // Check if bangladeshData is available
                if (typeof bangladeshData === 'undefined') {
                    console.error('bangladeshData is not loaded. Make sure address.js is included.');
                    return;
                }
                
                // Populate divisions for both present and permanent address
                populateDivisions('division');
                populateDivisions('permanent_division');
                
                // Set selected values if they exist
                if (addressData.present_division) {
                    document.getElementById('division').value = addressData.present_division;
                    populateDistricts('division', 'district');
                    
                    if (addressData.present_district) {
                        // Wait for districts to populate, then set value
                        setTimeout(function() {
                            document.getElementById('district').value = addressData.present_district;
                            populateUpazilas('district', 'upazila');
                            
                            if (addressData.present_upazila) {
                                // Wait for upazilas to populate, then set value
                                setTimeout(function() {
                                    document.getElementById('upazila').value = addressData.present_upazila;
                                }, 100);
                            }
                        }, 100);
                    }
                }
                
                if (addressData.permanent_division) {
                    document.getElementById('permanent_division').value = addressData.permanent_division;
                    populateDistricts('permanent_division', 'permanent_district');
                    
                    if (addressData.permanent_district) {
                        setTimeout(function() {
                            document.getElementById('permanent_district').value = addressData.permanent_district;
                            populateUpazilas('permanent_district', 'permanent_upazila');
                            
                            if (addressData.permanent_upazila) {
                                setTimeout(function() {
                                    document.getElementById('permanent_upazila').value = addressData.permanent_upazila;
                                }, 100);
                            }
                        }, 100);
                    }
                }
                
                // Check if permanent address is the same as present address
                const isSameAddress = (
                    addressData.present_division === addressData.permanent_division &&
                    addressData.present_district === addressData.permanent_district &&
                    addressData.present_upazila === addressData.permanent_upazila
                );
                
                // Set the checkbox state
                document.getElementById('sameAsPresent').checked = isSameAddress;
                
                // Show/hide permanent address fields based on checkbox state
                const permanentAddressFields = document.getElementById('permanentAddressFields');
                if (isSameAddress) {
                    permanentAddressFields.style.display = 'none';
                } else {
                    permanentAddressFields.style.display = 'flex';
                }
            }, 100);
        });
        
        // Handle "Same as Present Address" checkbox
        const sameAsPresentCheckbox = document.getElementById('sameAsPresent');
        if (sameAsPresentCheckbox) {
            sameAsPresentCheckbox.addEventListener('change', function() {
                const permanentAddressFields = document.getElementById('permanentAddressFields');
                if (this.checked) {
                    // Hide permanent address fields
                    permanentAddressFields.style.display = 'none';
                    
                    // Copy present address to permanent address
                    document.querySelector('textarea[name="permanentaddressline"]').value = document.querySelector('textarea[name="presentaddressline"]').value;
                    document.getElementById('permanent_division').value = document.getElementById('division').value;
                    document.getElementById('permanent_district').value = document.getElementById('district').value;
                    document.getElementById('permanent_upazila').value = document.getElementById('upazila').value;
                    document.getElementById('permanent_postcode').value = document.getElementById('presentpostcode').value;
                } else {
                    // Show permanent address fields
                    permanentAddressFields.style.display = 'flex';
                }
            });
        }
        
        // Add event listeners for division dropdowns
        const divisionSelect = document.getElementById('division');
        if (divisionSelect) {
            divisionSelect.addEventListener('change', function() {
                populateDistricts('division', 'district');
            });
        }
        
        const permanentDivisionSelect = document.getElementById('permanent_division');
        if (permanentDivisionSelect) {
            permanentDivisionSelect.addEventListener('change', function() {
                populateDistricts('permanent_division', 'permanent_district');
            });
        }
        
        // Add event listeners for district dropdowns
        const districtSelect = document.getElementById('district');
        if (districtSelect) {
            districtSelect.addEventListener('change', function() {
                populateUpazilas('district', 'upazila');
            });
        }
        
        const permanentDistrictSelect = document.getElementById('permanent_district');
        if (permanentDistrictSelect) {
            permanentDistrictSelect.addEventListener('change', function() {
                populateUpazilas('permanent_district', 'permanent_upazila');
            });
        }
        
        // Handle form submission
        const personalInfoForm = document.getElementById('personalInfoForm');
        if (personalInfoForm) {
            document.getElementById('personalInfoForm').addEventListener('submit', function (e) {
                e.preventDefault();
                
                // Validate required fields
                const requiredFields = ['fullName', 'fatherName', 'motherName', 'dob', 'gender', 'division', 'district', 'upazila'];
                for (const field of requiredFields) {
                    const element = this.elements[field];
                    if (!element.value.trim()) {
                        alert(`Please fill in the ${element.previousElementSibling.textContent} field`);
                        element.focus();
                        return;
                    }
                }
                
                // Check if "Same as Present Address" is checked
                const sameAsPresent = document.getElementById('sameAsPresent').checked;
                
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
                    presentaddressline: this.presentaddressline.value,
                    permanentaddressline: sameAsPresent ? this.presentaddressline.value : this.permanentaddressline.value,
                    placeofbirth: this.placeofbirth.value,
                    // Address fields
                    division: this.division.value,
                    district: this.district.value,
                    upazila: this.upazila.value,
                    presentpostcode: this.presentpostcode.value,
                    permanent_division: sameAsPresent ? this.division.value : this.permanent_division.value,
                    permanent_district: sameAsPresent ? this.district.value : this.permanent_district.value,
                    permanent_upazila: sameAsPresent ? this.upazila.value : this.permanent_upazila.value,
                    permanent_postcode: sameAsPresent ? this.presentpostcode.value : this.permanent_postcode.value,
                    sameAsPresent: sameAsPresent,
                    nonce: addressData.nonce
                };
                
                // Disable submit button to prevent double submission
                const submitButton = this.querySelector('button[type="submit"]');
                submitButton.disabled = true;
                submitButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';
                
                // Send AJAX request
                jQuery.ajax({
                    url: addressData.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'update_personal_info',
                        ...formData
                    },
                    success: function (response) {
                        if (response.success) {
                            // Update the display with the new data
                            updatePersonalInfoDisplay(formData);
                            
                            // Re-enable the submit button and reset its text
                            submitButton.disabled = false;
                            submitButton.innerHTML = 'Save Changes';
                            
                            // Close the modal
                            const modal = bootstrap.Modal.getInstance(document.getElementById('editPersonalInfoModal'));
                            modal.hide();
                            
                            // Show success message
                            //alert('Personal information updated successfully!');
                            showSuccessMessage('Personal information updated successfully!');
                        } else {
                            alert('Error updating personal information: ' + response.data);
                            submitButton.disabled = false;
                            submitButton.innerHTML = 'Save Changes';
                        }
                    },
                    error: function (error) {
                        alert('Error updating personal information: ' + error.statusText);
                        submitButton.disabled = false;
                        submitButton.innerHTML = 'Save Changes';
                    }
                });
            });
        }
    }
    
    // Function to populate divisions dropdown
    function populateDivisions(selectId) {
        const divisionSelect = document.getElementById(selectId);
        if (!divisionSelect) return;
        
        divisionSelect.innerHTML = '<option value="">Select Division</option>';
        
        for (const division in bangladeshData.divisions) {
            const option = document.createElement('option');
            option.value = division;
            option.textContent = division;
            divisionSelect.appendChild(option);
        }
    }
    
    // Function to populate districts dropdown based on selected division
    function populateDistricts(divisionSelectId, districtSelectId) {
        const divisionSelect = document.getElementById(divisionSelectId);
        const districtSelect = document.getElementById(districtSelectId);
        if (!divisionSelect || !districtSelect) return;
        
        const selectedDivision = divisionSelect.value;
        
        districtSelect.innerHTML = '<option value="">Select District</option>';
        
        if (selectedDivision && bangladeshData.divisions[selectedDivision]) {
            bangladeshData.divisions[selectedDivision].forEach(district => {
                const option = document.createElement('option');
                option.value = district;
                option.textContent = district;
                districtSelect.appendChild(option);
            });
        }
    }
    
    // Function to populate upazilas dropdown based on selected district
    function populateUpazilas(districtSelectId, upazilaSelectId) {
        const districtSelect = document.getElementById(districtSelectId);
        const upazilaSelect = document.getElementById(upazilaSelectId);
        if (!districtSelect || !upazilaSelect) return;
        
        const selectedDistrict = districtSelect.value;
        
        upazilaSelect.innerHTML = '<option value="">Select Upazila/City</option>';
        
        if (selectedDistrict && bangladeshData.districts[selectedDistrict]) {
            bangladeshData.districts[selectedDistrict].forEach(upazila => {
                const option = document.createElement('option');
                option.value = upazila;
                option.textContent = upazila;
                upazilaSelect.appendChild(option);
            });
        }
    }
    
// Function to update the personal information display
function updatePersonalInfoDisplay(data) {
    console.log(data);
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
    
    // Create full address strings
    const presentFullAddress = createFullAddressString(
        data.presentaddressline,
        data.upazila || '',
        data.district || '',
        data.division || '',
        data.presentpostcode || ''
    );
    
    const permanentFullAddress = data.sameAsPresent ? presentFullAddress : createFullAddressString(
        data.permanentaddressline,
        data.permanent_upazila || '',
        data.permanent_district || '',
        data.permanent_division || '',
        data.permanent_postcode || ''
    );
    
    // Update address fields with full address strings
    updateField('presentaddressline', presentFullAddress);
    updateField('permanentaddressline', permanentFullAddress);
    updateField('placeofbirth', data.placeofbirth);
    
    // Don't update individual address components (division, district, upazila) 
    // because they are not displayed in the frontend
}
    
// Helper function to create full address string
function createFullAddressString(address, upazila, district, division, postcode) {
    let fullAddress = '';
    
    // Only add components if they exist and are not empty
    if (address && address.trim() !== '') {
        fullAddress += address.trim();
    }
    if (upazila && upazila.trim() !== '') {
        fullAddress += (fullAddress ? ', ' : '') + upazila.trim();
    }
    if (district && district.trim() !== '') {
        fullAddress += (fullAddress ? ', ' : '') + district.trim();
    }
    if (division && division.trim() !== '') {
        fullAddress += (fullAddress ? ', ' : '') + division.trim();
    }
    if (postcode && postcode.trim() !== '') {
        fullAddress += (fullAddress ? ' - ' : '') + postcode.trim();
    }
    
    return fullAddress;
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
        'presentaddressline': { column: 1, index: 3 },
        'permanentaddressline': { column: 1, index: 4 },
        'placeofbirth': { column: 1, index: 5 }
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
// function formatDate(dateString) {
//     if (!dateString) return '';
//     const date = new Date(dateString);
//     return date.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
// }
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

                    // Ensure response.data exists
                    if (!response.data) {
                        console.error('Invalid response structure:', response);
                        alert('Error updating training: Invalid response structure');
                        return;
                    }

                    const newId = response.data.id || 'training_' + Date.now();
                    const message = response.data.message || 'Training updated successfully!';

                    if (trainingId) {
                        console.log('Updating existing training entry with ID:', trainingId);
                        updateTrainingEntry(trainingId, title, institution, startYear, endYear, description);
                    } else {
                        console.log('Adding new training entry with ID:', newId);
                        addTrainingEntry(newId, title, institution, startYear, endYear, description);
                    }

                    trainingForm.reset();
                    document.getElementById('trainingId').value = '';
                    const modal = bootstrap.Modal.getInstance(document.getElementById('editTrainingModal'));
                    modal.hide();
                    showSuccessMessage(message);

                    submitButton.disabled = false;
                    submitButton.innerHTML = 'Save Training';
                    isTrainingSubmitting = false;
                },
                function (error) {
                    console.error('Training AJAX error:', error);
                    alert('Error updating training: ' + error);
                    submitButton.disabled = false;
                    submitButton.innerHTML = 'Save Training';
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

    //Function to update existing training entry
    function updateTrainingEntry(trainingId, title, institution, startYear, endYear, description) {
        const trainingEntry = document.querySelector(`.training-item[data-id="${trainingId}"]`);

        if (!trainingEntry) {
            console.error('Training entry not found for ID:', trainingId);
            return;
        }

        // Format years for display
        const startYearDisplay = formatDateForDisplay(startYear);
        const endYearDisplay = formatDateForDisplay(endYear);
        const duration = calculateDuration(startYear, endYear);
        //  const startYearDisplay = new Date(startYear).getFullYear();
        //  const endYearDisplay = new Date(endYear).getFullYear();

        // Update entry
        trainingEntry.querySelector('h5').textContent = title;
        trainingEntry.querySelector('.text-muted').textContent =
            `${institution} • ${startYearDisplay} - ${endYearDisplay} • ${duration}`;
        //trainingEntry.querySelector('.text-muted').textContent = `${institution} • ${startYearDisplay} - ${endYearDisplay}`;
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
    // Get the reference form element
    const referenceForm = document.getElementById('referenceForm');

    // Only proceed if the form exists
    if (referenceForm) {
        const referenceHiddenIdInput = document.createElement('input');
        referenceHiddenIdInput.type = 'hidden';
        referenceHiddenIdInput.id = 'referenceId';
        referenceHiddenIdInput.name = 'referenceId';
        referenceForm.appendChild(referenceHiddenIdInput);
    }

    const editreferenceModal = document.getElementById('referenceModal');
    if (editreferenceModal){
        let referenceNumberInstance;
        editreferenceModal.addEventListener('shown.bs.modal', function () {
            setTimeout(function () {
                const referenceNumberInput = document.getElementById('referencePhone');
                if (referenceNumberInput && !referenceNumberInstance) {
                    referenceNumberInstance = window.intlTelInput(referenceNumberInput, {
                        initialCountry: "bd",
                        utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
                        separateDialCode: true,
                        formatOnDisplay: true,
                        nationalMode: true
                    });
                }
            }, 100);
        });

            // Get the save reference button
    const saveReferenceBtn = document.getElementById('saveReference');

    // Only attach event listener if button exists
    if (saveReferenceBtn) {
        saveReferenceBtn.addEventListener('click', function () {
            // Get all form elements
            const referenceId = document.getElementById('referenceId');
            const referenceName = document.getElementById('referenceName');
            const referencePosition = document.getElementById('referencePosition');
            const referenceCompany = document.getElementById('referenceCompany');
            const referenceEmail = document.getElementById('referenceEmail');
            const referencePhone = document.getElementById('referencePhone');
            const referenceForm = document.getElementById('referenceForm');

            // Check if required elements exist
            if (!referenceName || !referencePosition || !referenceCompany || !referenceEmail) {
                console.error('Required form elements not found');
                return;
            }

            // Get values
            const id = referenceId ? referenceId.value : '';
            const name = referenceName.value.trim();
            const position = referencePosition.value.trim();
            const company = referenceCompany.value.trim();
            const email = referenceEmail.value.trim();
            const phone = referencePhone ? referencePhone.value.trim() : '';

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
                referenceId: id,
                name: name,
                position: position,
                company: company,
                email: email,
                phone: referenceNumberInstance ? referenceNumberInstance.getNumber() : ''
            };
            saveReferenceBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';
            sendAjaxRequest(
                'update_references',
                'POST',
                formData,
                function (response) {
                    if (id) {
                        // Editing existing reference
                        const referenceEntry = document.querySelector(`.reference-entry[data-id="${id}"]`);
                        if (referenceEntry) {
                            updateReferenceEntry(referenceEntry, name, position, company, email, phone);
                            //console.log('formData: ' + formData);
                        }
                    } else {
                        // Adding new reference
                        addReferenceEntry(name, position, company, email, phone);
                    }

                    // Reset form and close modal
                    if (referenceForm) {
                        referenceForm.reset();
                        if (referenceId) referenceId.value = '';
                    }

                    const referenceModal = document.getElementById('referenceModal');
                    if (referenceModal) {
                        const modal = bootstrap.Modal.getInstance(referenceModal);
                        if (modal) modal.hide();
                    }

                    showSuccessMessage('Reference updated successfully!');
                },
                function (error) {
                    alert('Error updating reference: ' + error);
                }
            );
        });
    }
    }




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
        e.stopPropagation();
        console.log('Resume form submitted');

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

        // Add nonce from ajax_common_vars
        if (typeof ajax_common_vars !== 'undefined' && ajax_common_vars.profile_nonce) {
            formData.append('nonce', ajax_common_vars.profile_nonce);
        } else {
            console.error('Nonce not available');
            alert('Security token missing. Please refresh the page.');
            return;
        }

        // Disable submit button
        const submitButton = this.querySelector('button[type="submit"]');
        const originalButtonText = submitButton.innerHTML;
        submitButton.disabled = true;
        submitButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Uploading...';

        // Updated function call - removed nonce parameter
        sendFileUploadRequest(
            'upload_resume',
            formData,
            function (response) {
                console.log('Resume upload successful:', response);
                try {
                    // Hide placeholder
                    const placeholder = document.querySelector('.no-resume-placeholder');
                    if (placeholder) placeholder.style.display = 'none';

                    // Get resume section
                    const resumeSection = document.querySelector('.resume-section');
                    const resumepreviewbt = document.querySelector('.resume_upload-btn');
                    if (!resumeSection) throw new Error('Resume section not found');

                    // Get or create resume preview
                    let resumePreview = document.querySelector('.resume-preview');
                    if (!resumePreview) {
                        resumePreview = document.createElement('div');
                        resumePreview.className = 'resume-preview mb-3';
                        resumeSection.appendChild(resumePreview);
                    }

                    // Get or create resume actions
                    let resumeActions = document.querySelector('.resume-actions');
                    if (!resumeActions) {
                        resumeActions = document.createElement('div');
                        resumeActions.className = 'd-grid gap-2 resume-actions';
                        resumeSection.appendChild(resumeActions);
                    }

                    // Update preview content
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

                    // Update actions
                    resumeActions.innerHTML = `
                    <a href="${response.data.url}" class="btn btn-outline-primary" download>
                        <i class="fas fa-download me-1"></i> Download
                    </a>
                    <button class="btn btn-outline-danger" id="removeResume">
                        <i class="fas fa-trash me-1"></i> Remove
                    </button>
                `;

                    // Show elements
                    resumePreview.style.display = 'block';
                    resumeActions.style.display = 'block';
                    resumepreviewbt.style.display = 'none';

                    // Reset and close modal
                    setTimeout(() => {
                        fileInput.value = '';
                        const modalElement = document.getElementById('resumeUploadModal');
                        if (modalElement) {
                            const modal = bootstrap.Modal.getInstance(modalElement);
                            if (modal) modal.hide();
                        }
                        // if (typeof showSuccessMessage === 'function') {
                        //     showSuccessMessage('Resume uploaded successfully!');
                        // }
                        submitButton.disabled = false;
                        submitButton.innerHTML = originalButtonText;
                    }, 500);
                } catch (error) {
                    console.error('Error in success callback:', error);
                    alert('Error processing upload: ' + error.message);
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalButtonText;
                }
            },
            function (error) {
                console.error('Resume upload error:', error);
                alert('Error uploading resume: ' + error);
                submitButton.disabled = false;
                submitButton.innerHTML = originalButtonText;
            }
        );
    });



    // ============ test ============

    // Profile Picture Upload AJAX with Event Delegation
    document.addEventListener('submit', function (e) {
        if (e.target && e.target.id === 'profilepicUploadForm') {
            e.preventDefault();
            console.log('Profile picture ajax loaded from event delegation');

            const fileInput = document.getElementById('profilepic');
            const form = e.target;

            if (fileInput.files.length === 0) {
                alert('Please select a profile picture.');
                return;
            }

            const file = fileInput.files[0];
            const allowedTypes = ['image/jpeg', 'image/jpg'];
            const maxSize = 2 * 1024 * 1024; // 2MB

            if (!allowedTypes.includes(file.type)) {
                alert('Please select a JPG image file.');
                return;
            }

            if (file.size > maxSize) {
                alert('File size exceeds 2MB limit.');
                return;
            }

            // Update preview immediately
            const reader = new FileReader();
            reader.onload = function (e) {
                const previewImg = document.getElementById('profilepicPreview');
                if (previewImg) {
                    previewImg.src = e.target.result;
                }
                document.querySelectorAll('.profile-pic').forEach(img => {
                    if (img.id !== 'profilepicPreview') {
                        img.src = e.target.result;
                    }
                });
            };
            reader.readAsDataURL(file);

            const formData = new FormData();
            formData.append('profilepic', file);

            // Add nonce to FormData
            if (typeof ajax_common_vars !== 'undefined' && ajax_common_vars.profile_nonce) {
                formData.append('nonce', ajax_common_vars.profile_nonce);
            } else {
                console.error('Nonce not available');
                alert('Security token missing. Please refresh the page.');
                return;
            }

            // Disable submit button to prevent double submission
            const submitButton = form.querySelector('button[type="submit"]');
            const originalButtonText = submitButton.innerHTML;
            submitButton.disabled = true;
            submitButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Uploading...';

            // Send AJAX request
            sendFileUploadRequest(
                'upload_profile_picture_handler',
                formData,
                function (response) {
                    console.log('Profile picture upload successful:', response);
                    form.reset();

                    // Update all profile pictures with new image
                    if (response.data && response.data.url) {
                        document.querySelectorAll('.profile-pic').forEach(img => {
                            img.src = response.data.url;
                        });
                    }

                    // Close modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('profilepicUploadModal'));
                    if (modal) {
                        modal.hide();
                    }

                    // Show success message
                    if (typeof showSuccessMessage === 'function') {
                        showSuccessMessage('Profile picture updated successfully!');
                    } else {
                        alert('Profile picture updated successfully!');
                    }

                    // Re-enable submit button
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalButtonText;
                },
                function (error) {
                    console.error('Profile picture upload error:', error);
                    alert('Error uploading profile picture: ' + error);

                    // Re-enable submit button
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalButtonText;
                }
            );
        }
    });


    // console.log('custom-ajax.js loaded on page:', window.location.pathname);

    // Debug: Check if essential elements exist
    function debugPageElements() {
        console.log('=== PAGE DEBUG ===');
        console.log('profilepicUploadForm exists:', !!document.getElementById('profilepicUploadForm'));
        console.log('profilepicUploadModal exists:', !!document.getElementById('profilepicUploadModal'));
        console.log('ajax_common_vars exists:', typeof ajax_common_vars !== 'undefined');
        console.log('sendFileUploadRequest exists:', typeof sendFileUploadRequest !== 'undefined');
        console.log('Bootstrap exists:', typeof bootstrap !== 'undefined');
    }

    // Run debug on DOM ready
    document.addEventListener('DOMContentLoaded', function () {
        console.log('DOM loaded on:', window.location.pathname);
        debugPageElements();
    });

    // Also debug when modal is shown (in case it's dynamic)
    document.addEventListener('show.bs.modal', function (e) {
        console.log('Modal shown:', e.target.id);
        if (e.target.id === 'profilepicUploadModal') {
            debugPageElements();
        }
    });


    // ============ test ============




    // Delete Item AJAX (Education, Training, Work Experience, References)
    const confirmDeleteReference = document.getElementById('confirmDeleteReference');
    if (confirmDeleteReference) {
        confirmDeleteReference.addEventListener('click', function () {
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
    }




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
            const institution = trainingEntry.querySelector('.institution').textContent;
            const startYear = trainingEntry.querySelector('.start-year');
            const endYear = trainingEntry.querySelector('.end-year');
            const startYearEl = trainingEntry.querySelector('.start-year');
            const endYearEl = trainingEntry.querySelector('.end-year');

            // Parse the text into a Date object
            const startDate = new Date(startYearEl.textContent.trim());
            const endDate = new Date(endYearEl.textContent.trim());

            // Format as YYYY-MM-DD
            const formatDate = (date) => {
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0'); // months are 0-based
                const day = String(date.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            };

            const formattedStart = formatDate(startDate);
            const formattedEnd = formatDate(endDate);


            // Fill form fields
            document.getElementById('trainingTitle').value = title;
            document.getElementById('institution').value = institution;
            document.getElementById('startYear').value = formattedStart;
            document.getElementById('endYear').value = formattedEnd;
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
    const editAboutModal = document.getElementById('editAboutModal');
    if (editAboutModal) {
        editAboutModal.addEventListener('hidden.bs.modal', function () {
            document.getElementById('aboutMeForm').reset();
        });
    }
    const educationFormmodal = document.getElementById('editPersonalInfoModal');
    if (educationFormmodal) {
        educationFormmodal.addEventListener('hidden.bs.modal', function () {
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
    }


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
if (workExperienceForm) {
    const experienceHiddenIdInput = document.createElement('input');
    experienceHiddenIdInput.type = 'hidden';
    experienceHiddenIdInput.id = 'experienceId';
    experienceHiddenIdInput.name = 'experienceId';
    workExperienceForm.appendChild(experienceHiddenIdInput);
}
// Initialize Quill editor
let quill;
const quillEditorContainer = document.getElementById('quillEditor');
if (quillEditorContainer) {
    quill = new Quill('#quillEditor', {
        theme: 'snow',
        placeholder: 'Describe your responsibilities...',
    });
} else {
    //console.error('Quill editor container not found');
}
// Handle currently working checkbox
const currentlyWorking = document.getElementById('currentlyWorking');
const endDateInput = document.getElementById('endDate');

if (currentlyWorking) {
    currentlyWorking.addEventListener('change', function () {
        if (this.checked) {
            endDateInput.disabled = true;
            endDateInput.value = '';
        } else {
            endDateInput.disabled = false;
        }
    });
}
const saveWorkExperienceButton = document.getElementById('saveWorkExperience');
if (saveWorkExperienceButton) {
    // Handle save button click
    saveWorkExperienceButton.addEventListener('click', function () {
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

        saveWorkExperienceButton.disabled = true;
        saveWorkExperienceButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';

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
                saveWorkExperienceButton.disabled = false;
                saveWorkExperienceButton.innerHTML = 'Save';
            },
            function (error) {
                alert('Error updating work experience: ' + error);
                // Re-enable submit button
                saveWorkExperienceButton.disabled = false;
                saveWorkExperienceButton.innerHTML = 'Save';
            }
        );
    });

}

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
        duration += `${remainingMonths} month${remainingMonths > 1 ? 's' : ''}`;
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

jQuery(document).ready(function($) {
    console.log('AJAX login script loaded');
    
    // Handle form submission
    $('#ajax-login-form').on('submit', function(e) {
        console.log('Form submitted');
        e.preventDefault();
        
        // Show loading spinner
        $('#login-submit .button-text').addClass('d-none');
        $('#login-submit .spinner-border').removeClass('d-none');
        $('#login-submit').prop('disabled', true);
        $('#login-loading-text').removeClass('d-none');
        
        // Get form data
        var form_data = {
            'action': 'ajaxlogin', // Changed from 'ajax_login' to 'ajaxlogin'
            'security': $('#ajax-login-form #security').val(),
            'log': $('#ajax-login-form #user_login').val(),
            'pwd': $('#ajax-login-form #user_password').val(),
            'rememberme': $('#ajax-login-form #rememberMe').is(':checked'),
            'redirect_to': $('#ajax-login-form input[name="redirect_to"]').val()
        };
        
        console.log('Form data:', form_data);
        
        // Send AJAX request
        $.ajax({
            type: 'POST',
            url: ajax_login_object.ajaxurl,
            data: form_data,
            dataType: 'json',
            success: function(response) {
                console.log('AJAX success:', response);
                
                // Hide loading spinner
                $('#login-submit .button-text').removeClass('d-none');
                $('#login-submit .spinner-border').addClass('d-none');
                $('#login-submit').prop('disabled', false);
                $('#login-loading-text').addClass('d-none');
                
                if (response.loggedin === true) {
                    // Show success message
                    $('#login-message').removeClass('alert-danger d-none').addClass('alert-success').html(response.message);
                    
                    // Redirect to profile page
                    setTimeout(function() {
                        window.location.href = response.redirect;
                    }, 1000);
                } else {
                    // Show error message
                    $('#login-message').removeClass('alert-success d-none').addClass('alert-danger').html(response.message);
                }
            },
            error: function(xhr, status, error) {
                console.log('AJAX error:', xhr.responseText);
                console.log('Status:', status);
                console.log('Error:', error);
                
                // Hide loading spinner
                $('#login-submit .button-text').removeClass('d-none');
                $('#login-submit .spinner-border').addClass('d-none');
                $('#login-submit').prop('disabled', false);
                
                // Show error message
                $('#login-message').removeClass('alert-success d-none').addClass('alert-danger').html('An error occurred. Please try again. Error: ' + error);
            }
        });
    });
    
    // Handle social login buttons
    $('.btn-outline-social').on('click', function(e) {
        e.preventDefault();
        var provider = $(this).data('provider');
        
        // For now, just show a message
        $('#login-message').removeClass('alert-danger d-none').addClass('alert-info').html('Social login with ' + provider + ' will be implemented soon.');
    });
});



// ============ Signup Page Ajax ============
jQuery(document).ready(function($) {
    console.log('Signup script loaded');
    
    // First, let's check if ajax_signup_object is defined
    if (typeof ajax_signup_object === 'undefined') {
        console.error('ajax_signup_object is not defined!');
        return;
    }
    
    console.log('ajax_signup_object:', ajax_signup_object);
    
    // Handle signup form submission
    $('#ajax-signup-form').on('submit', function(e) {
        console.log('Form submitted');
        e.preventDefault();
        
        // Reset previous messages
        $('#signup-message').removeClass('alert-danger alert-success d-none').html('');
        
        // Validate form
        var isValid = true;
        var errorMessage = '';
        
        // Check if passwords match
        if ($('#floatingPassword').val() !== $('#floatingConfirmPassword').val()) {
            isValid = false;
            errorMessage = ajax_signup_object.passwords_do_not_match;
            $('#passwordMatch').text(errorMessage);
        } else {
            $('#passwordMatch').text('');
        }
        
        // Check if terms are accepted
        if (!$('#termsCheck').is(':checked')) {
            isValid = false;
            errorMessage = ajax_signup_object.accept_terms_error;
        }
        
        if (!isValid) {
            $('#signup-message').removeClass('d-none').addClass('alert-danger').html(errorMessage);
            return;
        }
        
        // Show loading spinner
        $('#signup-submit .button-text').addClass('d-none');
        $('#signup-submit .spinner-border').removeClass('d-none');
        $('#signup-submit').prop('disabled', true);
        $('#loading-text').removeClass('d-none');
        
        // Get form data
        var form_data = {
            'action': 'ajaxsignup',
            'security': $('#ajax-signup-form #security').val(),
            'username': $('#floatingUsername').val(),
            'email': $('#floatingEmail').val(),
            'password': $('#floatingPassword').val(),
            'confirm_password': $('#floatingConfirmPassword').val(),
            'terms': $('#termsCheck').is(':checked'),
            'redirect_to': $('#ajax-signup-form input[name="redirect_to"]').val()
        };
        
        console.log('Form data:', form_data);
        
        // Send AJAX request
        $.ajax({
            type: 'POST',
            url: ajax_signup_object.ajaxurl,
            data: form_data,
            dataType: 'json',
            success: function(response) {
                console.log('AJAX success:', response);
                
                // Hide loading spinner
                $('#signup-submit .button-text').removeClass('d-none');
                $('#signup-submit .spinner-border').addClass('d-none');
                $('#signup-submit').prop('disabled', false);
                $('#loading-text').addClass('d-none');
                
                if (response.success === true) {
                    // Show success message
                    $('#signup-message').removeClass('alert-danger d-none').addClass('alert-success').html(response.message);
                    
                    // Redirect to profile page
                    setTimeout(function() {
                        window.location.href = response.redirect;
                    }, 1500);
                } else {
                    // Show error message
                    $('#signup-message').removeClass('alert-success d-none').addClass('alert-danger').html(response.message);
                }
            },
            error: function(xhr, status, error) {
                console.log('AJAX error:', xhr.responseText);
                console.log('Status:', status);
                console.log('Error:', error);
                
                // Hide loading spinner
                $('#signup-submit .button-text').removeClass('d-none');
                $('#signup-submit .spinner-border').addClass('d-none');
                $('#signup-submit').prop('disabled', false);
                
                // Show error message
                $('#signup-message').removeClass('alert-success d-none').addClass('alert-danger').html(ajax_signup_object.error_occurred);
            }
        });
    });
    
    // Handle social signup buttons
    $('.btn-outline-social').on('click', function(e) {
        e.preventDefault();
        var provider = $(this).data('provider');
        
        // For now, just show a message
        $('#signup-message').removeClass('alert-danger d-none').addClass('alert-info').html('Social signup with ' + provider + ' will be implemented soon.');
    });
    
    // Password strength checker
    $('#floatingPassword').on('input', function() {
        var password = $(this).val();
        var strength = 0;
        
        // Check password length
        if (password.length >= 8) {
            strength += 1;
        }
        
        // Check for lowercase letters
        if (password.match(/[a-z]+/)) {
            strength += 1;
        }
        
        // Check for uppercase letters
        if (password.match(/[A-Z]+/)) {
            strength += 1;
        }
        
        // Check for numbers
        if (password.match(/[0-9]+/)) {
            strength += 1;
        }
        
        // Check for special characters
        if (password.match(/[$@#&!]+/)) {
            strength += 1;
        }
        
        // Update strength bar
        var strengthBar = $('#passwordStrengthBar');
        strengthBar.removeClass('bg-danger bg-warning bg-success bg-info');
        
        if (strength < 2) {
            strengthBar.css('width', '20%').addClass('bg-danger');
        } else if (strength < 3) {
            strengthBar.css('width', '40%').addClass('bg-warning');
        } else if (strength < 4) {
            strengthBar.css('width', '60%').addClass('bg-info');
        } else if (strength < 5) {
            strengthBar.css('width', '80%').addClass('bg-success');
        } else {
            strengthBar.css('width', '100%').addClass('bg-success');
        }
    });
    
    // Check if passwords match
    $('#floatingConfirmPassword').on('input', function() {
        var password = $('#floatingPassword').val();
        var confirmPassword = $(this).val();
        var matchMessage = $('#passwordMatch');
        
        if (password !== confirmPassword) {
            matchMessage.text(ajax_signup_object.passwords_do_not_match);
        } else {
            matchMessage.text('');
        }
    });
});



// ============ Forgot Password AJAX =============//

jQuery(document).ready(function($) {
    // Handle forgot password form submission
    $('#ajax-forgot-password-form').on('submit', function(e) {
        e.preventDefault();
        
        // Reset previous messages
        $('#forgot-password-message').removeClass('alert-danger alert-success d-none').html('');
        
        // Show loading spinner
        $('#reset-password-submit .button-text').addClass('d-none');
        $('#reset-password-submit .spinner-border').removeClass('d-none');
        $('#reset-password-submit').prop('disabled', true);
        
        // Get form data
        var form_data = {
            'action': 'ajaxforgotpassword',
            'security': $('#ajax-forgot-password-form #security').val(),
            'email': $('#floatingEmail').val(),
            'redirect_to': $('#ajax-forgot-password-form input[name="redirect_to"]').val()
        };
        
        console.log('Form data:', form_data);
        
        // Send AJAX request
        $.ajax({
            type: 'POST',
            url: ajax_forgot_password_object.ajaxurl,
            data: form_data,
            dataType: 'json',
            success: function(response) {
                console.log('AJAX success:', response);
                
                // Hide loading spinner
                $('#reset-password-submit .button-text').removeClass('d-none');
                $('#reset-password-submit .spinner-border').addClass('d-none');
                $('#reset-password-submit').prop('disabled', false);
                
                if (response.success === true) {
                    // Show success message
                    $('#forgot-password-message').removeClass('alert-danger d-none').addClass('alert-success').html(response.message);
                    
                    // Clear the form
                    $('#ajax-forgot-password-form')[0].reset();
                    
                    // Redirect after a delay
                    setTimeout(function() {
                        window.location.href = response.redirect;
                    }, 3000);
                } else {
                    // Show error message
                    $('#forgot-password-message').removeClass('alert-success d-none').addClass('alert-danger').html(response.message);
                }
            },
            error: function(xhr, status, error) {
                console.log('AJAX error:', xhr.responseText);
                console.log('Status:', status);
                console.log('Error:', error);
                
                // Hide loading spinner
                $('#reset-password-submit .button-text').removeClass('d-none');
                $('#reset-password-submit .spinner-border').addClass('d-none');
                $('#reset-password-submit').prop('disabled', false);
                
                // Show error message
                $('#forgot-password-message').removeClass('alert-success d-none').addClass('alert-danger').html(ajax_forgot_password_object.error_occurred);
            }
        });
    });
});


// ============ Reset Password AJAX =============//
jQuery(document).ready(function($) {
    // Handle password reset form submission
    $('#ajax-password-reset-form').on('submit', function(e) {
        e.preventDefault();
        
        // Reset previous messages
        $('#password-reset-message').removeClass('alert-danger alert-success d-none').html('');
        
        // Validate form
        var isValid = true;
        var errorMessage = '';
        
        // Check if passwords match
        if ($('#floatingPassword').val() !== $('#floatingConfirmPassword').val()) {
            isValid = false;
            errorMessage = ajax_password_reset_object.passwords_do_not_match;
            $('#passwordMatch').text(errorMessage);
        } else {
            $('#passwordMatch').text('');
        }
        
        // Check password length
        if ($('#floatingPassword').val().length < 8) {
            isValid = false;
            if (errorMessage) errorMessage += '<br>';
            errorMessage += ajax_password_reset_object.password_too_short;
        }
        
        if (!isValid) {
            $('#password-reset-message').removeClass('d-none').addClass('alert-danger').html(errorMessage);
            return;
        }
        
        // Show loading spinner
        $('#password-reset-submit .button-text').addClass('d-none');
        $('#password-reset-submit .spinner-border').removeClass('d-none');
        $('#password-reset-submit').prop('disabled', true);
        
        
        // Get form data
        var form_data = {
            'action': 'ajaxpasswordreset',
            'security': $('#ajax-password-reset-form #security').val(),
            'key': $('#ajax-password-reset-form input[name="key"]').val(),
            'login': $('#ajax-password-reset-form input[name="login"]').val(),
            'password': $('#floatingPassword').val(),
            'confirm_password': $('#floatingConfirmPassword').val(),
            'redirect_to': $('#ajax-password-reset-form input[name="redirect_to"]').val()
        };
        
        console.log('Form data:', form_data);
        
        // Send AJAX request
        $.ajax({
            type: 'POST',
            url: ajax_password_reset_object.ajaxurl,
            data: form_data,
            dataType: 'json',
            success: function(response) {
                console.log('AJAX success:', response);
                
                // Hide loading spinner
                $('#password-reset-submit .button-text').removeClass('d-none');
                $('#password-reset-submit .spinner-border').addClass('d-none');
                $('#password-reset-submit').prop('disabled', false);
                
                if (response.success === true) {
                    // Show success message
                    $('#password-reset-message').removeClass('alert-danger d-none').addClass('alert-success').html(response.message);
                    
                    // Clear the form
                    $('#ajax-password-reset-form')[0].reset();
                    
                    // Redirect after a delay
                    setTimeout(function() {
                        window.location.href = response.redirect;
                    }, 1500);
                } else {
                    // Show error message
                    $('#password-reset-message').removeClass('alert-success d-none').addClass('alert-danger').html(response.message);
                }
            },
            error: function(xhr, status, error) {
                console.log('AJAX error:', xhr.responseText);
                console.log('Status:', status);
                console.log('Error:', error);
                
                // Hide loading spinner
                $('#password-reset-submit .button-text').removeClass('d-none');
                $('#password-reset-submit .spinner-border').addClass('d-none');
                $('#password-reset-submit').prop('disabled', false);
                
                // Show error message
                $('#password-reset-message').removeClass('alert-success d-none').addClass('alert-danger').html(ajax_password_reset_object.error_occurred);
            }
        });
    });
    
    // Password strength checker for reset form
    $('#floatingPassword').on('input', function() {
        var password = $(this).val();
        var strength = 0;
        
        // Check password length
        if (password.length >= 8) {
            strength += 1;
        }
        
        // Check for lowercase letters
        if (password.match(/[a-z]+/)) {
            strength += 1;
        }
        
        // Check for uppercase letters
        if (password.match(/[A-Z]+/)) {
            strength += 1;
        }
        
        // Check for numbers
        if (password.match(/[0-9]+/)) {
            strength += 1;
        }
        
        // Check for special characters
        if (password.match(/[$@#&!]+/)) {
            strength += 1;
        }
        
        // Update strength bar
        var strengthBar = $('#passwordStrengthBar');
        strengthBar.removeClass('bg-danger bg-warning bg-success bg-info');
        
        if (strength < 2) {
            strengthBar.css('width', '20%').addClass('bg-danger');
        } else if (strength < 3) {
            strengthBar.css('width', '40%').addClass('bg-warning');
        } else if (strength < 4) {
            strengthBar.css('width', '60%').addClass('bg-info');
        } else if (strength < 5) {
            strengthBar.css('width', '80%').addClass('bg-success');
        } else {
            strengthBar.css('width', '100%').addClass('bg-success');
        }
    });
    
    // Check if passwords match for reset form
    $('#floatingConfirmPassword').on('input', function() {
        var password = $('#floatingPassword').val();
        var confirmPassword = $(this).val();
        var matchMessage = $('#passwordMatch');
        
        if (password !== confirmPassword) {
            matchMessage.text(ajax_password_reset_object.passwords_do_not_match);
        } else {
            matchMessage.text('');
        }
    });
});

//*************************** */

document.addEventListener('DOMContentLoaded', function() {

// Email Notifications Toggle
    const emailNotifyToggle = document.getElementById('emailNotify');
    
    if (emailNotifyToggle) {
        // Set initial state from global variable
        emailNotifyToggle.checked = window.emailNotifications || false;
        
        emailNotifyToggle.addEventListener('change', function() {
            const enabled = this.checked;
            
            sendAjaxRequest(
                'email_notifications',
                'POST',
                { enabled: enabled },
                function(response) {
                    showSuccessMessage('Email notifications updated successfully');
                },
                function(error) {
                    // Revert toggle on error
                    emailNotifyToggle.checked = !enabled;
                    alert('Error updating email notifications: ' + error);
                }
            );
        });
    }

//  // Change Password Form
//     const changePasswordForm = document.getElementById('changePasswordForm');
//     const changePasswordBtn = document.getElementById('changePasswordBtn');
    
//     // Toggle password visibility
//     const toggleCurrentPassword = document.getElementById('toggleCurrentPassword');
//     const toggleNewPassword = document.getElementById('toggleNewPassword');
//     const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
    
//     // Function to toggle password visibility
//     function togglePasswordVisibility(toggleButton, passwordInput) {
//         const icon = toggleButton.querySelector('i');
        
//         if (passwordInput.type === 'password') {
//             passwordInput.type = 'text';
//             icon.classList.remove('fa-eye');
//             icon.classList.add('fa-eye-slash');
//         } else {
//             passwordInput.type = 'password';
//             icon.classList.remove('fa-eye-slash');
//             icon.classList.add('fa-eye');
//         }
//     }
    
//     if (toggleCurrentPassword) {
//         toggleCurrentPassword.addEventListener('click', function(e) {
//             e.preventDefault(); // Prevent form submission
//             const passwordInput = document.getElementById('currentPassword');
//             togglePasswordVisibility(this, passwordInput);
//         });
//     }
    
//     if (toggleNewPassword) {
//         toggleNewPassword.addEventListener('click', function(e) {
//             e.preventDefault(); // Prevent form submission
//             const passwordInput = document.getElementById('newPassword');
//             togglePasswordVisibility(this, passwordInput);
//         });
//     }
    
//     if (toggleConfirmPassword) {
//         toggleConfirmPassword.addEventListener('click', function(e) {
//             e.preventDefault(); // Prevent form submission
//             const passwordInput = document.getElementById('confirmPassword');
//             togglePasswordVisibility(this, passwordInput);
//         });
//     }
    
//     // Password strength indicator
//     const newPasswordInput = document.getElementById('newPassword');
//     const passwordStrengthBar = document.getElementById('passwordStrengthBar');
//     const passwordStrengthText = document.getElementById('passwordStrengthText');
    
//     if (newPasswordInput && passwordStrengthBar && passwordStrengthText) {
//         newPasswordInput.addEventListener('input', function() {
//             const password = this.value;
//             let strength = 0;
//             let feedback = [];
            
//             // Length check
//             if (password.length >= 8) {
//                 strength += 25;
//             } else {
//                 feedback.push('at least 8 characters');
//             }
            
//             // Uppercase letters
//             if (password.match(/[A-Z]/)) {
//                 strength += 25;
//             } else {
//                 feedback.push('uppercase letters');
//             }
            
//             // Lowercase letters
//             if (password.match(/[a-z]/)) {
//                 strength += 25;
//             } else {
//                 feedback.push('lowercase letters');
//             }
            
//             // Numbers
//             if (password.match(/[0-9]/)) {
//                 strength += 25;
//             } else {
//                 feedback.push('numbers');
//             }
            
//             // Special characters
//             if (password.match(/[^A-Za-z0-9]/)) {
//                 strength += 5; // Bonus for special characters
//             }
            
//             // Update strength bar
//             passwordStrengthBar.style.width = strength + '%';
            
//             // Update strength text and bar color
//             if (password.length === 0) {
//                 passwordStrengthBar.className = 'progress-bar';
//                 passwordStrengthText.textContent = 'Enter a password';
//             } else if (strength < 50) {
//                 passwordStrengthBar.className = 'progress-bar bg-danger';
//                 passwordStrengthText.textContent = 'Weak: Add ' + feedback.join(', ');
//             } else if (strength < 75) {
//                 passwordStrengthBar.className = 'progress-bar bg-warning';
//                 passwordStrengthText.textContent = 'Medium: Add ' + feedback.join(', ');
//             } else if (strength < 90) {
//                 passwordStrengthBar.className = 'progress-bar bg-info';
//                 passwordStrengthText.textContent = 'Strong';
//             } else {
//                 passwordStrengthBar.className = 'progress-bar bg-success';
//                 passwordStrengthText.textContent = 'Very Strong';
//             }
//         });
//     }
    

    
//     if (changePasswordForm) {
//         changePasswordForm.addEventListener('submit', function(e) {
//             e.preventDefault(); // Prevent form submission
            
//             // Get form elements
//             const currentPassword = document.getElementById('currentPassword');
//             const newPassword = document.getElementById('newPassword');
//             const confirmPassword = document.getElementById('confirmPassword');
            
//             // Check if elements exist
//             if (!currentPassword || !newPassword || !confirmPassword) {
//                 console.error('Form elements not found');
//                 alert('Form elements not found');
//                 return;
//             }
            
//             // Get values directly from the elements
//             const currentPasswordValue = currentPassword.value;
//             const newPasswordValue = newPassword.value;
//             const confirmPasswordValue = confirmPassword.value;
            
//             // Debug: Log the values
//             console.log('Form values:', {
//                 currentPassword: currentPasswordValue,
//                 newPassword: newPasswordValue,
//                 confirmPassword: confirmPasswordValue
//             });
            
//             // Basic validation
//             if (!currentPasswordValue) {
//                 alert('Current password is required');
//                 currentPassword.focus();
//                 return;
//             }
            
//             if (!newPasswordValue) {
//                 alert('New password is required');
//                 newPassword.focus();
//                 return;
//             }
            
//             if (!confirmPasswordValue) {
//                 alert('Confirm password is required');
//                 confirmPassword.focus();
//                 return;
//             }
            
//             if (newPasswordValue !== confirmPasswordValue) {
//                 alert('New passwords do not match');
//                 newPassword.focus();
//                 return;
//             }
            
//             // Get the submit button
//             const changePasswordBtn = document.getElementById('changePasswordBtn');
            
//             // Add loading state
//             const originalText = changePasswordBtn.innerHTML;
//             changePasswordBtn.disabled = true;
//             changePasswordBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Changing...';
            
//             // Prepare data for AJAX request
//             const data = {
//                 action: 'change_password',
//                 nonce: ajax_common_vars.profile_nonce,
//                 current_password: currentPasswordValue,
//                 new_password: newPasswordValue,
//                 confirm_password: confirmPasswordValue
//             };
            
//             // Send AJAX request using URL-encoded data
//             const xhr = new XMLHttpRequest();
//             xhr.open('POST', ajax_common_vars.ajaxurl, true);
//             xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
//             xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            
//             xhr.onreadystatechange = function () {
//                 if (xhr.readyState === 4) {
//                     if (xhr.status === 200) {
//                         try {
//                             const response = JSON.parse(xhr.responseText);
//                             console.log('AJAX response:', response);
//                             if (response.success) {
//                                 // Show success message
//                                 alert(response.data);
                                
//                                 // Close modal
//                                 const modal = bootstrap.Modal.getInstance(document.getElementById('changePasswordModal'));
//                                 if (modal) {
//                                     modal.hide();
//                                 }
                                
//                                 // Reset form
//                                 changePasswordForm.reset();
                                
//                                 // Reset button
//                                 changePasswordBtn.disabled = false;
//                                 changePasswordBtn.innerHTML = originalText;
                                
//                                 // Redirect to login page after a short delay
//                                 setTimeout(function() {
//                                     window.location.href = '<?php echo home_url('/login/'); ?>';
//                                 }, 2000);
//                             } else {
//                                 alert('Error: ' + (response.data || response.message));
//                                 // Reset button
//                                 changePasswordBtn.disabled = false;
//                                 changePasswordBtn.innerHTML = originalText;
//                             }
//                         } catch (e) {
//                             console.error('AJAX response parsing error:', e);
//                             alert('Invalid server response');
//                             // Reset button
//                             changePasswordBtn.disabled = false;
//                             changePasswordBtn.innerHTML = originalText;
//                         }
//                     } else {
//                         console.error('AJAX request failed with status:', xhr.status);
//                         alert('Request failed with status: ' + xhr.status);
//                         // Reset button
//                         changePasswordBtn.disabled = false;
//                         changePasswordBtn.innerHTML = originalText;
//                     }
//                 }
//             };
            
//             // Convert data to URL-encoded string
//             const urlEncodedData = Object.keys(data)
//                 .map(key => encodeURIComponent(key) + '=' + encodeURIComponent(data[key]))
//                 .join('&');
            
//             console.log('Sending AJAX request with data:', urlEncodedData);
//             xhr.send(urlEncodedData);
//         });
//     }
});

document.addEventListener('DOMContentLoaded', function() {
    console.log('🔧 DOM loaded, initializing change password functionality...');
    
    // Initialize when modal is shown
    const changePasswordModal = document.getElementById('changePasswordModal');
    if (changePasswordModal) {
        changePasswordModal.addEventListener('shown.bs.modal', function() {
            console.log('✅ Change password modal is now fully shown');
            initializeChangePassword();
        });
    }
    
    // Also initialize on page load in case modal is already open
    initializeChangePassword();
});

function initializeChangePassword() {
    console.log('🔧 Initializing change password functionality...');
    
    // Get all elements
    const changePasswordForm = document.getElementById('changePasswordForm');
    const changePasswordBtn = document.getElementById('changePasswordBtn');
    const toggleCurrentPassword = document.getElementById('toggleCurrentPassword');
    const toggleNewPassword = document.getElementById('toggleNewPassword');
    const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
    const newPasswordInput = document.getElementById('newPassword');
    const passwordStrengthBar = document.getElementById('passwordStrengthBar');
    const passwordStrengthText = document.getElementById('passwordStrengthText');
    
    // Debug: Check if elements exist
    console.log('🔍 Elements found:', {
        changePasswordForm: !!changePasswordForm,
        changePasswordBtn: !!changePasswordBtn,
        toggleCurrentPassword: !!toggleCurrentPassword,
        toggleNewPassword: !!toggleNewPassword,
        toggleConfirmPassword: !!toggleConfirmPassword,
        newPasswordInput: !!newPasswordInput
    });
    
    // Toggle password visibility function
    function togglePasswordVisibility(toggleButton, passwordInput) {
        const icon = toggleButton.querySelector('i');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
    
    // Initialize password visibility toggles
    if (toggleCurrentPassword) {
        toggleCurrentPassword.addEventListener('click', function(e) {
            e.preventDefault();
            const passwordInput = document.getElementById('currentPassword');
            if (passwordInput) {
                togglePasswordVisibility(this, passwordInput);
            }
        });
    }
    
    if (toggleNewPassword) {
        toggleNewPassword.addEventListener('click', function(e) {
            e.preventDefault();
            const passwordInput = document.getElementById('newPassword');
            if (passwordInput) {
                togglePasswordVisibility(this, passwordInput);
            }
        });
    }
    
    if (toggleConfirmPassword) {
        toggleConfirmPassword.addEventListener('click', function(e) {
            e.preventDefault();
            const passwordInput = document.getElementById('confirmPassword');
            if (passwordInput) {
                togglePasswordVisibility(this, passwordInput);
            }
        });
    }
    
    // Password strength indicator
    if (newPasswordInput && passwordStrengthBar && passwordStrengthText) {
        newPasswordInput.addEventListener('input', function() {
            const password = this.value;
            let strength = 0;
            let feedback = [];
            
            // Length check
            if (password.length >= 8) {
                strength += 25;
            } else {
                feedback.push('at least 8 characters');
            }
            
            // Uppercase letters
            if (password.match(/[A-Z]/)) {
                strength += 25;
            } else {
                feedback.push('uppercase letters');
            }
            
            // Lowercase letters
            if (password.match(/[a-z]/)) {
                strength += 25;
            } else {
                feedback.push('lowercase letters');
            }
            
            // Numbers
            if (password.match(/[0-9]/)) {
                strength += 25;
            } else {
                feedback.push('numbers');
            }
            
            // Special characters
            if (password.match(/[^A-Za-z0-9]/)) {
                strength += 5; // Bonus for special characters
            }
            
            // Update strength bar
            passwordStrengthBar.style.width = strength + '%';
            
            // Update strength text and bar color
            if (password.length === 0) {
                passwordStrengthBar.className = 'progress-bar';
                passwordStrengthText.textContent = 'Enter a password';
            } else if (strength < 50) {
                passwordStrengthBar.className = 'progress-bar bg-danger';
                passwordStrengthText.textContent = 'Weak: Add ' + feedback.join(', ');
            } else if (strength < 75) {
                passwordStrengthBar.className = 'progress-bar bg-warning';
                passwordStrengthText.textContent = 'Medium: Add ' + feedback.join(', ');
            } else if (strength < 90) {
                passwordStrengthBar.className = 'progress-bar bg-info';
                passwordStrengthText.textContent = 'Strong';
            } else {
                passwordStrengthBar.className = 'progress-bar bg-success';
                passwordStrengthText.textContent = 'Very Strong';
            }
        });
    }
    
    // Change password form submission
    if (changePasswordBtn) {
        // Remove any existing event listeners
        changePasswordBtn.replaceWith(changePasswordBtn.cloneNode(true));
        const newChangePasswordBtn = document.getElementById('changePasswordBtn');
        
        newChangePasswordBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            console.log('🔍 Change password button clicked');
            
            // Get fresh references to input elements
            const currentPasswordInput = document.getElementById('currentPassword');
            const newPasswordInput = document.getElementById('newPassword');
            const confirmPasswordInput = document.getElementById('confirmPassword');
            
            // Check if elements exist
            if (!currentPasswordInput || !newPasswordInput || !confirmPasswordInput) {
                console.error('❌ Form input elements not found');
                alert('Form elements not found. Please refresh the page.');
                return;
            }
            
            // Get values directly from inputs
            const currentPasswordValue = currentPasswordInput.value.trim();
            const newPasswordValue = newPasswordInput.value.trim();
            const confirmPasswordValue = confirmPasswordInput.value.trim();
            
            console.log('🔍 ACTUAL FORM VALUES:', {
                currentPassword: currentPasswordValue,
                newPassword: newPasswordValue,
                confirmPassword: confirmPasswordValue
            });
            
            // Client-side validation
            if (!currentPasswordValue) {
                alert('Please enter your current password.');
                currentPasswordInput.focus();
                return;
            }
            
            if (!newPasswordValue) {
                alert('Please enter a new password.');
                newPasswordInput.focus();
                return;
            }
            
            if (!confirmPasswordValue) {
                alert('Please confirm your new password.');
                confirmPasswordInput.focus();
                return;
            }
            
            if (newPasswordValue !== confirmPasswordValue) {
                alert('New passwords do not match. Please make sure both passwords are identical.');
                newPasswordInput.focus();
                return;
            }
            
            // Password strength validation
            if (newPasswordValue.length < 8) {
                alert('New password must be at least 8 characters long.');
                newPasswordInput.focus();
                return;
            }
            
            console.log('✅ All client-side validations passed');
            
            // Add loading state
            const originalText = newChangePasswordBtn.innerHTML;
            newChangePasswordBtn.disabled = true;
            newChangePasswordBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Changing...';
            
            // Prepare data for AJAX
            const params = new URLSearchParams();
            params.append('action', 'change_password');
            params.append('nonce', ajax_common_vars.profile_nonce);
            params.append('current_password', currentPasswordValue);
            params.append('new_password', newPasswordValue);
            params.append('confirm_password', confirmPasswordValue);
            
            console.log('📤 Sending AJAX request with data:', params.toString());
            
            // Send AJAX request
            const xhr = new XMLHttpRequest();
            xhr.open('POST', ajax_common_vars.ajaxurl, true);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    console.log('📥 Raw server response:', xhr.responseText);
                    console.log('📊 Response status:', xhr.status);
                    
                    // Reset button state
                    newChangePasswordBtn.disabled = false;
                    newChangePasswordBtn.innerHTML = originalText;
                    
                    if (xhr.status === 200) {
                        try {
                            const response = JSON.parse(xhr.responseText);
                            console.log('✅ Parsed server response:', response);
                            
                            if (response.success) {
                                changePasswordForm.classList.add('d-none');
                                document.getElementById('cngpasssuccessMessage').classList.remove('d-none');
                                document.getElementById('cngpasssuccessMessagetext').textContent = response.data;
                           
                                // Success handling
                               // alert('Success: ' + response.data);
                                
                                // Close modal
                                const modalElement = document.getElementById('changePasswordModal');
                                const modal = bootstrap.Modal.getInstance(modalElement);
                                if (modal) {
                                    setTimeout(function(){
                                        modal.hide();
                                    }, 2000); // Close after 1.5 seconds
                                }
                                
                                // Reset form
                                if (changePasswordForm) {
                                    changePasswordForm.reset();
                                }
                                
                                // Reset password strength indicator
                                if (passwordStrengthBar && passwordStrengthText) {
                                    passwordStrengthBar.style.width = '0%';
                                    passwordStrengthBar.className = 'progress-bar';
                                    passwordStrengthText.textContent = 'Enter a password';
                                }
                                
                                // Redirect to login page after success - using the correct login slug
                                // setTimeout(function() {
                                //     // Use home_url + /login/ as you mentioned
                                //     window.location.href = ajax_common_vars.home_url + '/login/';
                                // }, 1500); // Reduced to 1.5 seconds for better UX
                            } else {
                                changePasswordForm.classList.add('d-none');
                                document.getElementById('cngpasserrorMessage').classList.remove('d-none');
                                document.getElementById('cngpasserrorMessagetext').textContent = response.data;
                                // Error handling
                                // Server returned error
                                //alert('Errora: ' + (response.data || 'Unknown server error'));
                            }
                        } catch (e) {
                            console.error('❌ JSON parse error:', e);
                            alert('Errorb: Invalid response from server. Please try again.');
                        }
                    } else {
                        console.error('❌ HTTP error:', xhr.status);
                        alert('Errorc: Request failed with status ' + xhr.status);
                    }
                }
            };
            
            xhr.onerror = function() {
                console.error('❌ Network error occurred');
                alert('Error: Network error. Please check your connection and try again.');
                newChangePasswordBtn.disabled = false;
                newChangePasswordBtn.innerHTML = originalText;
            };
            
            // Send the request
            xhr.send(params.toString());
        });
        
        console.log('✅ Change password button event listener attached');
    } else {
        console.error('❌ Change password button not found');
    }
}