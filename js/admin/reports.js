document.getElementById("emp_search").onkeyup = function(){
    var text = this.value.toLowerCase();
    var rows = document.querySelectorAll('#emp_table_data tbody tr');
    for(var e = 0; e < rows.length; e++){
        rows[e].style.display = rows[e].innerText.toLowerCase().includes(text) ? '' : 'none';
    }
};

document.getElementById("project_search").onkeyup = function(){
    var text = this.value.toLowerCase();
    var rows = document.querySelectorAll('#project_table_data tbody tr');
    for(var p = 0; p < rows.length; p++){
        rows[p].style.display = rows[p].innerText.toLowerCase().includes(text) ? '' : 'none';
    }
};