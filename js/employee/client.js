//let clients = [];
let deleteIndex = null;

function setPrice(){
    let projectselect = document.getElementById("project");
    let priceInput = document.getElementById("price");
    let selectedOption = projectselect.options[projectselect.selectedIndex];
    let price = selectedOption.getAttribute("data-price");
    priceInput.value = price ? price : "";
}

function openModal(index = null) {
   document.getElementById("formModal").style.display = "block";
     if (index !== null) {
        document.getElementById("modalTitle").innerText =
             "Edit Client Details";
        document.getElementById("name").value = clients[index].name;
        document.getElementById("contact").value = clients[index].contact;
        document.getElementById("email").value = clients[index].email;
        document.getElementById("project").value = clients[index].project;
        document.getElementById("price").value = clients[index].price;
        document.getElementById("editIndex").value = index;
     }
    }

      function openAddModal(){
        document.getElementById("clientForm").reset();
        document.getElementById("name").value = "";
        document.getElementById("contact").value = "";
        document.getElementById("email").value = "";
        document.getElementById("formModal").style.display = "block";
        document.getElementById("saveBtn").innerText = "Update";
    
        document.getElementById("modalTitle").innerText = "Add New Client";
        
        document.getElementById("editIndex").value = "";
        document.getElementById("saveBtn").innerText = "Save";

        document.getElementById("error-contact").innerHTML = "";
        document.getElementById("error-email").innerHTML = "";
    }


function closeModal() {
    document.getElementById("formModal").style.display = "none";
    document.getElementById("clientForm").reset();
    window.history.replaceState({}, document.title, "clients.php");
    if(window.location.search.includes('edit')){
        window.location.href = 'clients.php';
    }
}

/*document.getElementById("clientForm").onsubmit = function (e) {
    e.preventDefault();
    const clientData = {
        name: document.getElementById("name").value,
        contact: document.getElementById("contact").value,
        email: document.getElementById("email").value,
        project: document.getElementById("project").value,
        price: document.getElementById("price").value,
    };

    const editIndex = document.getElementById("editIndex").value;
    if (editIndex !== "") {
        clients[editIndex] = clientData;
    } else {
        clients.push(clientData);
    }
    renderTable();
    closeModal();
};

function renderTable() {
    const tbody = document.getElementById("tableBody");
    tbody.innerHTML = "";
    clients.forEach((client, index) => {
        tbody.innerHTML += `
                <tr>
                    <td>${client.name}</td>
                    <td>${client.contact}</td>
                    <td>${client.email}</td>
                    <td>${client.project}</td>
                    <td>₹${client.price}</td>
                    <td>
                        <button class="edit-btn" onclick="openModal(${index})">Edit</button>
                        <button class="delete-btn" onclick="askDelete(${index})">Delete</button>
                    </td>
                </tr>`;
    });
}*/

function askDelete(id) {
    deleteIndex = id;
    document.getElementById("deleteModal").style.display = "block";
    document.getElementById("confirmDeleteBtn").onclick = function(){
        window.location.href = "?delete=" + id;
    }
}

function closeDeleteModal() {
    document.getElementById("deleteModal").style.display = "none";
}

/*document.getElementById("confirmDeleteBtn").onclick = function () {
    clients.splice(deleteIndex, 1);
    renderTable();
    closeDeleteModal();
};*/