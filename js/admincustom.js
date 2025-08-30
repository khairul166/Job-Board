jQuery(document).ready(function ($) {


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
        const rejectBtn = card.querySelector('.btn-reject');
        const scheduleBtn = card.querySelector('.btn-schedule');

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
            success: function (response) {
                console.log('AJAX response:', response);
                if (response.success) {
                    // Update status badge
                    statusBadge.className = 'status-badge status-shortlisted';
                    statusBadge.textContent = 'Shortlisted';

                    // Hide shortlist button
                    shortlistBtn.style.display = 'none';

                    // Show schedule interview button
                    if (scheduleBtn) {
                        scheduleBtn.style.display = 'inline-flex';
                    } else {
                        // If schedule button doesn't exist, create it
                        const newScheduleBtn = document.createElement('button');
                        newScheduleBtn.className = 'action-btn btn-schedule';
                        newScheduleBtn.setAttribute('data-applicant-id', applicantId);
                        newScheduleBtn.innerHTML = `
                        <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                        </svg>
                        Schedule Interview
                    `;

                        // Add click event listener
                        newScheduleBtn.addEventListener('click', function () {
                            const appId = this.getAttribute('data-applicant-id');
                            openScheduleModal(appId);
                        });

                        // Insert before reject button
                        rejectBtn.parentNode.insertBefore(newScheduleBtn, rejectBtn);
                    }

                    // Keep reject button unchanged
                    // rejectBtn remains visible and enabled

                    // Show success message
                    showMessage('Applicant has been shortlisted successfully!', 'success');
                } else {
                    showMessage('Error: ' + response.data, 'error');
                }
                // Remove loading state
                card.classList.remove('loading');
            },
            error: function (xhr, status, error) {
                console.error('AJAX Error:', error);
                console.log('XHR:', xhr);
                showMessage('AJAX Error: ' + error, 'error');
                card.classList.remove('loading');
            }
        });
    }




    // Use event delegation for dynamically added buttons
    $(document).on('click', '.btn-schedule', function () {
        const applicantId = $(this).data('applicant-id');
        openScheduleModal(applicantId);
    });

    // Function to open schedule modal
    function openScheduleModal(applicantId) {
        // Set the application ID in the modal
        document.getElementById('application-ids').value = applicantId;

        // Show the modal
        document.getElementById('interview-modal').style.display = 'block';
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
        const btnschedule = card.querySelector('.btn-schedule');
        const btnreschedule = card.querySelector('.btn-reschedule');
        const interviewinfo = card.querySelector('.interview-info');

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
            success: function (response) {
                console.log('AJAX response:', response);

                if (response.success) {
                    // Update status badge
                    statusBadge.className = 'status-badge status-rejected';
                    statusBadge.textContent = 'Rejected';
                    if(shortlistBtn){
                    // Update buttons
                    shortlistBtn.style.display = 'none';
                    }
                    if(btnschedule){
                        btnschedule.style.display = 'none';
                    }
                    if(btnreschedule){
                        btnreschedule.style.display = 'none';
                    }
                    if(interviewinfo){
                        interviewinfo.style.display = 'none';
                    }
                    rejectBtn.disabled = true;
                    rejectBtn.style.opacity = '0.5';
                    rejectBtn.innerHTML = `
                        <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                        Rejected `;

                    // Show success message
                    showMessage('Applicant has been rejected.', 'success');
                } else {
                    showMessage('Error: ' + response.data, 'error');
                }

                // Remove loading state
                card.classList.remove('loading');
            },
            error: function (xhr, status, error) {
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
            success: function (response) {
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
                    setTimeout(function () {
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
            error: function (xhr, status, error) {
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
    $(document).on('click', '.btn-shortlist', function (e) {
        e.preventDefault();
        const applicantId = $(this).data('applicant-id');
        console.log('Shortlist button clicked for applicant:', applicantId);
        shortlistApplicant(applicantId);
    });

    $(document).on('click', '.btn-reject', function (e) {
        e.preventDefault();
        const applicantId = $(this).data('applicant-id');
        console.log('Reject button clicked for applicant:', applicantId);
        rejectApplicant(applicantId);
    });

    $(document).on('click', '.btn-download', function (e) {
        e.preventDefault();
        const applicantId = $(this).data('applicant-id');
        console.log('Download button clicked for applicant:', applicantId);
        downloadCV(applicantId);
    });



    // Show CV function using iframe
    window.showCV = function (applicantId) {
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
    $(document).on('click', '.close-modal', function () {
        $('#cvModal').hide();
    });

    // Close modal when clicking outside of it
    $(window).on('click', function (event) {
        const modal = document.getElementById('cvModal');
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });
});



// jQuery(document).ready(function ($) {
//     // Handle status filter change
//     $('#status_filter').on('change', function () {
//         $('#statusFilterForm').submit();
//     });

//     // Update experience years when slider changes
//     $('#experience_slider').on('input', function () {
//         var value = $(this).val();
//         $('#experience_display').text(value + ' years');
//         $('#experience_years').val(value);
//     });

//     // Filter applications by experience if a value is selected
//     if ($('#experience_years').val() > 0) {
//         var minMonths = parseInt($('#experience_years').val()) * 12;
//         $('.applicant-card').each(function () {
//             var experienceMonths = parseInt($(this).data('experience-months'));
//             if (experienceMonths < minMonths) {
//                 $(this).hide();
//             }
//         });
//     }
// });

function copyLink(url) {
    navigator.clipboard.writeText(url).then(function () {
        alert("Link copied to clipboard!");
    }, function () {
        alert("Failed to copy link.");
    });
}


jQuery(document).ready(function ($) {
    // // Handle status filter change
    // $('#status_filter').on('change', function () {
    //     $('#statusFilterForm').submit();
    // });

    // // Update experience years when slider changes
    // $('#experience_slider').on('input', function () {
    //     var value = $(this).val();
    //     $('#experience_display').text(value + ' years');
    //     $('#experience_years').val(value);
    // });

    // // Filter applications by experience if a value is selected
    // if ($('#experience_years').val() > 0) {
    //     var minMonths = parseInt($('#experience_years').val()) * 12;
    //     $('.applicant-card').each(function () {
    //         var experienceMonths = parseInt($(this).data('experience-months'));
    //         if (experienceMonths < minMonths) {
    //             $(this).hide();
    //         }
    //     });
    // }

    // Handle checkbox selection for bulk actions
    $(".container").on("change", ".application-checkbox", function () {
        if ($(".application-checkbox:checked").length > 0) {
            $(".bulk-actions").show();
        } else {
            $(".bulk-actions").hide();
        }
    });


// Handle bulk action
$("#do-bulk-action").on("click", function () {
    var action = $("#bulk-action-select").val();
    if (!action) return;
    
    var applicationIds = [];
    $(".application-checkbox:checked").each(function () {
        applicationIds.push($(this).val());
    });
    
    if (applicationIds.length === 0) return;
    
    if (action === "schedule") {
        // Show modal for bulk scheduling using the alternative function
        console.log("Bulk scheduling for applications:", applicationIds);
        showInterviewModal(null, null, false, applicationIds.join(","));
    } else {
        // Perform bulk action (shortlist or reject)
        performBulkAction(action, applicationIds);
    }
});

    function performBulkAction(action, applicationIds) {
        $.ajax({
            url: job_applications_vars.ajaxurl,
            type: "POST",
            data: {
                action: "bulk_" + action,
                application_ids: applicationIds,
                nonce: job_applications_vars.nonce
            },
            success: function (response) {
                if (response.success) {
                    alert(response.data.message);
                    //location.reload();
                } else {
                    alert("Error: " + response.data);
                }
            }
        });
    }

    // Handle individual actions
    $(".btn-shortlist").on("click", function () {
        var applicationId = $(this).data("applicant-id");
        performIndividualAction("shortlist_applicant", applicationId);
    });

    $(".btn-reject").on("click", function () {
        var applicationId = $(this).data("applicant-id");
        performIndividualAction("reject_applicant", applicationId);
    });

    $(".btn-schedule").on("click", function () {
        var applicationId = $(this).data("applicant-id");
        showInterviewModal(null, null, false, applicationId);
    });

    $(".btn-reschedule").on("click", function () {
        var applicationId = $(this).data("applicant-id");
        var card = $(this).closest(".applicant-card");

        // Pre-fill form with existing data
        var interviewInfo = card.find(".interview-info").html();

        // Extract date from interview info
        var dateMatch = interviewInfo.match(/Interview:\s*([^<]+)/);
        var locationMatch = interviewInfo.match(/Location:\s*([^<]+)/);

        var interviewDate = dateMatch ? dateMatch[1].trim() : '';
        var interviewLocation = locationMatch ? locationMatch[1].trim() : '';

        // Convert date to datetime-local format
        var formattedDate = '';
        if (interviewDate) {
            var dateObj = new Date(interviewDate);
            formattedDate = dateObj.toISOString().slice(0, 16);
        }

        showInterviewModal(formattedDate, interviewLocation, true, applicationId);
    });

    function performIndividualAction(action, applicationId) {
        $.ajax({
            url: job_applications_vars.ajaxurl,
            type: "POST",
            data: {
                action: action,
                applicant_id: applicationId,
                nonce: job_applications_vars.nonce
            },
            success: function (response) {
                if (response.success) {
                    //location.reload();
                } else {
                    alert("Error: " + response.data);
                }
            }
        });
    }

// Update the showInterviewModal function to handle both string and number applicationId
function showInterviewModal(interviewDate, interviewLocation, isReschedule, applicationId) {
    console.log("showInterviewModal called with:", {
        interviewDate: interviewDate,
        interviewLocation: interviewLocation,
        isReschedule: isReschedule,
        applicationId: applicationId
    });
    
    // Check if modal exists
    var $modal = $("#interview-modal");
    if ($modal.length === 0) {
        console.error("Interview modal not found in DOM");
        alert("Error: Interview modal not found. Please refresh the page and try again.");
        return;
    }
    
    // Set the application ID value
    if (applicationId) {
        $("#application-ids").val(applicationId);
        console.log("Set application ID to:", applicationId);
        console.log("Verification - application-ids value:", $("#application-ids").val());
    } else {
        console.error("No application ID provided");
    }
    
    // Pre-fill values if provided
    if (interviewDate) {
        $("#interview-date").val(interviewDate);
    } else {
        $("#interview-date").val("");
    }
    
    if (interviewLocation) {
        $("#interview-location").val(interviewLocation);
    } else {
        $("#interview-location").val("");
    }
    
    // Clear notes
    $("#interview-notes").val("");
    
    // Update button text based on action
    if (isReschedule) {
        $("#save-interview").text("Update Interview");
    } else {
        $("#save-interview").text("Schedule Interview");
    }
    
    // Update modal title
    if (isReschedule) {
        $(".interview-modal-header h2").text("Reschedule Interview");
    } else {
        $(".interview-modal-header h2").text("Schedule Interview");
    }
    
    // If it's a bulk action, update the title to indicate multiple applications
    // Convert applicationId to string before checking for commas
    var applicationIdStr = String(applicationId);
    if (applicationIdStr.includes(",")) {
        var count = applicationIdStr.split(",").length;
        $(".interview-modal-header h2").text("Schedule Interview for " + count + " Applicants");
    }
    
    // Use a timeout to ensure the modal is shown
    setTimeout(function() {
        $modal.show();
        console.log("Modal shown with timeout");
        
        // Also try to set focus to force the modal to be active
        $modal.find('#interview-date').focus();
    }, 10);
}

    // // Handle interview form submission
    // $(document).on("click", "#save-interview", function (e) {
    //     e.preventDefault();

    //     var isReschedule = $(this).text() === "Update Interview";
    //     var applicationIdsValue = $("#application-ids").val();
    //     var $button = $(this); // Store reference to the button
    //     var originalButtonText = $button.text(); // Store original button text

    //     // Debug logging
    //     console.log("Application IDs Value:", applicationIdsValue);
    //     console.log("Application IDs input exists:", $("#application-ids").length);
    //     console.log("Nonce:", job_applications_vars.nonce);
    //     console.log("Is Reschedule:", isReschedule);

    //     // Check if applicationIdsValue is defined and not empty
    //     if (!applicationIdsValue) {
    //         alert("Error: Application ID not found");
    //         return;
    //     }

    //     // Split the value into an array
    //     var applicationIds = applicationIdsValue.split(",");

    //     // Validate the form
    //     var interviewDate = $("#interview-date").val();
    //     var interviewLocation = $("#interview-location").val();

    //     if (!interviewDate) {
    //         alert("Please select an interview date and time");
    //         return;
    //     }

    //     if (!interviewLocation) {
    //         alert("Please enter an interview location");
    //         return;
    //     }

    //     // Show spinner and disable button
    //     $button.prop('disabled', true);
    //     $button.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...');

    //     // Prepare the data for AJAX
    //     var data = {
    //         action: isReschedule ? "reschedule_interview" : "schedule_interview",
    //         application_ids: applicationIds,
    //         application_id: applicationIds[0], // For reschedule (single)
    //         interview_date: interviewDate,
    //         interview_location: interviewLocation,
    //         interview_notes: $("#interview-notes").val(),
    //         nonce: job_applications_vars.nonce
    //     };

    //     // Debug logging
    //     console.log("Data being sent:", data);

    //     // Send the AJAX request
    //     $.ajax({
    //         url: job_applications_vars.ajaxurl,
    //         type: "POST",
    //         data: data,
    //         beforeSend: function (xhr) {
    //             console.log("Sending AJAX request...");
    //         },
    //         success: function (response) {
    //             console.log("AJAX response:", response);
    //             if (response.success) {
    //                 // Hide modal
    //                 $("#interview-modal").hide();

    //                 // If it's a bulk action, just reload the page
    //                 if (applicationIds.length > 1) {
    //                     showMessage('Interviews scheduled successfully for ' + applicationIds.length + ' applicants!', 'success');
    //                     setTimeout(function () {
    //                         location.reload();
    //                     }, 1500);
    //                 } else {
    //                     // Get the application card
    //                     var applicantId = applicationIds[0];
    //                     var card = $("#applicant-" + applicantId);

    //                     if (card.length) {
    //                         // Update status badge
    //                         var statusBadge = card.find('.status-badge');
    //                         statusBadge.removeClass('status-new status-shortlisted status-rejected');
    //                         statusBadge.addClass('status-interview_scheduled');
    //                         statusBadge.text('Interview Scheduled');
    //                         var btnreschedule = card.find('.btn-reschedule');

    //                         // Update or create interview info
    //                         var interviewInfo = card.find('.interview-info');

    //                         if (interviewInfo.length) {
    //                             // Update existing interview info
    //                             interviewInfo.find('#interview_date').text(response.data.new_date);
    //                             interviewInfo.find('#location').text(response.data.new_location);
    //                         } else {
    //                             // Create new interview info
    //                             var appDate = card.find('.application-date');
    //                             if (appDate.length) {
    //                                 var newInterviewInfo = $('<div class="interview-info"></div>');
    //                                 newInterviewInfo.html(`
    //                                 <strong>Interview:</strong> <span id="interview_date">${response.data.new_date}</span><br>
    //                                 <strong>Location:</strong> <span id="location">${response.data.new_location}</span>
    //                             `);
    //                                 appDate.after(newInterviewInfo);
    //                             }
    //                         }

    //                         // Update buttons based on interview date
    //                         var actionsContainer = card.find('.applicant-actions');
    //                         var scheduleBtn = actionsContainer.find('.btn-schedule');

    //                         // Check if interview date has passed
    //                         var interviewDateTime = new Date(interviewDate);
    //                         var currentDateTime = new Date();
    //                         var interviewPassed = interviewDateTime < currentDateTime;
    //                         console.log("Interview DateTime:", interviewDateTime);
    //                         console.log("Current DateTime:", currentDateTime);
    //                         console.log("Interview Passed:", interviewPassed);

    //                         if (interviewPassed) {
    //                             // Replace schedule button with reschedule button
    //                             if (scheduleBtn.length) {
    //                                 scheduleBtn.removeClass('btn-schedule');
    //                                 scheduleBtn.addClass('btn-reschedule');
    //                                 scheduleBtn.prop('disabled', false);
    //                                 scheduleBtn.html(`
    //                             <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor">
    //                                 <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
    //                             </svg>
    //                             Reschedule
    //                         `);
    //                             }
    //                         } else {
    //                             // Replace schedule button with disabled scheduled button
    //                             if (scheduleBtn.length) {
    //                                 scheduleBtn.removeClass('btn-schedule');
    //                                 scheduleBtn.prop('disabled', true);

    //                                 // Check if this is a reschedule (second time scheduling)
    //                                 if (isReschedule) {
    //                                     // Show "Rescheduled" button for rescheduled interviews
    //                                     scheduleBtn.html(`
    //                                     <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor">
    //                                         <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
    //                                     </svg>
    //                                     Rescheduled
    //                                 `);
    //                                 } else {
    //                                     // Show "Scheduled" button for initial scheduling
    //                                     scheduleBtn.html(`
    //                                     <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor">
    //                                         <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
    //                                     </svg>
    //                                     Scheduled
    //                                 `);
    //                                 }
    //                             }
    //                         }
    //                     }

    //                     // Show success message
    //                     showMessage('Interview scheduled successfully!', 'success');
    //                 }
    //             } else {
    //                 alert("Error: " + response.data);
    //             }

    //             // Reset button state
    //             $button.prop('disabled', false);
    //             $button.html(originalButtonText);
    //         },
    //         error: function (xhr, status, error) {
    //             console.log("AJAX Error: " + status + " - " + error);
    //             console.log("Response text:", xhr.responseText);
    //             alert("An error occurred. Please try again.");

    //             // Reset button state
    //             $button.prop('disabled', false);
    //             $button.html(originalButtonText);
    //         }
    //     });
    // });


    // Handle interview form submission
$(document).on("click", "#save-interview", function (e) {
    e.preventDefault();
    var isReschedule = $(this).text() === "Update Interview";
    var applicationIdsValue = $("#application-ids").val();
    var $button = $(this); // Store reference to the button
    var originalButtonText = $button.text(); // Store original button text
    
    // Debug logging
    console.log("Application IDs Value:", applicationIdsValue);
    console.log("Application IDs input exists:", $("#application-ids").length);
    console.log("Nonce:", job_applications_vars.nonce);
    console.log("Is Reschedule:", isReschedule);
    
    // Check if applicationIdsValue is defined and not empty
    if (!applicationIdsValue) {
        alert("Error: Application ID not found");
        return;
    }
    
    // Split the value into an array
    var applicationIds = applicationIdsValue.split(",");
    
    // Validate the form
    var interviewDate = $("#interview-date").val();
    var interviewLocation = $("#interview-location").val();
    
    if (!interviewDate) {
        alert("Please select an interview date and time");
        return;
    }
    
    if (!interviewLocation) {
        alert("Please enter an interview location");
        return;
    }
    
    // Show spinner and disable button
    $button.prop('disabled', true);
    $button.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...');
    
    // Prepare the data for AJAX
    var data = {
        action: isReschedule ? "reschedule_interview" : "schedule_interview",
        application_ids: applicationIds,
        application_id: applicationIds[0], // For reschedule (single)
        interview_date: interviewDate,
        interview_location: interviewLocation,
        interview_notes: $("#interview-notes").val(),
        nonce: job_applications_vars.nonce
    };
    
    // Debug logging
    console.log("Data being sent:", data);
    
    // Send the AJAX request
    $.ajax({
        url: job_applications_vars.ajaxurl,
        type: "POST",
        data: data,
        beforeSend: function (xhr) {
            console.log("Sending AJAX request...");
        },
        success: function (response) {
            console.log("AJAX response:", response);
            if (response.success) {
                // Hide modal
                $("#interview-modal").hide();
                
                // If it's a bulk action, just reload the page
                if (applicationIds.length > 1) {
                    showMessage('Interviews scheduled successfully for ' + applicationIds.length + ' applicants!', 'success');
                    setTimeout(function () {
                        location.reload();
                    }, 1500);
                } else {
                    // Get the application card
                    var applicantId = applicationIds[0];
                    var card = $("#applicant-" + applicantId);
                    
                    if (card.length) {
                        // Update status badge
                        var statusBadge = card.find('.status-badge');
                        statusBadge.removeClass('status-new status-shortlisted status-rejected');
                        statusBadge.addClass('status-interview_scheduled');
                        statusBadge.text('Interview Scheduled');
                        
                        // Update or create interview info
                        var interviewInfo = card.find('.interview-info');
                        if (interviewInfo.length) {
                            // Update existing interview info
                            interviewInfo.find('#interview_date').text(response.data.new_date);
                            interviewInfo.find('#location').text(response.data.new_location);
                        } else {
                            // Create new interview info
                            var appDate = card.find('.application-date');
                            if (appDate.length) {
                                var newInterviewInfo = $('<div class="interview-info"></div>');
                                newInterviewInfo.html(`
                                    <strong>Interview:</strong> <span id="interview_date">${response.data.new_date}</span><br>
                                    <strong>Location:</strong> <span id="location">${response.data.new_location}</span>
                                `);
                                appDate.after(newInterviewInfo);
                            }
                        }
                        
                        // Update buttons based on interview date
                        var actionsContainer = card.find('.applicant-actions');
                        var scheduleBtn = actionsContainer.find('.btn-schedule, .btn-reschedule');
                        
                        // Check if interview date has passed
                        var interviewDateTime = new Date(interviewDate);
                        var currentDateTime = new Date();
                        var interviewPassed = interviewDateTime < currentDateTime;
                        console.log("Interview DateTime:", interviewDateTime);
                        console.log("Current DateTime:", currentDateTime);
                        console.log("Interview Passed:", interviewPassed);
                        
                        if (interviewPassed) {
                            // Replace button with reschedule button
                            if (scheduleBtn.length) {
                                scheduleBtn.removeClass('btn-schedule');
                                scheduleBtn.addClass('btn-reschedule');
                                scheduleBtn.prop('disabled', false);
                                scheduleBtn.html(`
                                    <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
                                    </svg>
                                    Reschedule
                                `);
                            }
                        } else {
                            // Replace button with disabled scheduled button
                            if (scheduleBtn.length) {
                                scheduleBtn.removeClass('btn-schedule btn-reschedule');
                                scheduleBtn.prop('disabled', true);
                                
                                // Check if this is a reschedule (second time scheduling)
                                if (isReschedule) {
                                    // Show "Rescheduled" button for rescheduled interviews
                                    scheduleBtn.html(`
                                        <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                        </svg>
                                        Rescheduled
                                    `);
                                } else {
                                    // Show "Scheduled" button for initial scheduling
                                    scheduleBtn.html(`
                                        <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                        </svg>
                                        Scheduled
                                    `);
                                }
                            }
                        }
                    }
                    
                    // Show success message
                    showMessage('Interview scheduled successfully!', 'success');
                }
            } else {
                alert("Error: " + response.data);
            }
            
            // Reset button state
            $button.prop('disabled', false);
            $button.html(originalButtonText);
        },
        error: function (xhr, status, error) {
            console.log("AJAX Error: " + status + " - " + error);
            console.log("Response text:", xhr.responseText);
            alert("An error occurred. Please try again.");
            
            // Reset button state
            $button.prop('disabled', false);
            $button.html(originalButtonText);
        }
    });
});

// Function to show messages
function showMessage(message, type) {
    var messageHtml = '<div class="notice notice-' + type + ' is-dismissible"><p>' + message + '</p></div>';
    $('.wrap h1').after(messageHtml);
    
    // Auto-dismiss after 5 seconds
    setTimeout(function() {
        $('.notice.is-dismissible').fadeOut(function() {
            $(this).remove();
        });
    }, 5000);
}

// Event delegation for reschedule button
$(document).on("click", ".btn-reschedule", function() {
    var applicantId = $(this).data("applicant-id");
    var card = $(this).closest(".applicant-card");
    
    // Pre-fill form with existing data
    var interviewInfo = card.find(".interview-info");
    var interviewDate = interviewInfo.find('#interview_date').text();
    var interviewLocation = interviewInfo.find('#location').text();
    
    // Convert date to datetime-local format
    var formattedDate = '';
    if (interviewDate) {
        // Parse the date string and convert to the required format
        var dateObj = new Date(interviewDate);
        if (!isNaN(dateObj.getTime())) {
            formattedDate = dateObj.toISOString().slice(0, 16);
        }
    }
    
    // Show modal with pre-filled data
    showInterviewModal(formattedDate, interviewLocation, true, applicantId);
});

// Modal controls
$(document).on("click", ".interview-modal-close, #cancel-interview", function () {
    $("#interview-modal").hide();
});

// Close modal when clicking outside of it
$(document).on("click", function (event) {
    if ($(event.target).closest(".interview-modal-content").length === 0 &&
        $(event.target).closest(".btn-schedule, .btn-reschedule").length === 0) {
        $("#interview-modal").hide();
    }
});

});

jQuery(document).ready(function($) {
            class AgeRangeSlider {
                constructor() {
                    this.minAge = 0;
                    this.maxAge = 80;
                    this.selectedMin = 0;
                    this.selectedMax = 80;
                    
                    this.$track = $('.age-slider-track');
                    this.$range = $('.age-slider-range');
                    this.$thumbMin = $('.age-slider-thumb[data-thumb="min"]');
                    this.$thumbMax = $('.age-slider-thumb[data-thumb="max"]');
                    this.$displayMin = $('.age-value-min');
                    this.$displayMax = $('.age-value-max');
                    this.$inputMin = $('#min_age');
                    this.$inputMax = $('#max_age');
                    
                    this.trackWidth = 0;
                    this.isDragging = false;
                    this.activeThumb = null;
                    
                    this.init();
                }
                
                init() {
                    // Get initial values from inputs
                    this.selectedMin = parseInt(this.$inputMin.val()) || 0;
                    this.selectedMax = parseInt(this.$inputMax.val()) || 80;
                    
                    // Set initial positions
                    this.updateSlider();
                    
                    // Bind events
                    this.$thumbMin.on('mousedown touchstart', (e) => this.startDrag(e, 'min'));
                    this.$thumbMax.on('mousedown touchstart', (e) => this.startDrag(e, 'max'));
                    $(document).on('mousemove touchmove', (e) => this.onDrag(e));
                    $(document).on('mouseup touchend', () => this.endDrag());
                    
                    // Prevent text selection while dragging
                    $('.age-slider-thumb').on('selectstart', (e) => e.preventDefault());
                    
                    // Handle window resize
                    $(window).on('resize', () => {
                        this.trackWidth = this.$track.width();
                        this.updateSlider();
                    });
                }
                
                startDrag(e, thumb) {
                    e.preventDefault();
                    this.isDragging = true;
                    this.activeThumb = thumb;
                    this.trackWidth = this.$track.width();
                    
                    // Add visual feedback
                    $(`.age-slider-thumb[data-thumb="${thumb}"]`).addClass('active');
                }
                
                onDrag(e) {
                    if (!this.isDragging) return;
                    
                    // Handle both mouse and touch events
                    const clientX = e.type.includes('touch') ? e.originalEvent.touches[0].clientX : e.clientX;
                    
                    const trackRect = this.$track[0].getBoundingClientRect();
                    let position = Math.max(0, Math.min(this.trackWidth, clientX - trackRect.left));
                    const percentage = position / this.trackWidth;
                    const value = Math.round(this.minAge + percentage * (this.maxAge - this.minAge));
                    
                    if (this.activeThumb === 'min') {
                        this.selectedMin = Math.min(value, this.selectedMax - 1);
                    } else {
                        this.selectedMax = Math.max(value, this.selectedMin + 1);
                    }
                    
                    this.updateSlider();
                }
                
                endDrag() {
                    if (!this.isDragging) return;
                    
                    this.isDragging = false;
                    this.activeThumb = null;
                    
                    // Remove visual feedback
                    $('.age-slider-thumb').removeClass('active');
                    
                    // Update hidden inputs
                    this.$inputMin.val(this.selectedMin);
                    this.$inputMax.val(this.selectedMax);
                }
                
                updateSlider() {
                    // Calculate positions
                    const minPercent = (this.selectedMin - this.minAge) / (this.maxAge - this.minAge);
                    const maxPercent = (this.selectedMax - this.minAge) / (this.maxAge - this.minAge);
                    
                    // Update thumbs positions (accounting for thumb width)
                    this.$thumbMin.css('left', `calc(${minPercent * 100}% - 10px)`);
                    this.$thumbMax.css('left', `calc(${maxPercent * 100}% - 10px)`);
                    
                    // Update range background
                    this.$range.css({
                        left: `${minPercent * 100}%`,
                        right: `${100 - (maxPercent * 100)}%`
                    });
                    
                    // Update displays
                    this.$displayMin.text(this.selectedMin);
                    this.$displayMax.text(this.selectedMax);
                }
            }
            
            // Initialize the slider
            const ageSlider = new AgeRangeSlider();
            
            // Reset button functionality
            $('.btn-reset').on('click', function() {
                ageSlider.selectedMin = 0;
                ageSlider.selectedMax = 80;
                ageSlider.updateSlider();
                ageSlider.$inputMin.val(0);
                ageSlider.$inputMax.val(80);
            });
            
            // Apply button functionality
            $('.btn-apply').on('click', function() {
                alert(`Filters applied!\nMin Age: ${ageSlider.selectedMin}\nMax Age: ${ageSlider.selectedMax}`);
            });
        });



                document.addEventListener('DOMContentLoaded', function() {
            class ExperienceRangeSlider {
                constructor() {
                    this.minExperience = 0;
                    this.maxExperience = 20;
                    this.selectedMin = 0;
                    this.selectedMax = 20;
                    
                    this.$track = document.querySelector('.experience-slider-track');
                    this.$range = document.querySelector('.experience-slider-range');
                    this.$thumbMin = document.querySelector('.experience-slider-thumb[data-thumb="min"]');
                    this.$thumbMax = document.querySelector('.experience-slider-thumb[data-thumb="max"]');
                    this.$displayMin = document.querySelector('.experience-value-min');
                    this.$displayMax = document.querySelector('.experience-value-max');
                    this.$inputMin = document.getElementById('min_experience');
                    this.$inputMax = document.getElementById('max_experience');
                    
                    this.trackWidth = 0;
                    this.isDragging = false;
                    this.activeThumb = null;
                    
                    this.init();
                }
                
                init() {
                    // Set initial positions
                    this.updateSlider();
                    
                    // Bind events
                    this.$thumbMin.addEventListener('mousedown', (e) => this.startDrag(e, 'min'));
                    this.$thumbMax.addEventListener('mousedown', (e) => this.startDrag(e, 'max'));
                    document.addEventListener('mousemove', (e) => this.onDrag(e));
                    document.addEventListener('mouseup', () => this.endDrag());
                    
                    // Touch events for mobile
                    this.$thumbMin.addEventListener('touchstart', (e) => this.startDrag(e, 'min'));
                    this.$thumbMax.addEventListener('touchstart', (e) => this.startDrag(e, 'max'));
                    document.addEventListener('touchmove', (e) => this.onDrag(e));
                    document.addEventListener('touchend', () => this.endDrag());
                    
                    // Handle window resize
                    window.addEventListener('resize', () => {
                        this.trackWidth = this.$track.offsetWidth;
                        this.updateSlider();
                    });
                }
                
                startDrag(e, thumb) {
                    e.preventDefault();
                    this.isDragging = true;
                    this.activeThumb = thumb;
                    this.trackWidth = this.$track.offsetWidth;
                    
                    // Add visual feedback
                    document.querySelector(`.experience-slider-thumb[data-thumb="${thumb}"]`).classList.add('active');
                }
                
                onDrag(e) {
                    if (!this.isDragging) return;
                    
                    e.preventDefault();
                    
                    // Handle both mouse and touch events
                    const clientX = e.type.includes('touch') ? e.touches[0].clientX : e.clientX;
                    
                    const trackRect = this.$track.getBoundingClientRect();
                    let position = Math.max(0, Math.min(this.trackWidth, clientX - trackRect.left));
                    const percentage = position / this.trackWidth;
                    const value = Math.round(this.minExperience + percentage * (this.maxExperience - this.minExperience));
                    
                    if (this.activeThumb === 'min') {
                        this.selectedMin = Math.min(value, this.selectedMax - 1);
                    } else {
                        this.selectedMax = Math.max(value, this.selectedMin + 1);
                    }
                    
                    this.updateSlider();
                }
                
                endDrag() {
                    if (!this.isDragging) return;
                    
                    this.isDragging = false;
                    this.activeThumb = null;
                    
                    // Remove visual feedback
                    document.querySelectorAll('.experience-slider-thumb').forEach(thumb => {
                        thumb.classList.remove('active');
                    });
                    
                    // Update hidden inputs
                    this.$inputMin.value = this.selectedMin;
                    this.$inputMax.value = this.selectedMax;
                }
                
                updateSlider() {
                    // Calculate positions
                    const minPercent = (this.selectedMin - this.minExperience) / (this.maxExperience - this.minExperience);
                    const maxPercent = (this.selectedMax - this.minExperience) / (this.maxExperience - this.minExperience);
                    
                    // Update thumbs positions (accounting for thumb width)
                    this.$thumbMin.style.left = `calc(${minPercent * 100}% - 11px)`;
                    this.$thumbMax.style.left = `calc(${maxPercent * 100}% - 11px)`;
                    
                    // Update range background
                    this.$range.style.left = `${minPercent * 100}%`;
                    this.$range.style.right = `${100 - (maxPercent * 100)}%`;
                    
                    // Update displays
                    this.$displayMin.textContent = this.selectedMin === 20 ? '20+ years' : `${this.selectedMin} years`;
                    this.$displayMax.textContent = this.selectedMax === 20 ? '20+ years' : `${this.selectedMax} years`;
                }
            }
            
            // Initialize the slider
            const experienceSlider = new ExperienceRangeSlider();
            
            // Reset button functionality
            document.querySelector('.btn-reset').addEventListener('click', function() {
                experienceSlider.selectedMin = 0;
                experienceSlider.selectedMax = 20;
                experienceSlider.updateSlider();
                experienceSlider.$inputMin.value = 0;
                experienceSlider.$inputMax.value = 20;
            });
            
            // Apply button functionality
            document.querySelector('.btn-apply').addEventListener('click', function() {
                const min = experienceSlider.selectedMin;
                const max = experienceSlider.selectedMax;
                alert(`Filters applied!\nMinimum Experience: ${min} years\nMaximum Experience: ${max} years`);
            });
        });