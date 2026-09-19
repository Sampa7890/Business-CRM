let currentRow = null;

/* =========================
   OPEN ADD MODAL
========================= */

function openProjectModal() {

    document
        .getElementById("sale_form_action")
        .setAttribute("name", "addSale");

    document.getElementById("sale_edit_id").value = "";

    document.getElementById("projectForm").reset();
    $('#projectAssigned').prop('selectedIndex', 0);

    document
        .getElementById("projectModal")
        .style.display = "block";

    currentRow = null;
}

/* =========================
   CLOSE PROJECT MODAL
========================= */

function closeProjectModal() {

    document
        .getElementById("projectModal")
        .style.display = "none";
    document.getElementById("projectForm").reset();
    $('#projectAssigned').prop('selectedIndex', 0);


}

/* =========================
   EDIT PROJECT
========================= */

function editProject(button) {

    currentRow = button.closest("tr");

    // PROJECT NAME
    document.getElementById("projectName").value =
        currentRow.children[0].innerText.trim();

    // DESCRIPTION
    document.getElementById("projectDescription").value =
        currentRow.children[1].innerText.trim();

    // PRICE
    document.getElementById("projectPrice").value =
        currentRow.querySelector(".price").innerText.trim();

    // DEADLINE
    document.getElementById("projectDeadline").value =
        currentRow.querySelector(".deadline")
            .getAttribute("data-deadline");

    // CHANGE FORM ACTION
    document
        .getElementById("sale_form_action")
        .setAttribute("name", "editSale");

    // SALE ID
    document.getElementById("sale_edit_id").value =
        currentRow.querySelector(".deadline")
            .getAttribute("data-id");

    // STATUS
    document.getElementById("projectStatus").value =
        button.getAttribute("data-status");

    var emp_id = button.getAttribute("data-emp_id");
    var employees = button.getAttribute("data-employees");
    employees = JSON.parse(employees);

    $('#projectAssigned').html(
        '<option value="">Select Employee</option>'
    );

    employees.forEach(employee => {

        let selected =
            employee.id == emp_id ? 'selected' : '';

        $('#projectAssigned').append(`
    
            <option
    
                value="${employee.id}"
    
                data-jobrole="${employee.jobrole}"
    
                ${selected}>
    
                ${employee.name} (EMP${employee.id} - ${employee.jobrole})
    
            </option>
    
        `);

    });

    // showEmployeeDetails();


    // OPEN MODAL
    document
        .getElementById("projectModal")
        .style.display = "block";
}

// ========================= Show Employee Details =========================//

// function showEmployeeDetails() {

//     let select =
//         document.getElementById('projectAssigned');

//     let selectedOption =
//         select.options[select.selectedIndex];

//     let employeeId =
//         select.value;

//     let jobrole =
//         selectedOption.getAttribute('data-jobrole');

//     document.getElementById('employeeIdText').innerText =
//         employeeId ? 'EMP' + employeeId : '-';

//     document.getElementById('employeeRoleText').innerText =
//         jobrole ? jobrole : '-';
// }

/* =========================
   DELETE PROJECT
========================= */

function deleteProject(button) {

    let row = button.closest("tr");

    document.getElementById("delete_sale_id").value =
        row.querySelector(".deadline")
            .getAttribute("data-id");

    document
        .getElementById("deleteModal")
        .style.display = "block";
}

/* =========================
   CLOSE DELETE MODAL
========================= */

function closeDeleteModal() {

    document
        .getElementById("deleteModal")
        .style.display = "none";
}

/* =========================
   CLOSE MODAL OUTSIDE CLICK
========================= */

window.onclick = function (event) {

    const projectModal =
        document.getElementById("projectModal");

    const deleteModal =
        document.getElementById("deleteModal");

    if (event.target === projectModal) {
        closeProjectModal();
    }

    if (event.target === deleteModal) {
        closeDeleteModal();
    }
}