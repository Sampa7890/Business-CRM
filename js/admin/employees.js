var editRows = null;
var deleteRow = null;

function openPop() {
    document.getElementById("Popup").style.display = "flex";
    document.getElementById("popupTitle").innerText = "Add Employee";
    document.getElementById("saveBTN").innerText = "Save";
    editRows = null;
    document.getElementById("name").value = "";
    document.getElementById("email").value = "";
    document.getElementById("contact").value = "";
    document.getElementById("salary").value = "";
    document.getElementById("joindate").value = "";
    document.getElementById("account").value = "";
    document.getElementById("jobrole").value = "";
    document.getElementById("form_action").setAttribute("name", "addEmployee");
}

function closePop() {
    document.getElementById("Popup").style.display = "none";
}

function saveEmployee() {
    var name = document.getElementById("name").value;
    var email = document.getElementById("email").value;
    var contact = document.getElementById("contact").value;
    var salary = document.getElementById("salary").value;
    var date = document.getElementById("joindate").value;
    var bank = document.getElementById("account").value;
    var jobrole = document.getElementById("jobrole").value;

    if (name == "" ||
        email == "" ||
        salary == "" ||
        date == "" ||
        bank == "" ||
        jobrole == "") {
        alert("Please fill all fields");
        return false;
    }

    var table = document.getElementById("employeeTable");
    var rows = table.querySelectorAll("tbody tr");

    for (var i = 0; i < rows.length; i++) {
        if (rows[i] != editRows) {
            var existEmail = rows[i].cells[1].innerText;

            if (existEmail == email) {
                document.getElementById("emailmsg").innerText = "Email already exists!";

                document.getElementById("emailmsg").style.display = "inline";
                return false;
            }
        }
    }
    document.getElementById("emailmsg").style.display = "none";

    if (!email.includes("@") ||
        !email.includes(".")) {
        document.getElementById("emailmsg").innerText = "Invalid Email!";
        document.getElementById("emailmsg").style.display = "inline";
        return false;
    } else {
        document.getElementById("emailmsg").style.display = "none";
    }

    return true;
}
function closelog() { document.getElementById("logindel").style.display = "none"; }

function editButt(button) {
    editRows = button.parentElement.parentElement;
    document.getElementById("Popup").style.display = "flex";
    document.getElementById("popupTitle").innerText = "Edit Employee Page";
    document.getElementById("saveBTN").innerText = "Save Changes";

    document.getElementById("name").value = editRows.cells[0].innerText;
    document.getElementById("email").value = editRows.cells[1].innerText;
    document.getElementById("contact").value = editRows.cells[2].innerText;
    document.getElementById("salary").value = editRows.dataset.salary;
    document.getElementById("joindate").value = editRows.cells[4].innerText;
    document.getElementById("jobrole").value = editRows.cells[5].innerText;
    document.getElementById("account").value = editRows.getAttribute("data-bank");
    document.getElementById("form_action").setAttribute("name", "editEmployee");
    document.getElementById("form_action").value = editRows.getAttribute("data-id");
}

function deleteButt(button) {
    deleteRow = button.parentElement.parentElement;
    document.getElementById("delpop").style.display = "flex";
    deleteId = button.getAttribute("data-id");
    document.getElementById("deleteEmployee").value = deleteId;
}

function confirmdelete() {
    deleteRow.remove();
    document.getElementById("delpop").style.display = "none";
}

function closedelete() {
    document.getElementById("delpop").style.display = "none";
}
/* for only read modal */

function onlyread(button) {
    var emp_email = button.getAttribute("data-emp_email");
    var emp_name = button.getAttribute("data-emp_name");
    var clients = button.getAttribute("data-clients");
    clients = JSON.parse(clients);

    document.getElementById("emp_details").innerText = `${emp_name} (${emp_email})`;
    document.getElementById("viewModal").style.display = "flex";
     $('#sales_activity_tbody').html(`
        <tr class="bg-primary text-light text-center">
            <th class="p-2">Date</th>
            <th>Client ID</th>
            <th>Client Name</th>
            <th>Project Name</th>
            <th>Project Price</th>
        </tr>
    `);
    // Example data
    clients.forEach(client => {
        const dateOnly = client.created_at.split(" ")[0];
        $('#sales_activity_tbody').append(`<tr class="bg-light text-center">
                        <td>${dateOnly}</td>
                        <td>${client.id}</td>
                        <td>${client.client_name}</td>
                        <td>${client.project}</td>
                        <td>₹${client.price}</td>
                    </tr>`
        )
    });
}

function closeViewModal() {

    document.getElementById("viewModal").style.display = "none";
}

/* for copying password to clipboard */

function copyPassword() {

    const password =
        document.getElementById("password").innerText;

    navigator.clipboard.writeText(password);

    alert("Password copied to clipboard");
}

/* for closing modal when clicking outside of it */

window.onclick = function (event) {

    const popup = document.getElementById("Popup");
    const delpop = document.getElementById("delpop");
    const logindel = document.getElementById("logindel");

    if (event.target == popup) {
        closePop();
    } else if (event.target == delpop) {
        closedelete();
    }
    else if (event.target == logindel) {
        closelog();
    }
}

/* for closing modal when pressing escape key */

document.addEventListener("keydown", function (event) {

    if (event.key === "Escape") {

        closePop();

        closedelete();

        document.getElementById("logindel").style.display = "none";
    }
});