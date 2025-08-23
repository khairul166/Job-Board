//============== Job Filter =============//
document.addEventListener('DOMContentLoaded', function () {
    const jobCards = document.querySelectorAll('.job-card');
    const jobTypeFilter = document.getElementById('job-type-filter');
    const experienceFilter = document.getElementById('experience-filter');
    const industryFilter = document.getElementById('industry-filter');
    const resetBtn = document.getElementById('reset-filters');

    // Check if filter elements exist before proceeding
    if (!jobTypeFilter && !experienceFilter && !industryFilter && !resetBtn) {
        // console.log('Filter elements not found on this page, skipping filter setup.');
        return; // Exit early if no filter elements exist
    }

    function filterJobs() {
        const selectedJobType = jobTypeFilter ? jobTypeFilter.value.toLowerCase() : '';
        const selectedExperience = experienceFilter ? experienceFilter.value.toLowerCase() : '';
        const selectedIndustry = industryFilter ? industryFilter.value.toLowerCase() : '';

        jobCards.forEach(card => {
            const cardJobType = card.getAttribute('data-job-type') || '';
            const cardExperience = card.getAttribute('data-experience') || '';
            const cardIndustry = card.getAttribute('data-industry') || '';

            const matchesJobType = !selectedJobType || cardJobType.includes(selectedJobType);
            const matchesExperience = !selectedExperience || cardExperience === selectedExperience;
            const matchesIndustry = !selectedIndustry || cardIndustry === selectedIndustry;

            if (matchesJobType && matchesExperience && matchesIndustry) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    }

    // Add event listeners only if elements exist
    if (jobTypeFilter) {
        jobTypeFilter.addEventListener('change', filterJobs);
    } else {
        console.log('jobTypeFilter not found on this page');
    }

    if (experienceFilter) {
        experienceFilter.addEventListener('change', filterJobs);
    } else {
        console.log('experienceFilter not found on this page');
    }

    if (industryFilter) {
        industryFilter.addEventListener('change', filterJobs);
    } else {
        console.log('industryFilter not found on this page');
    }

    if (resetBtn) {
        resetBtn.addEventListener('click', function () {
            if (jobTypeFilter) jobTypeFilter.value = '';
            if (experienceFilter) experienceFilter.value = '';
            if (industryFilter) industryFilter.value = '';
            filterJobs();
        });
    } else {
        console.log('resetBtn not found on this page');
    }
});



//======== Resume Upload ==========//
document.addEventListener('DOMContentLoaded', function() {
    // Get the form element first
    const resumeUploadForm = document.getElementById('resumeUploadForm');
    
    // Only attach event listener if form exists
    if (resumeUploadForm) {
        resumeUploadForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const fileInput = document.getElementById('resumeFile');
            const message = document.getElementById('resumeUploadMessage');
            const resumeuploadsection = document.getElementById('resume-upload-section');
            
            // Add null checks for all elements
            if (!fileInput || !message || !resumeuploadsection) {
                console.error('Required elements not found');
                return;
            }
            
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
            
            // Show success message
            resumeuploadsection.classList.add('d-none');
            message.classList.remove('d-none');
            
            // Get the resume preview container
            const resumePreview = document.querySelector('.resume-preview');
            
            if (resumePreview) {
                // Update the resume preview with the new file information
                const fileName = file.name;
                const currentDate = new Date();
                const formattedDate = currentDate.toLocaleDateString('en-US', { 
                    year: 'numeric', 
                    month: 'short', 
                    day: 'numeric' 
                });
                
                // Update the preview content
                resumePreview.innerHTML = `
                    <i class="fas fa-file-pdf fa-3x text-danger mb-2"></i>
                    <p class="mb-1">${fileName}</p>
                    <small class="text-muted">Uploaded: ${formattedDate}</small>
                `;
            }
            
            // Show the download and remove buttons
            const buttonContainer = document.querySelector('.d-grid.gap-2');
            if (buttonContainer) {
                buttonContainer.style.display = 'block';
            }
            
            // Reset the form and close modal after a delay
            setTimeout(() => {
                if (message) message.classList.add('d-none');
                if (fileInput) fileInput.value = ''; // reset input
                
                const modalElement = document.getElementById('resumeUploadModal');
                if (modalElement) {
                    const modal = bootstrap.Modal.getInstance(modalElement);
                    if (modal) modal.hide();
                }
            }, 2000);
        });
    } else {
        // console.error('Resume upload form not found');
    }
    
    // Handle resume download using event delegation
    document.addEventListener('click', function(e) {
        // Check if the clicked element is the download button
        const downloadButton = e.target.closest('.btn-outline-primary');
        if (downloadButton && downloadButton.closest('.resume-section')) {
            // Get the file name from the resume preview
            const resumePreview = document.querySelector('.resume-preview');
            if (resumePreview) {
                const fileNameElement = resumePreview.querySelector('p.mb-1');
                
                if (fileNameElement) {
                    const fileName = fileNameElement.textContent;
                    
                    // Create a temporary link to download the file
                    const link = document.createElement('a');
                    link.href = '#'; // In a real application, this would be the actual file URL
                    link.download = fileName;
                    
                    // For demonstration purposes, show an alert
                    alert(`Downloading ${fileName}...`);
                    
                    // In a real application, you would trigger the download:
                    // link.click();
                }
            }
        }
    });
});




