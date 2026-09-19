document.addEventListener("DOMContentLoaded", function () {
    const loginForm = document.getElementById("loginForm");
    const loginEmail = document.getElementById("email");
    const loginPassword = document.getElementById("password");
    const loginEye = document.getElementById("eye_icon");

    if (loginForm) {
        loginForm.addEventListener("submit", function (e) {
            e.preventDefault();

            let isValid = true;

            if (!loginEmail.checkValidity()) {
                loginEmail.classList.add("is-invalid");
                loginEmail.classList.remove("is-valid");
                isValid = false;
            } else {
                loginEmail.classList.remove("is-invalid");
                loginEmail.classList.add("is-valid");
            }

            if (!loginPassword.checkValidity()) {
                loginPassword.classList.add("is-invalid");
                loginEye.classList.add("d-none");
                isValid = false;
            } else {
                loginPassword.classList.remove("is-invalid");
                loginEye.classList.remove("d-none");
            }

            if (isValid) {
                loginForm.submit();
            }
        });

        loginEmail.addEventListener("input", () => {
            loginEmail.classList.remove("is-invalid");
        });

        loginPassword.addEventListener("input", () => {
            loginPassword.classList.remove("is-invalid");
            loginEye.classList.remove("d-none");
        });

        window.togglePassword = function () {
            if (loginPassword.type === "password") {
                loginPassword.type = "text";
                loginEye.querySelector("svg").classList.replace("fa-eye", "fa-eye-slash");
            } else {
                loginPassword.type = "password";
                loginEye.querySelector("svg").classList.replace("fa-eye-slash", "fa-eye");
            }
        };
    }

    const signupForm = document.getElementById("signupForm");

    if (signupForm) {
        const name = document.getElementById("name");
        const phone = document.getElementById("phone");
        const email = document.getElementById("email");
        const password = document.getElementById("password");
        const confirmPassword = document.getElementById("confirmPassword");
        const eye = document.getElementById("eye_icon");
        const eyeConfirm = document.getElementById("eye_icon_confirm");

        signupForm.addEventListener("submit", function (e) {
            e.preventDefault();

            let isValid = true;

            if (phone.value.length > 0) {
                const phoneRegex = /^[0-9]{10,15}$/;
                if (!phoneRegex.test(phone.value.trim())) {
                    phone.classList.add("is-invalid");
                    isValid = false;
                } else {
                    phone.classList.remove("is-invalid");
                    phone.classList.add("is-valid");
                }
            }

            if (!email.checkValidity()) {
                email.classList.add("is-invalid");
                isValid = false;
            } else {
                email.classList.remove("is-invalid");
                email.classList.add("is-valid");
            }

            if (password.value.length < 6) {
                password.classList.add("is-invalid");
                eye.classList.add("d-none");
                isValid = false;
            } else {
                password.classList.remove("is-invalid");
                eye.classList.remove("d-none");
            }

            if (confirmPassword.value !== password.value || confirmPassword.value === "") {
                confirmPassword.classList.add("is-invalid");
                eyeConfirm.classList.add("d-none");
                isValid = false;
            } else {
                confirmPassword.classList.remove("is-invalid");
                eyeConfirm.classList.remove("d-none");
            }

            if (isValid) {
                signupForm.submit();
            }
        });

        email.addEventListener("input", () => {
            email.classList.remove("is-invalid");
        });

        password.addEventListener("input", () => {
            password.classList.remove("is-invalid");
            eye.classList.remove("d-none");
        });

        confirmPassword.addEventListener("input", () => {
            confirmPassword.classList.remove("is-invalid");
            eyeConfirm.classList.remove("d-none");
        });

        window.togglePassword = function () {
            if (password.type === "password") {
                password.type = "text";
                eye.querySelector("svg").classList.replace("fa-eye", "fa-eye-slash");
            } else {
                password.type = "password";
                eye.querySelector("svg").classList.replace("fa-eye-slash", "fa-eye");
            }
        };

        window.toggleConfirmPassword = function () {
            if (confirmPassword.type === "password") {
                confirmPassword.type = "text";
                eyeConfirm.querySelector("svg").classList.replace("fa-eye", "fa-eye-slash");
            } else {
                confirmPassword.type = "password";
                eyeConfirm.querySelector("svg").classList.replace("fa-eye-slash", "fa-eye");
            }
        };
    }
});