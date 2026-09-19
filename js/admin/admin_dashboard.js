
var months = ["JAN","FEB","MAR","APR","MAY","JUN","JUL","AUG","SEP","OCT","NOV","DEC"];

var graph = document.getElementById("drawing");
new Chart(graph, {
    type: 'line',
    data: {
        labels: months,
        datasets:[{
            label:"Monthly Sales",
            data:monthlysale,
            borderColor:"blue",
            backgroundColor:"lightblue"
        }]
        
    },options:{
        plugins:{
            legend:{
                labels:{
                    color: "black"
                }
            }
        },  responsive: true,
        scales: {
            x: {
                ticks:{
                    color: "black"
                }
            },
            y: {
                beginAtZero: true,
                ticks: {
                    color: "black",
                    stepSize: 10
                }
                }
            }
        }
    });

var bargraph = document.getElementById("bar-chart");
new Chart(bargraph, {
    type: 'bar',
    data: {
        labels: empname,
        datasets:[{
            label:"Employees Monthly Sale",
            data: empsale,
            borderColor:"green",
            backgroundColor:"lightyellow",
            borderWidth: 1
        }]
        
    },options:{
        responsive: true,
        plugins:{
            legend:{
                labels:{
                    color:"black"
                }
            }
        },
        scales:{
            x:{
                ticks:{
                    color: "black",
                    font:{
                        weight: "bold"
                }
            }
        },
        y:{
            beginAtZero: true,
            ticks:{
                color: "black"
            }
        }
    }
}
});

var Employees = [];
for (var b = 0; b < empname.length; b++){
    Employees.push({
        name : empname[b],
        sale : empsale[b]
    });
}
 Employees.sort ((a,b)=> b.sale - a.sale);
 var topsale = document.getElementById("topemp");
 Employees.slice(0,5).forEach((Employees,index)=>{
      topsale.innerHTML +=
      "<li><div class='list'>" +
      (index + 1) +
      "</div><div class='toplist'><h5>" +
      Employees.name +
      "</h5> ( <p>" +
      Employees.sale +
      " - sales</p> ) </div></li>";
 })


projects.sort((a,b)=> b.users - a.users);
var poplist = document.getElementById("pro-list");
projects.forEach((project,index)=>{
    poplist.innerHTML +=
      "<li> <div class= 'range'>" +
      (index + 1) +
      "</div><div class='details'><h5>" +
      project.name +
      "</h5><p>" +
      project.users +
      " - Active users</p></div></li>";
})

   