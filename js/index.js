
function companyN(){
    var box = document.getElementById("dropdown");
    if(box.style.display === "none"){
          box.style.display = "block";
    }
    else {box.style.display = "none";}
       }

  function newcompany(){
    document.getElementById("form").style.display = "block";
    document.getElementById("dropdown").style.display = "none";
       }
  function Comesave(){
     var companyname = document.getElementById("companyname").value.trim();
     var errorspan = document.getElementById("errormsg");
   
     if(companyname === ""){
       errorspan.innerText = "Please enter company name";
       errorspan.style.display = "inline";
       return false;
      }
     else {
       errorspan.style.display = "none"; 
     }
  
    var company = document.getElementById("companylists");

    if(company && company.innerText.includes(companyname)){
      errorspan.innerText = "Company already exists";
      errorspan.style.display = "inline";
      return false;
    }
    return true;
  }
  function cancelform(){
    document.getElementById("form").style.display = "none";
    document.getElementById("errormsg").style.display = "none";
    document.getElementById("companyname").value = "";
  }
/* DELETE COMPANY*/
var deleteID = "";
var deleteName = "";

function deletecompany(id,name){
  deleteID = id;
  deleteName = name;

  document.getElementById("deletepop").style.display = "block";
  document.getElementById("poptext").innerHTML = 
        "Are you sure, you want to delete <b>" + name + "</b> company ?";
}
function conpop(){
  document.getElementById("deletepop").style.display = "none";
  document.getElementById("confirmdel").style.display = "block";
  document.getElementById("confirmtext").innerHTML = 
        "Confirm to delete <b>" + deleteName + "</b> company";
}
function context(){
  window.location.href = "?del=" + deleteID;
}
function cancelpop(){
  document.getElementById("deletepop").style.display = "none";
  document.getElementById("confirmdel").style.display = "none";
}
  
 
