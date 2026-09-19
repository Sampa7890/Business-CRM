function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}
function isFormValid() {
    const name = document.getElementById('mainName').value;
    const phone = document.getElementById('mainPhone').value;
    const email = document.getElementById('mainEmail').value;

    if (name === "" || phone === "" || email === "") {
        alert("Fill all fields!");
        return false;
    }
    if (!validateEmail(email)) {
        alert("Enter a valid email format (e.g., name@mail.com)!");
        return false;
    }
    return true;
}

function handleSave() {
    if (isFormValid()) {
        document.getElementById('profileForm').submit();
    }
}

function verifyEmail() {
    const email = document.getElementById('mainEmail').value;
    if (validateEmail(email)) {
        alert("Email format is valid!");
    } else {
        alert("Enter a valid email format (e.g., name@mail.com)!");
    }
}

function openModal() {
    if (isFormValid()) {
        document.getElementById('modalName').value = document.getElementById('mainName').value;
        document.getElementById('modalPhone').value = document.getElementById('mainPhone').value;
        document.getElementById('modalDob').value = document.getElementById('mainDob').value;
        document.getElementById('modalDesig').value = document.getElementById('mainDesig').value;
        document.getElementById('modalOverlay').classList.add('active');
    }
}

function closeModal() {
    document.getElementById('modalOverlay').classList.remove('active');
}

function saveModalData() {
    document.getElementById('mainName').value = document.getElementById('modalName').value;
    document.getElementById('mainPhone').value = document.getElementById('modalPhone').value;
    document.getElementById('mainDob').value = document.getElementById('modalDob').value;
    document.getElementById('mainDesig').value = document.getElementById('modalDesig').value;
    closeModal();
    handleSave();
}

function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            const display = document.getElementById('profileDisplay');
            display.style.backgroundImage = "url('" + e.target.result + "')";
            display.style.backgroundSize = "cover";
            display.innerHTML = "";
        }
        reader.readAsDataURL(input.files[0]);
    }
}