document.addEventListener('DOMContentLoaded', function () {
    // Password strength indicator
    const passwordInput = document.getElementById('floatingPassword');
    if (passwordInput) {
        passwordInput.addEventListener('input', function(e) {
            const password = e.target.value;
            const strengthBar = document.getElementById('passwordStrengthBar');
            let strength = 0;
            if (password.length > 0) strength += 20;
            if (password.length >= 8) strength += 20;
            if (/[A-Z]/.test(password)) strength += 20;
            if (/\d/.test(password)) strength += 20;
            if (/[^A-Za-z0-9]/.test(password)) strength += 20;
            strengthBar.style.width = strength + '%';
            if (strength < 40) {
                strengthBar.style.backgroundColor = '#dc3545'; // red
            } else if (strength < 80) {
                strengthBar.style.backgroundColor = '#fd7e14'; // orange
            } else {
                strengthBar.style.backgroundColor = '#198754'; // green
            }
        });
    }

    // Password match validation
    const confirmPasswordInput = document.getElementById('floatingConfirmPassword');
    if (confirmPasswordInput) {
        confirmPasswordInput.addEventListener('input', function(e) {
            const password = document.getElementById('floatingPassword').value;
            const confirmPassword = e.target.value;
            const matchText = document.getElementById('passwordMatch');
            if (confirmPassword === '') {
                matchText.textContent = '';
            } else if (password !== confirmPassword) {
                matchText.textContent = 'Passwords do not match!';
                matchText.className = 'text-danger small';
            } else {
                matchText.textContent = 'Passwords match!';
                matchText.className = 'text-success small';
            }
        });
    }
});



