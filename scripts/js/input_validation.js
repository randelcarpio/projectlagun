var firstName = document.getElementById('firstName');
var lastName = document.getElementById('lastName');
var phoneNumber = document.getElementById('phoneNumber');
var email = document.getElementById('email');
var termsCheckbox = document.getElementById('terms');
var submitButton = document.querySelector('button[type="submit"]');

function sanitizeInput(input) {
    var temp = document.createElement('div');
    temp.textContent = input;
    return temp.innerHTML;
}

function validateEmail() {
    var pattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;
    if (email.value.match(pattern)) {
        email.setCustomValidity('');
    } else {
        email.setCustomValidity('Please enter a valid email address');
    }
}

function validatePhoneNumber() {
    var pattern = /^(\+63|0)[0-9]{10}$/;
    if (phoneNumber.value.match(pattern)) {
        phoneNumber.setCustomValidity('');
    } else {
        phoneNumber.setCustomValidity('Please enter a valid Philippine phone number');
    }
}

function validateRecaptcha() {
    grecaptcha.ready(function() {
        grecaptcha.execute('6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI', {action: 'submit'}).then(function(token) {
            document.getElementById('recaptchaResponse').value = token;
        });
    });
}

function checkTerms() {
    if (termsCheckbox.checked) {
        termsCheckbox.setCustomValidity('');
    } else {
        termsCheckbox.setCustomValidity('You must agree to the terms and conditions');
    }
}

function checkAllFields() {
    if (firstName.value && lastName.value && phoneNumber.value && email.value && termsCheckbox.checked) {
        if (firstName.checkValidity() && lastName.checkValidity() && phoneNumber.checkValidity() && email.checkValidity()) {
            submitButton.disabled = false;
        } else {
            submitButton.disabled = true;
        }
    } else {
        submitButton.disabled = true;
    }
}


firstName.addEventListener('input', function (e) {
    firstName.value = sanitizeInput(firstName.value);
    checkAllFields();
});

lastName.addEventListener('input', function (e) {
    lastName.value = sanitizeInput(lastName.value);
    checkAllFields();
});

email.addEventListener('input', function (e) {
    email.value = sanitizeInput(email.value);
    validateEmail();
    checkAllFields();
});

phoneNumber.addEventListener('keypress', function (e) {
    var key = e.which || e.keyCode;
    if (!(key >= 48 && key <= 57)) { // check if it's not a number (0-9)
        e.preventDefault();
    }
});

phoneNumber.addEventListener('input', function (e) {
    phoneNumber.value = sanitizeInput(phoneNumber.value);
    validatePhoneNumber();
    checkAllFields();
});

termsCheckbox.addEventListener('change', function (e) {
    checkTerms();
    checkAllFields();
});

// Call validateRecaptcha separately when the form is submitted
document.querySelector('form').addEventListener('submit', function(e) {
    e.preventDefault();
    validateRecaptcha();
});
