document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('awp-login-form');
    if (!form) return;

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        // UI Elements
        const submitButton = form.querySelector('button[type="submit"]');
        const messageEl = document.getElementById('awp-login-message');
        const originalButtonText = submitButton.textContent;

        // Set loading state
        submitButton.disabled = true;
        submitButton.textContent = 'Logging in...';
        messageEl.innerHTML = '';
        messageEl.classList.remove('error', 'success');

        try {
            const formData = new FormData(form);
            const response = await fetch(awp_ajax.ajax_url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    action: 'awp_ajax_login',
                    awp_nonce: awp_ajax.nonce,
                    email: formData.get('email'),
                    password: formData.get('password'),
                    redirect_to: formData.get('redirect_to') || ''
                })
            });

            const data = await response.json();

            if (!response.ok || !data.success) {
                throw new Error(data.data || 'Login failed');
            }

            // Success
            messageEl.classList.add('success');
            messageEl.textContent = data.data.message || 'Login successful!';
            
            // Redirect or reload
            if (data.data.redirect) {
                window.location.href = data.data.redirect;
            } else {
                window.location.reload();
            }

        } catch (error) {
            messageEl.classList.add('error');
            messageEl.textContent = error.message;
        } finally {
            submitButton.disabled = false;
            submitButton.textContent = originalButtonText;
        }
    });
});