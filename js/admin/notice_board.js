document.addEventListener("DOMContentLoaded", function () {

    const addModal = document.getElementById("addModal");
    const modalTitle = document.getElementById("modalTitle");
    const noticeIdInput = document.getElementById("noticeIdInput");
    const noticeTitleInput = document.getElementById("noticeTitleInput");
    const noticeDescInput = document.getElementById("noticeDescInput");
    const addNoticeBtn = document.getElementById("addNoticeBtn");

    if (addNoticeBtn) {
        addNoticeBtn.addEventListener("click", function () {
            modalTitle.innerText = "Add New Notice";
            noticeIdInput.value = "";
            noticeTitleInput.value = "";
            noticeDescInput.value = "";
            addModal.style.display = "flex";
        });
    }

    window.openEditModal = function(button) {
        modalTitle.innerText = "Edit Notice";
        noticeIdInput.value = button.dataset.id;
        noticeTitleInput.value = button.dataset.title;
        noticeDescInput.value = button.dataset.description;
        addModal.style.display = "flex";
    };

    window.openSeenModal = function(id) {
        document.getElementById(id).style.display = "flex";
    };

    window.closeModal = function(id) {
        document.getElementById(id).style.display = "none";
    };

    window.addEventListener("click", function(event) {
        if (event.target.classList.contains("modal")) {
            event.target.style.display = "none";
        }
    });

});