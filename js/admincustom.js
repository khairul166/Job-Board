jQuery(document).ready(function($) {

    // Debug: Check if script is loaded
    console.log('Job applications script initialized');
    
    // Check if required variables are available
    if (typeof job_applications_vars === 'undefined') {
        console.error('job_applications_vars is not defined');
        return;
    }
    
        function showMessage(message) {
            const successMessage = document.createElement('div');
            successMessage.className = 'notice notice-success settings-error is-dismissible';
            successMessage.style.top = '20px';
            successMessage.style.right = '20px';
            successMessage.style.zIndex = '9999';
            successMessage.innerHTML = `
                    ${message}
                `;
            document.body.appendChild(successMessage);

            // Auto-hide after 3 seconds
            setTimeout(() => {
                successMessage.remove();
            }, 3000);
        }
    
    // Function to shortlist applicant
    function shortlistApplicant(applicantId) {
        console.log('Shortlist function called for applicant:', applicantId);
        
        const card = document.getElementById(`applicant-${applicantId}`);
        if (!card) {
            console.error('Applicant card not found:', applicantId);
            return;
        }
        
        const statusBadge = card.querySelector('.status-badge');
        const shortlistBtn = card.querySelector('.btn-shortlist');
        
        // Add loading state
        card.classList.add('loading');
        
        // Prepare AJAX data
        const data = {
            action: 'shortlist_applicant',
            applicant_id: applicantId,
            nonce: job_applications_vars.nonce
        };
        
        console.log('Sending AJAX request:', data);
        
        // Make AJAX request
        $.ajax({
            url: job_applications_vars.ajaxurl,
            type: 'POST',
            data: data,
            dataType: 'json',
            success: function(response) {
                console.log('AJAX response:', response);
                
                if (response.success) {
                    // Update status badge
                    statusBadge.className = 'status-badge status-shortlisted';
                    statusBadge.textContent = 'Shortlisted';
                    
                    // Update button
                    shortlistBtn.disabled = true;
                    shortlistBtn.style.opacity = '0.5';
                    shortlistBtn.innerHTML = `
                        <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                        Shortlisted
                    `;
                    
                    // Show success message
                    showMessage('Applicant has been shortlisted successfully!', 'success');
                } else {
                    showMessage('Error: ' + response.data, 'error');
                }
                
                // Remove loading state
                card.classList.remove('loading');
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
                console.log('XHR:', xhr);
                showMessage('AJAX Error: ' + error, 'error');
                card.classList.remove('loading');
            }
        });
    }
    
    // Function to reject applicant
    function rejectApplicant(applicantId) {
        console.log('Reject function called for applicant:', applicantId);
        
        const card = document.getElementById(`applicant-${applicantId}`);
        if (!card) {
            console.error('Applicant card not found:', applicantId);
            return;
        }
        
        const statusBadge = card.querySelector('.status-badge');
        const shortlistBtn = card.querySelector('.btn-shortlist');
        const rejectBtn = card.querySelector('.btn-reject');
        
        // Add loading state
        card.classList.add('loading');
        
        // Prepare AJAX data
        const data = {
            action: 'reject_applicant',
            applicant_id: applicantId,
            nonce: job_applications_vars.nonce
        };
        
        console.log('Sending AJAX request:', data);
        
        // Make AJAX request
        $.ajax({
            url: job_applications_vars.ajaxurl,
            type: 'POST',
            data: data,
            dataType: 'json',
            success: function(response) {
                console.log('AJAX response:', response);
                
                if (response.success) {
                    // Update status badge
                    statusBadge.className = 'status-badge status-rejected';
                    statusBadge.textContent = 'Rejected';
                    
                    // Update buttons
                    shortlistBtn.disabled = true;
                    shortlistBtn.style.opacity = '0.5';
                    rejectBtn.disabled = true;
                    rejectBtn.style.opacity = '0.5';
                    
                    // Show success message
                    showMessage('Applicant has been rejected.', 'success');
                } else {
                    showMessage('Error: ' + response.data, 'error');
                }
                
                // Remove loading state
                card.classList.remove('loading');
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
                console.log('XHR:', xhr);
                showMessage('AJAX Error: ' + error, 'error');
                card.classList.remove('loading');
            }
        });
    }
    
// Function to download CV
function downloadCV(applicantId) {
    const card = document.getElementById(`applicant-${applicantId}`);
    if (!card) {
        console.error('Applicant card not found:', applicantId);
        return;
    }
    
    const downloadBtn = card.querySelector('.btn-download');
    
    // Add loading state
    card.classList.add('loading');
    if (downloadBtn) {
        downloadBtn.disabled = true;
        downloadBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Generating...';
    }
    
    // Prepare AJAX data
    const data = {
        action: 'download_cv',
        applicant_id: applicantId,
        nonce: job_applications_vars.nonce
    };
    
    console.log('Sending AJAX request:', data);
    
    // Make AJAX request
    $.ajax({
        url: job_applications_vars.ajaxurl,
        type: 'POST',
        data: data,
        dataType: 'json',
        success: function(response) {
            console.log('AJAX response:', response);
            
            if (response.success && response.data) {
                // Create a temporary link to download the file
                const link = document.createElement('a');
                link.href = response.data.file_url;
                link.download = response.data.file_name;
                link.target = '_blank'; // Open in new tab as fallback
                
                // For some browsers, it's necessary to add the link to the DOM
                document.body.appendChild(link);
                link.click();
                
                // Clean up
                setTimeout(function() {
                    document.body.removeChild(link);
                }, 100);
                
                // Show success message
                showMessage('CV downloaded successfully!', 'success');
            } else {
                // Handle error response
                const errorMsg = response.data ? response.data : 'Unknown error occurred';
                showMessage('Error: ' + errorMsg, 'error');
            }
            
            // Remove loading state
            card.classList.remove('loading');
            if (downloadBtn) {
                downloadBtn.disabled = false;
                downloadBtn.innerHTML = 'Download CV';
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', error);
            console.log('XHR:', xhr);
            
            // Try to get more detailed error message
            let errorMessage = 'AJAX Error: ' + error;
            try {
                const response = JSON.parse(xhr.responseText);
                if (response.data) {
                    errorMessage = response.data;
                }
            } catch (e) {
                // If parsing fails, use the default error message
            }
            
            showMessage(errorMessage, 'error');
            
            // Remove loading state
            card.classList.remove('loading');
            if (downloadBtn) {
                downloadBtn.disabled = false;
                downloadBtn.innerHTML = 'Download CV';
            }
        }
    });
}

// Helper function to show messages
function showMessage(message, type) {
    // Create message element if it doesn't exist
    let messageContainer = document.getElementById('message-container');
    if (!messageContainer) {
        messageContainer = document.createElement('div');
        messageContainer.id = 'message-container';
        messageContainer.style.position = 'fixed';
        messageContainer.style.top = '20px';
        messageContainer.style.right = '20px';
        messageContainer.style.zIndex = '9999';
        document.body.appendChild(messageContainer);
    }
    
    // Create message
    const messageElement = document.createElement('div');
    messageElement.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show`;
    messageElement.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    
    // Add message to container
    messageContainer.appendChild(messageElement);
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        messageElement.classList.remove('show');
        setTimeout(() => {
            if (messageElement.parentNode) {
                messageElement.parentNode.removeChild(messageElement);
            }
        }, 150);
    }, 5000);
}
    
    // Event delegation for button clicks
    $(document).on('click', '.btn-shortlist', function(e) {
        e.preventDefault();
        const applicantId = $(this).data('applicant-id');
        console.log('Shortlist button clicked for applicant:', applicantId);
        shortlistApplicant(applicantId);
    });
    
    $(document).on('click', '.btn-reject', function(e) {
        e.preventDefault();
        const applicantId = $(this).data('applicant-id');
        console.log('Reject button clicked for applicant:', applicantId);
        rejectApplicant(applicantId);
    });
    
    $(document).on('click', '.btn-download', function(e) {
        e.preventDefault();
        const applicantId = $(this).data('applicant-id');
        console.log('Download button clicked for applicant:', applicantId);
        downloadCV(applicantId);
    });
    
    // // Show CV function
    // window.showCV = function(applicantId) {
    //     console.log('Show CV function called for applicant:', applicantId);
        
    //     const modal = document.getElementById('cvModal');
    //     const cvContent = document.getElementById('cvContent');
        
    //     if (!modal || !cvContent) {
    //         showMessage('CV modal not found', 'error');
    //         return;
    //     }
        
    //     // Show loading state
    //     cvContent.innerHTML = '<div class="loading">Loading CV...</div>';
    //     modal.style.display = 'block';
        
    //     // Prepare AJAX data
    //     const data = {
    //         action: 'get_cv_details',
    //         applicant_id: applicantId,
    //         nonce: job_applications_vars.nonce
    //     };
        
    //     // Make AJAX request
    //     $.ajax({
    //         url: job_applications_vars.ajaxurl,
    //         type: 'POST',
    //         data: data,
    //         dataType: 'json',
    //         success: function(response) {
    //             if (response.success) {
    //                 cvContent.innerHTML = response.data.html;
    //             } else {
    //                 cvContent.innerHTML = '<div class="error">Error loading CV: ' + response.data + '</div>';
    //             }
    //         },
    //         error: function(xhr, status, error) {
    //             cvContent.innerHTML = '<div class="error">AJAX Error: ' + error + '</div>';
    //         }
    //     });
    // };
    
    // Show CV function using iframe
window.showCV = function(applicantId) {
    console.log('Show CV function called for applicant:', applicantId);
    
    const modal = document.getElementById('cvModal');
    const cvContent = document.getElementById('cvContent');
    
    if (!modal || !cvContent) {
        showMessage('CV modal not found', 'error');
        return;
    }
    
    // Clear previous content and create iframe
    cvContent.innerHTML = '';
    
    // Create iframe element
    const iframe = document.createElement('iframe');
    iframe.src = `http://localhost/job-board/resume/?applicant_id=${applicantId}`;
    iframe.style.width = '100%';
    iframe.style.height = '100%';
    iframe.style.border = 'none';
    iframe.style.minHeight = '500px';
    
    // Append iframe to modal content
    cvContent.appendChild(iframe);
    
    // Show the modal
    modal.style.display = 'block';
};

    // Close modal when clicking the close button
    $(document).on('click', '.close-modal', function() {
        $('#cvModal').hide();
    });
    
    // Close modal when clicking outside of it
    $(window).on('click', function(event) {
        const modal = document.getElementById('cvModal');
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });
});



jQuery(document).ready(function($) {
    // Handle status filter change
    $('#status_filter').on('change', function() {
        $('#statusFilterForm').submit();
    });
    
    // Update experience years when slider changes
    $('#experience_slider').on('input', function() {
        var value = $(this).val();
        $('#experience_display').text(value + ' years');
        $('#experience_years').val(value);
    });
    
    // Filter applications by experience if a value is selected
    if ($('#experience_years').val() > 0) {
        var minMonths = parseInt($('#experience_years').val()) * 12;
        $('.applicant-card').each(function() {
            var experienceMonths = parseInt($(this).data('experience-months'));
            if (experienceMonths < minMonths) {
                $(this).hide();
            }
        });
    }
});

function copyLink(url) {
    navigator.clipboard.writeText(url).then(function() {
        alert("Link copied to clipboard!");
    }, function() {
        alert("Failed to copy link.");
    });
}