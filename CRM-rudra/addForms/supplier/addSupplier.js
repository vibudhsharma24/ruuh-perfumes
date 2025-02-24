
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('form');
    const inputs = form.querySelectorAll('input, select, textarea');

    // Custom validation for required fields
    form.addEventListener('submit', (event) => {
        event.preventDefault();

        if (!form.checkValidity()) {
            inputs.forEach(input => {
                if (!input.checkValidity()) {
                    input.classList.add('is-invalid');
                } else {
                    input.classList.remove('is-invalid');
                }
            });
            form.classList.add('was-validated');
            return;
        }

        // Send form data via Fetch API
        const formData = new FormData(form);
        for (let [key, value] of formData.entries()) {
            console.log(`${key}: ${value}`);
        }
        fetch('addSupplier.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message); // Display success message
                    // form.reset();
                    location.replace("../../supplier.php");
                }
                else if (data.errors) {
                    handleServerErrors(data.errors);
                } else {
                    alert(data.message); // Display error message if provided
                }
            })
            .catch(error => console.error('Error:', error));

    });

    // Real-time validation feedback as user types
    inputs.forEach(input => {
        input.addEventListener('input', () => {
            if (input.checkValidity()) {
                input.classList.remove('is-invalid');
            } else {
                input.classList.add('is-invalid');
            }
        });
    });

    // Function to handle server-side errors
    function handleServerErrors(errors) {
        Object.keys(errors).forEach(field => {
            const input = document.querySelector(`[name="${field}"]`);
            if (input) {
                input.classList.add('is-invalid');
                let errorElement = input.nextElementSibling;
                if (!errorElement || !errorElement.classList.contains('invalid-feedback')) {
                    errorElement = document.createElement('div');
                    errorElement.className = 'invalid-feedback';
                    input.parentNode.insertBefore(errorElement, input.nextSibling);
                }
                errorElement.textContent = errors[field];
            }
        });
    }
});
