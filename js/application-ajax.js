// Helper to send AJAX requests - FIXED VERSION
function applysendAjaxRequest(action, method, data, successCallback, errorCallback) {
    // FIXED: Try multiple sources for ajaxurl
    let ajaxUrl;
    if (typeof ajaxurl !== 'undefined') {
        ajaxUrl = ajaxurl;
    } else if (window.ajax_common_vars && ajax_common_vars.ajaxurl) {
        ajaxUrl = ajax_common_vars.ajaxurl;
    } else if (window.application_vars && application_vars.ajaxurl) {
        ajaxUrl = application_vars.ajaxurl;
    } else {
        ajaxUrl = '/wp-admin/admin-ajax.php'; // Fallback
    }
    
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
                        if (errorCallback) errorCallback(response);
                        else alert(response.data || 'Unknown error');
                    }
                } catch (e) {
                    console.error('Response parsing error:', e);
                    if (errorCallback) errorCallback({ data: 'Invalid server response' });
                    else alert('Invalid server response');
                }
            } else {
                console.error('Request failed with status:', xhr.status);
                if (errorCallback) errorCallback({ data: 'Request failed with status: ' + xhr.status });
                else alert('Request failed with status: ' + xhr.status);
            }
        }
    };
    
    // Add action and nonce
    data.action = action;
    data.nonce = window.application_vars ? window.application_vars.nonce : '';
    
    // Convert to URL-encoded string
    const formData = new URLSearchParams();
    for (const key in data) {
        if (data.hasOwnProperty(key)) formData.append(key, data[key]);
    }
    
    console.log('Sending data to:', ajaxUrl); // Debug log
    console.log('Sending data:', data); // Debug log
    xhr.send(formData.toString());
}

//========== Apply Button (AJAX) - FIXED VERSION ========//
document.addEventListener('DOMContentLoaded', function () {
    const applicationForm = document.getElementById('applicationForm');
    const successMessage = document.getElementById('successMessage');
    const generalErrorText = document.getElementById('generalErrorText');
    const alreadyApplied = document.getElementById('alreadyapplied');
    const submitBtn = document.getElementById('applysubmitBtn');
    const applyModalEl = document.getElementById('applyModal');
    const applyBtn = document.getElementById('apply-btn');
    
    if (!applicationForm || !submitBtn) return;
    
    // FIXED: Set ajaxurl from multiple sources
    if (typeof ajaxurl === 'undefined') {
        if (window.ajax_common_vars && ajax_common_vars.ajaxurl) {
            window.ajaxurl = ajax_common_vars.ajaxurl;
        } else if (window.application_vars && application_vars.ajaxurl) {
            window.ajaxurl = application_vars.ajaxurl;
        } else {
            window.ajaxurl = '/wp-admin/admin-ajax.php';
        }
    }
    
    // Debug logs
    console.log('ajaxurl:', window.ajaxurl);
    console.log('ajax_common_vars:', window.ajax_common_vars);
    console.log('application_vars:', window.application_vars);
    
    // Check if application_vars is available
    if (!window.application_vars) {
        console.error('application_vars not available');
        alert('Configuration error. Please refresh the page.');
        return;
    }
    
    submitBtn.addEventListener('click', function (event) {
        event.preventDefault();
        event.stopPropagation();
        
        // loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Applying...';
        
        // Properly serialize resume data
        const resumeData = typeof application_vars.resume_data === 'object' 
            ? JSON.stringify(application_vars.resume_data)
            : application_vars.resume_data || '';
        
        // collect data
        const data = {
            user_id: application_vars.user_id || 0,
            job_id: application_vars.job_id || 0,
            full_name: application_vars.full_name || '',
            email: application_vars.email || '',
            contact_number: application_vars.contact_number || '',
            resume: resumeData
        };
        
        console.log('Application data:', data); // Debug log
        
        applysendAjaxRequest(
            'submit_application',
            'POST',
            data,
            // SUCCESS
            function onSuccess(response) {
                console.log('Application success:', response);
                applicationForm.classList.add('d-none');
                successMessage.classList.remove('d-none');
                submitBtn.classList.add('d-none');
                generalErrorText.classList.add('d-none');
                alreadyApplied.classList.add('d-none');
                applyBtn.disabled = true;
                applyBtn.innerHTML = '<i class="fas fa-paper-plane me-2"></i>Already Applied';
                setTimeout(() => {
                    const modal = bootstrap.Modal.getInstance(applyModalEl);
                    if (modal) modal.hide();
                    resetApplicationModal();
                }, 5000);
            },
            // ERROR
            function onError(response) {
                console.error('Application error:', response);
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-paper-plane me-2"></i>Apply';
                applicationForm.classList.add('d-none');
                
                const errorMsg = response.data || 'Application failed.';
                if (errorMsg === "You have already applied for this job.") {
                    alreadyApplied.classList.remove('d-none');
                    generalErrorText.classList.add('d-none');
                } else {
                    generalErrorText.classList.remove('d-none');
                    alreadyApplied.classList.add('d-none');
                    const errorTextElement = document.querySelector('#generalErrorText p');
                    if (errorTextElement) {
                        errorTextElement.textContent = errorMsg;
                    }
                }
            }
        );
    });
    
    // reset UI
    function resetApplicationModal() {
        applicationForm.classList.remove('d-none');
        submitBtn.classList.remove('d-none');
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-paper-plane me-2"></i>Apply';
        successMessage.classList.add('d-none');
        generalErrorText.classList.add('d-none');
        alreadyApplied.classList.add('d-none');
    }
    
    if (applyModalEl) {
        applyModalEl.addEventListener('hidden.bs.modal', resetApplicationModal);
    }
});