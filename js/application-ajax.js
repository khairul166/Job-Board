// Helper to send AJAX requests
function applysendAjaxRequest(action, method, data, successCallback, errorCallback) {
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
                        if (errorCallback) errorCallback(response); // send full response
                        else alert(response.data || 'Unknown error');
                    }
                } catch (e) {
                    if (errorCallback) errorCallback({ data: 'Invalid server response' });
                    else alert('Invalid server response');
                }
            } else {
                if (errorCallback) errorCallback({ data: 'Request failed with status: ' + xhr.status });
                else alert('Request failed with status: ' + xhr.status);
            }
        }
    };

    // Add action and nonce
    data.action = action;
    data.nonce = profile_nonce;

    // Convert to URL-encoded string
    const formData = new URLSearchParams();
    for (const key in data) {
        if (data.hasOwnProperty(key)) formData.append(key, data[key]);
    }

    xhr.send(formData.toString());
}

//========== Apply Button (AJAX) ========//
document.addEventListener('DOMContentLoaded', function () {
    const applicationForm = document.getElementById('applicationForm');
    const successMessage = document.getElementById('successMessage');
    const generalErrorText = document.getElementById('generalErrorText');
    const alreadyApplied = document.getElementById('alreadyapplied');
    const submitBtn = document.getElementById('applysubmitBtn');
    const applyModalEl = document.getElementById('applyModal');

    if (!applicationForm || !submitBtn) return;

    window.ajaxurl = window.ajaxurl || (window.application_vars ? application_vars.ajaxurl : undefined);
    window.profile_nonce = window.profile_nonce || (window.application_vars ? application_vars.nonce : undefined);

    submitBtn.addEventListener('click', function (event) {
        event.preventDefault();
        event.stopPropagation();

        // loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Applying...';

        // collect data
        const data = {
            user_id: application_vars?.user_id || 0,
            job_id: application_vars?.job_id || 0,
            full_name: application_vars?.full_name || '',
            email: application_vars?.email || '',
            contact_number: application_vars?.contact_number || '',
            resume: typeof application_vars?.resume_data === 'object'
                ? JSON.stringify(application_vars.resume_data)
                : (application_vars?.resume_data || '')
        };

        applysendAjaxRequest(
            'submit_application',
            'POST',
            data,
            // SUCCESS
            function onSuccess(response) {
                applicationForm.classList.add('d-none');
                successMessage.classList.remove('d-none');
                submitBtn.classList.add('d-none');

                generalErrorText.classList.add('d-none');
                alreadyApplied.classList.add('d-none');

                setTimeout(() => {
                    const modal = bootstrap.Modal.getInstance(applyModalEl);
                    if (modal) modal.hide();
                    resetApplicationModal();
                }, 5000);
            },
            // ERROR
            function onError(response) {
                console.log('Application error:', response.data);

                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-paper-plane me-2"></i>Apply';
                applicationForm.classList.add('d-none');

                if (response.data === "You have already applied for this job.") {
                    alreadyApplied.classList.remove('d-none');
                    generalErrorText.classList.add('d-none');
                } else {
                    generalErrorText.classList.remove('d-none');
                    alreadyApplied.classList.add('d-none');
                    // document.querySelector('#generalErrorText p').textContent = response.data || 'Application failed.';
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
