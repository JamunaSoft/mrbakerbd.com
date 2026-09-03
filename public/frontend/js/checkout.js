document.addEventListener('DOMContentLoaded', function() {
    // Time dropdown logic with dynamic filtering
    const btn = document.getElementById('delv_t-dropdown-btn');
    const list = document.getElementById('delv_t-dropdown-list');
    const selected = document.getElementById('delv_t-selected');
    const hidden = document.getElementById('delv_t');
    const dateInput = document.getElementById('delv_d');

    // All time slots
    const allTimeSlots = [
        "08:00 AM - 09:00 AM",
        "09:00 AM - 10:00 AM",
        "10:00 AM - 11:00 AM",
        "11:00 AM - 12:00 PM",
        "12:00 PM - 01:00 PM",
        "01:00 PM - 02:00 PM",
        "02:00 PM - 03:00 PM",
        "03:00 PM - 04:00 PM",
        "04:00 PM - 05:00 PM",
        "05:00 PM - 06:00 PM",
        "06:00 PM - 07:00 PM",
        "07:00 PM - 08:00 PM",
    ];

    // Get server date and time
    const serverDate = document.getElementById('server-date') ? document.getElementById('server-date').value : new Date().toISOString().split('T')[0];
    const serverHour = document.getElementById('server-time') ? parseInt(document.getElementById('server-time').value) : new Date().getHours();

    // Function to get available time slots based on selected date
    function getAvailableTimeSlots(selectedDate) {
        if (selectedDate === serverDate) {
            // For today, only show slots that are 3+ hours from now
            const threeHoursLater = serverHour + 3;
            return allTimeSlots.filter(slot => {
                // Extract hour from time slot
                const timeStart = slot.split(' - ')[0];
                const timeParts = timeStart.split(':');
                let hour = parseInt(timeParts[0]);
                const amPm = timeStart.slice(-2);

                // Convert to 24-hour format
                if (amPm === 'PM' && hour !== 12) {
                    hour += 12;
                } else if (amPm === 'AM' && hour === 12) {
                    hour = 0;
                }

                return hour >= threeHoursLater;
            });
        } else {
            // For future dates, show all time slots
            return allTimeSlots;
        }
    }

    // Function to update time slots dropdown
    function updateTimeSlots(selectedDate) {
        const availableSlots = getAvailableTimeSlots(selectedDate);

        // Clear existing options
        list.innerHTML = '';

        // Add available time slots
        availableSlots.forEach(slot => {
            const li = document.createElement('li');
            li.className = 'dropdown-item';
            li.setAttribute('data-value', slot);
            li.style.cssText = 'padding: 10px; cursor: pointer;';
            li.textContent = slot;

            // Add click event
            li.addEventListener('click', function() {
                selected.textContent = this.textContent;
                hidden.value = this.getAttribute('data-value');
                list.style.display = 'none';
            });

            // Add hover effects
            li.addEventListener('mouseover', function() {
                this.style.backgroundColor = '#f8f9fa';
            });

            li.addEventListener('mouseout', function() {
                this.style.backgroundColor = '';
            });

            list.appendChild(li);
        });

        // Reset selection if current selection is not available
        const currentSelection = hidden.value;
        if (currentSelection && !availableSlots.includes(currentSelection)) {
            hidden.value = '';
            selected.textContent = 'Select Delivery Time';
        }

        // Show message if no slots available
        if (availableSlots.length === 0) {
            const li = document.createElement('li');
            li.style.cssText = 'padding: 10px; color: #6c757d; font-style: italic;';
            li.textContent = 'No available time slots for selected date';
            list.appendChild(li);
        }
    }

    // Time dropdown toggle
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        list.style.display = list.style.display === 'block' ? 'none' : 'block';
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!btn.contains(e.target) && !list.contains(e.target)) {
            list.style.display = 'none';
        }
    });

    // Event listener for date change
    if (dateInput) {
        dateInput.addEventListener('change', function() {
            updateTimeSlots(this.value);
        });
    }

    // Initialize time slots on page load
    updateTimeSlots(dateInput ? dateInput.value : serverDate);

    // Reason dropdown logic (unchanged)
    const reasonBtn = document.getElementById('reason-dropdown-btn');
    const reasonList = document.getElementById('reason-dropdown-list');
    const reasonSelected = document.getElementById('reason-selected');
    const reasonHidden = document.getElementById('reason-hidden');

    if (reasonBtn && reasonList) {
        reasonBtn.addEventListener('click', function(e) {
            e.preventDefault();
            reasonList.style.display = reasonList.style.display === 'block' ? 'none' : 'block';
        });

        reasonList.querySelectorAll('.dropdown-item').forEach(function(item) {
            item.addEventListener('click', function() {
                reasonSelected.textContent = this.textContent;
                reasonHidden.value = this.getAttribute('data-value');
                reasonList.style.display = 'none';
            });
        });

        document.addEventListener('click', function(e) {
            if (!reasonBtn.contains(e.target) && !reasonList.contains(e.target)) {
                reasonList.style.display = 'none';
            }
        });
    }

    // Unified validation function
    function validateForm() {
        let isValid = true;

        // Field definitions
        const fields = [
            {
                id: 'checkout-first-name-1',
                errorId: 'error-customer_name',
                validate: value => value.trim().length > 0,
                message: 'Name is required'
            },
            {
                id: 'address',
                errorId: 'error-address_details',
                validate: value => value.trim().length > 0,
                message: 'Address is required'
            },
            {
                id: 'checkout-email-1',
                errorId: 'error-email',
                validate: value => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value),
                message: 'Valid email is required'
            },
            {
                id: 'checkout-phone-1',
                errorId: 'error-phone',
                validate: value => /^\+?\d{10,}$/.test(value.replace(/\s/g, '')),
                message: 'Valid phone number is required'
            },
            {
                id: 'delv_d',
                errorId: 'error-delv_d',
                validate: value => value !== '',
                message: 'Delivery date is required'
            },
            {
                id: 'delv_t',
                errorId: 'error-time',
                validate: value => value !== '',
                message: 'Delivery time is required'
            },
            {
                id: 'same-address',
                errorId: 'error-conditions',
                validate: () => document.getElementById('same-address').checked,
                message: 'You must agree to the terms and conditions'
            }
        ];

        // Validate each field
        fields.forEach(f => {
            const input = document.getElementById(f.id);
            const errorElement = document.getElementById(f.errorId);
            if (input && errorElement) {
                let value = input.type === 'checkbox' ? input.checked : input.value;
                let valid = f.validate(value);
                if (!valid) {
                    errorElement.textContent = f.message;
                    errorElement.classList.add('active');
                    input.classList.add('error');
                    isValid = false;
                } else {
                    errorElement.textContent = '';
                    errorElement.classList.remove('active');
                    input.classList.remove('error');
                }
            }
        });

        // Validate reason dropdown
        if (reasonHidden) {
            const reason = reasonHidden.value;
            const reasonError = document.getElementById('reason-error');
            if (reasonError) {
                if (!reason) {
                    reasonError.style.display = 'block';
                    isValid = false;
                } else {
                    reasonError.style.display = 'none';
                }
            }
        }

        // Validate payment method
        const paymentMethod = document.querySelector('input[name="payment_method"]:checked');
        const paymentError = document.getElementById('error-payment_method');
        if (paymentError) {
            if (!paymentMethod) {
                paymentError.textContent = 'Please select a payment method';
                paymentError.classList.add('active');
                isValid = false;
            } else {
                paymentError.textContent = '';
                paymentError.classList.remove('active');
            }
        }

        // Validate password if create account is checked
        const createAccount = document.getElementById('create_account');
        if (createAccount && createAccount.checked) {
            const password = document.getElementById('password');
            const passwordError = document.getElementById('error-password');
            if (password && passwordError) {
                if (password.value.length < 8) {
                    passwordError.textContent = 'Password must be at least 8 characters long';
                    passwordError.classList.add('active');
                    password.classList.add('error');
                    isValid = false;
                } else {
                    passwordError.textContent = '';
                    passwordError.classList.remove('active');
                    password.classList.remove('error');
                }
            }
        } else {
            const passwordError = document.getElementById('error-password');
            const password = document.getElementById('password');
            if (passwordError) {
                passwordError.textContent = '';
                passwordError.classList.remove('active');
            }
            if (password) {
                password.classList.remove('error');
            }
        }

        return isValid;
    }

    // Wait for DOM and jQuery to be ready
    if (typeof jQuery !== 'undefined') {
        jQuery(document).ready(function() {
            // Initialize with default date
            const defaultDate = jQuery('#delv_d').val();
            if (defaultDate) {
                updateTimeSlots(defaultDate);
            }

            // Real-time validation for all fields
            [
                'checkout-first-name-1',
                'address',
                'checkout-email-1',
                'checkout-phone-1',
                'delv_d',
                'delv_t'
            ].forEach(id => {
                jQuery(`#${id}`).on('input change', function() {
                    validateForm();
                });
            });

            jQuery('#same-address').on('change', function() {
                validateForm();
            });

            jQuery('input[name="payment_method"]').on('change', function() {
                validateForm();
            });

            jQuery('#password').on('input', function() {
                validateForm();
            });

            jQuery('#delv_d').on('change', function() {
                updateTimeSlots(this.value);
                validateForm();
            });

            // Show/hide password field
            const createAccountCheckbox = document.getElementById('create_account');
            if (createAccountCheckbox) {
                createAccountCheckbox.addEventListener('change', function() {
                    var passwordField = document.getElementById('password-field');
                    if (passwordField) {
                        passwordField.style.display = this.checked ? 'block' : 'none';
                    }
                    validateForm();
                });
            }

            // Unified form submit
            jQuery('#checkout-form').on('submit', function(e) {
                if (!validateForm()) {
                    e.preventDefault();
                    e.stopImmediatePropagation();
                    return false;
                }
            });
        });
    } else {
        // Fallback for when jQuery is not available
        // Real-time validation setup with vanilla JS
        [
            'checkout-first-name-1',
            'address',
            'checkout-email-1',
            'checkout-phone-1',
            'delv_d',
            'delv_t'
        ].forEach(id => {
            const element = document.getElementById(id);
            if (element) {
                element.addEventListener('input', validateForm);
                element.addEventListener('change', validateForm);
            }
        });

        const sameAddressCheckbox = document.getElementById('same-address');
        if (sameAddressCheckbox) {
            sameAddressCheckbox.addEventListener('change', validateForm);
        }

        const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
        paymentMethods.forEach(radio => {
            radio.addEventListener('change', validateForm);
        });

        const passwordInput = document.getElementById('password');
        if (passwordInput) {
            passwordInput.addEventListener('input', validateForm);
        }

        // Show/hide password field
        const createAccountCheckbox = document.getElementById('create_account');
        if (createAccountCheckbox) {
            createAccountCheckbox.addEventListener('change', function() {
                const passwordField = document.getElementById('password-field');
                if (passwordField) {
                    passwordField.style.display = this.checked ? 'block' : 'none';
                }
                validateForm();
            });
        }

        // Form submit validation
        const checkoutForm = document.getElementById('checkout-form');
        if (checkoutForm) {
            checkoutForm.addEventListener('submit', function(e) {
                if (!validateForm()) {
                    e.preventDefault();
                    e.stopImmediatePropagation();
                    return false;
                }
            });
        }
    }
});
