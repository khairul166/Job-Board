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

                    // Update button
                    rejectBtn.disabled = false;
                    rejectBtn.style.opacity = '1';
                    rejectBtn.innerHTML = `
                        <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                        Reject `;
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
            error: function (xhr, status, error) {
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
            success: function (response) {
                console.log('AJAX response:', response);

                if (response.success) {
                    // Update status badge
                    statusBadge.className = 'status-badge status-rejected';
                    statusBadge.textContent = 'Rejected';

                    // Update buttons
                    shortlistBtn.disabled = false;
                    shortlistBtn.style.opacity = '1';
                    shortlistBtn.innerHTML = `
                        <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    Shortlist`;
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



jQuery(document).ready(function ($) {
    // Handle status filter change
    $('#status_filter').on('change', function () {
        $('#statusFilterForm').submit();
    });

    // Update experience years when slider changes
    $('#experience_slider').on('input', function () {
        var value = $(this).val();
        $('#experience_display').text(value + ' years');
        $('#experience_years').val(value);
    });

    // Filter applications by experience if a value is selected
    if ($('#experience_years').val() > 0) {
        var minMonths = parseInt($('#experience_years').val()) * 12;
        $('.applicant-card').each(function () {
            var experienceMonths = parseInt($(this).data('experience-months'));
            if (experienceMonths < minMonths) {
                $(this).hide();
            }
        });
    }
});

function copyLink(url) {
    navigator.clipboard.writeText(url).then(function () {
        alert("Link copied to clipboard!");
    }, function () {
        alert("Failed to copy link.");
    });
}


jQuery(document).ready(function ($) {
    // Handle status filter change
    $('#status_filter').on('change', function () {
        $('#statusFilterForm').submit();
    });

    // Update experience years when slider changes
    $('#experience_slider').on('input', function () {
        var value = $(this).val();
        $('#experience_display').text(value + ' years');
        $('#experience_years').val(value);
    });

    // Filter applications by experience if a value is selected
    if ($('#experience_years').val() > 0) {
        var minMonths = parseInt($('#experience_years').val()) * 12;
        $('.applicant-card').each(function () {
            var experienceMonths = parseInt($(this).data('experience-months'));
            if (experienceMonths < minMonths) {
                $(this).hide();
            }
        });
    }

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
            // Show modal for bulk scheduling
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
                    location.reload();
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
                    location.reload();
                } else {
                    alert("Error: " + response.data);
                }
            }
        });
    }

    // Function to show the interview modal
    function showInterviewModal(interviewDate, interviewLocation, isReschedule, applicationId) {
        // Create modal if it doesn't exist
        if ($("#interview-modal").length === 0) {
            var modalHTML = `
                <div id="interview-modal" class="interview-modal" style="display:none;">
                    <div class="interview-modal-content">
                        <div class="interview-modal-header">
                            <h2>${isReschedule ? 'Reschedule Interview' : 'Schedule Interview'}</h2>
                            <span class="interview-modal-close">&times;</span>
                        </div>
                        <div class="interview-modal-body">
                            <input type="hidden" id="application-ids" name="application_ids">
                            <div class="form-group">
                                <label for="interview-date">Date & Time</label>
                                <input type="datetime-local" id="interview-date" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="interview-location">Location</label>
                                <input type="text" id="interview-location" class="form-control" placeholder="Office address" required>
                            </div>
                            <div class="form-group">
                                <label for="interview-notes">Notes (Optional)</label>
                                <textarea id="interview-notes" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="interview-modal-footer">
                            <button type="button" class="button button-secondary" id="cancel-interview">Cancel</button>
                            <button type="button" class="button button-primary" id="save-interview">
                                ${isReschedule ? 'Update Interview' : 'Schedule Interview'}
                            </button>
                        </div>
                    </div>
                </div>
            `;
            $('body').append(modalHTML);
        }

        // Set the application ID value
        if (applicationId) {
            $("#application-ids").val(applicationId);
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

        // Show the modal
        $("#interview-modal").show();
    }

    // Handle interview form submission
    $(document).on("click", "#save-interview", function (e) {
        e.preventDefault();

        var isReschedule = $(this).text() === "Update Interview";
        var applicationIdsValue = $("#application-ids").val();

        // Debug logging
        console.log("Application IDs Value:", applicationIdsValue);
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
                    if (isReschedule) {
                        $("#interview-modal").hide();
                        document.getElementById('interview_date').innerHTML = response.data.new_date;
                        document.getElementById('location').innerHTML = response.data.new_location;
                    } else {
                        $("#interview-modal").hide();
                        // Select the application-date element
                        const appDate = document.querySelector('.application-date');

                        if (appDate) {
                        // Create the new block
                        const interviewInfo = document.createElement('div');
                        interviewInfo.classList.add('interview-info');
                        interviewInfo.innerHTML = `
                            <strong>Interview:</strong> <span id="interview_date">${response.data.new_date}</span><br>
                            <strong>Location:</strong> <span id="location">${response.data.new_location}</span>
                        `;

                        // Insert after .application-date
                        appDate.insertAdjacentElement('afterend', interviewInfo);
                        }

                    }
                } else {
                    alert("Error: " + response.data);
                }
            },
            error: function (xhr, status, error) {
                console.log("AJAX Error: " + status + " - " + error);
                console.log("Response text:", xhr.responseText);
                alert("An error occurred. Please try again.");
            }
        });
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