//========== Change Password ==========//
document.addEventListener('DOMContentLoaded', function() {
    // Get form elements
    const changePasswordForm = document.getElementById('changePasswordForm');
    const currentPasswordInput = document.getElementById('currentPassword');
    const newPasswordInput = document.getElementById('newPassword');
    const confirmPasswordInput = document.getElementById('confirmPassword');
    const changePasswordBtn = document.getElementById('changePasswordBtn');
    const passwordStrengthBar = document.getElementById('passwordStrengthBar');
    const passwordStrengthText = document.getElementById('passwordStrengthText');
    
    // Check if elements exist
    if (!changePasswordForm || !currentPasswordInput || !newPasswordInput || 
        !confirmPasswordInput || !changePasswordBtn || !passwordStrengthBar || !passwordStrengthText) {
        //console.error('One or more required elements not found');
        return;
    }
    
    // Toggle password visibility
    const toggleCurrentPassword = document.getElementById('toggleCurrentPassword');
    const toggleNewPassword = document.getElementById('toggleNewPassword');
    const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
    
    if (toggleCurrentPassword) {
        toggleCurrentPassword.addEventListener('click', function() {
            togglePasswordVisibility(currentPasswordInput, this);
        });
    }
    
    if (toggleNewPassword) {
        toggleNewPassword.addEventListener('click', function() {
            togglePasswordVisibility(newPasswordInput, this);
        });
    }
    
    if (toggleConfirmPassword) {
        toggleConfirmPassword.addEventListener('click', function() {
            togglePasswordVisibility(confirmPasswordInput, this);
        });
    }
    
    function togglePasswordVisibility(input, button) {
        if (!input || !button) return;
        
        const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
        input.setAttribute('type', type);
        
        // Toggle icon
        const icon = button.querySelector('i');
        if (icon) {
            if (type === 'password') {
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            } else {
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            }
        }
    }
    
    // Password strength checker
    if (newPasswordInput) {
        newPasswordInput.addEventListener('input', function() {
            const password = this.value;
            let strength = 0;
            let strengthLabel = '';
            let strengthClass = '';
            
            if (password.length >= 8) strength += 1;
            if (password.match(/[a-z]+/)) strength += 1;
            if (password.match(/[A-Z]+/)) strength += 1;
            if (password.match(/[0-9]+/)) strength += 1;
            if (password.match(/[$@#&!]+/)) strength += 1;
            
            // Update strength meter
            const strengthPercent = (strength / 5) * 100;
            passwordStrengthBar.style.width = strengthPercent + '%';
            
            // Update strength label and color
            switch(strength) {
                case 0:
                    strengthLabel = 'Very Weak';
                    strengthClass = 'bg-danger';
                    break;
                case 1:
                    strengthLabel = 'Weak';
                    strengthClass = 'bg-danger';
                    break;
                case 2:
                    strengthLabel = 'Fair';
                    strengthClass = 'bg-warning';
                    break;
                case 3:
                    strengthLabel = 'Good';
                    strengthClass = 'bg-info';
                    break;
                case 4:
                    strengthLabel = 'Strong';
                    strengthClass = 'bg-success';
                    break;
                case 5:
                    strengthLabel = 'Very Strong';
                    strengthClass = 'bg-success';
                    break;
            }
            
            passwordStrengthBar.className = 'progress-bar ' + strengthClass;
            passwordStrengthText.textContent = strengthLabel;
        });
    }
    
    // Handle change password button click
    if (changePasswordBtn) {
        changePasswordBtn.addEventListener('click', function() {
            // Reset validation states
            if (changePasswordForm) {
                changePasswordForm.classList.remove('was-validated');
            }
            
            if (currentPasswordInput) currentPasswordInput.classList.remove('is-invalid');
            if (newPasswordInput) newPasswordInput.classList.remove('is-invalid');
            if (confirmPasswordInput) confirmPasswordInput.classList.remove('is-invalid');
            
            // Get values
            const currentPassword = currentPasswordInput ? currentPasswordInput.value : '';
            const newPassword = newPasswordInput ? newPasswordInput.value : '';
            const confirmPassword = confirmPasswordInput ? confirmPasswordInput.value : '';
            
            // Validate current password
            if (!currentPassword) {
                if (currentPasswordInput) currentPasswordInput.classList.add('is-invalid');
                return;
            }
            
            // Validate new password
            if (!newPassword) {
                if (newPasswordInput) newPasswordInput.classList.add('is-invalid');
                return;
            }
            
            // Validate password length
            if (newPassword.length < 8) {
                if (newPasswordInput) newPasswordInput.classList.add('is-invalid');
                return;
            }
            
            // Validate confirm password
            if (!confirmPassword) {
                if (confirmPasswordInput) confirmPasswordInput.classList.add('is-invalid');
                return;
            }
            
            // Check if passwords match
            if (newPassword !== confirmPassword) {
                if (confirmPasswordInput) confirmPasswordInput.classList.add('is-invalid');
                return;
            }
            
            // Check if new password is same as current password
            if (currentPassword === newPassword) {
                if (newPasswordInput) newPasswordInput.classList.add('is-invalid');
                return;
            }
            
            // Show success message
            const successMessage = document.createElement('div');
            successMessage.className = 'alert alert-success alert-dismissible fade show position-fixed';
            successMessage.style.top = '20px';
            successMessage.style.right = '20px';
            successMessage.style.zIndex = '9999';
            successMessage.innerHTML = `
                <i class="fas fa-check-circle me-2"></i>Password changed successfully!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;
            document.body.appendChild(successMessage);
            
            // Auto-hide after 3 seconds
            setTimeout(() => {
                if (successMessage && successMessage.parentNode) {
                    successMessage.remove();
                }
            }, 3000);
            
            // Reset form and close modal
            if (changePasswordForm) changePasswordForm.reset();
            if (passwordStrengthBar) {
                passwordStrengthBar.style.width = '0%';
                passwordStrengthBar.className = 'progress-bar';
            }
            if (passwordStrengthText) passwordStrengthText.textContent = 'Enter a password';
            
            const modal = document.getElementById('changePasswordModal');
            if (modal) {
                const modalInstance = bootstrap.Modal.getInstance(modal);
                if (modalInstance) modalInstance.hide();
            }
        });
    }
    
    // Reset form when modal is closed
    const modal = document.getElementById('changePasswordModal');
    if (modal) {
        modal.addEventListener('hidden.bs.modal', function() {
            if (changePasswordForm) {
                changePasswordForm.reset();
                changePasswordForm.classList.remove('was-validated');
            }
            
            if (currentPasswordInput) currentPasswordInput.classList.remove('is-invalid');
            if (newPasswordInput) newPasswordInput.classList.remove('is-invalid');
            if (confirmPasswordInput) confirmPasswordInput.classList.remove('is-invalid');
            
            if (passwordStrengthBar) {
                passwordStrengthBar.style.width = '0%';
                passwordStrengthBar.className = 'progress-bar';
            }
            if (passwordStrengthText) passwordStrengthText.textContent = 'Enter a password';
            
            // Reset password visibility toggles
            const passwordInputs = modal.querySelectorAll('input[type="password"]');
            passwordInputs.forEach(input => {
                input.setAttribute('type', 'password');
            });
            
            const toggleButtons = modal.querySelectorAll('.btn-outline-secondary i');
            toggleButtons.forEach(icon => {
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            });
        });
    }
});

document.addEventListener('DOMContentLoaded', function () {
    // Get the form element first
    const aboutMeForm = document.getElementById('aboutMeForm');
    
    // Only attach event listener if form exists
    if (aboutMeForm) {
        aboutMeForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const aboutMeTextarea = document.getElementById('aboutMeTextarea');
            const profileSection = document.querySelector('.profile-section p');
            
            // Add null checks for all elements
            if (!aboutMeTextarea || !profileSection) {
                console.error('Required elements not found');
                return;
            }
            
            const updatedText = aboutMeTextarea.value;
            profileSection.innerText = updatedText;
            
            const editAboutModal = document.getElementById('editAboutModal');
            if (editAboutModal) {
                const modal = bootstrap.Modal.getInstance(editAboutModal);
                if (modal) modal.hide();
            }
        });
    } else {
        // console.error('About Me form not found');
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const filterItems = document.querySelectorAll('#filterDropdown + .dropdown-menu .dropdown-item');
    const jobCards = document.querySelectorAll('.job-card');
    
    filterItems.forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Update active state
            filterItems.forEach(i => i.classList.remove('active'));
            this.classList.add('active');
            
            // Update button text
            const filterText = this.textContent;
            document.querySelector('#filterDropdown').innerHTML = '<i class="fas fa-filter me-1"></i> ' + filterText;
            
            // Get filter value
            const filterValue = this.getAttribute('data-filter');
            
            // Filter job cards
            jobCards.forEach(card => {
                if (filterValue === 'all' || card.getAttribute('data-status') === filterValue) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });
});
document.addEventListener('DOMContentLoaded', function() {
const fileInput = document.getElementById('profilepic');
const previewImg = document.getElementById('profilepicPreview');

fileInput.addEventListener('change', function(e) {
    if (this.files && this.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            previewImg.src = e.target.result; // Set the src to the file data
        }
        
        reader.readAsDataURL(this.files[0]); // Convert file to data URL
    }
});
});