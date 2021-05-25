// Load the Visualization API and the linechart package.
google.charts.load('current', {'packages':['corechart']});
      
// Set a callback to run when the Google Visualization API is loaded.
google.charts.setOnLoadCallback(drawVoti);
google.charts.setOnLoadCallback(drawCfu);
google.charts.setOnLoadCallback(drawMediaP);
google.charts.setOnLoadCallback(drawMediaA);
  
function drawVoti() {


  $.ajax({
    url: "getStats.php?voti_esami=true",
    dataType: "json",
    async: true,
    success: function(msg){
      // Create our data table out of JSON data loaded from server.
      var data = new google.visualization.DataTable(msg);

      // Instantiate and draw our chart, passing in some options.
      var chart = new google.visualization.LineChart(document.getElementById('voti-box'));
      
      var options={
        legend: { position: 'bottom' }
      };
      
      chart.draw(data, options);
      },
      error: function(msg){
        alert("errore");
      }

  });
      

}

function drawCfu() {


  $.ajax({
    url: "getStats.php?cfu=true",
    dataType: "json",
    async: true,
    success: function(msg){
      // Create our data table out of JSON data loaded from server.
      var data = new google.visualization.DataTable(msg);

      // Instantiate and draw our chart, passing in some options.
      var chart = new google.visualization.PieChart(document.getElementById('cfu-box'));
      
      var options={
        is3D:true
      };
      
      chart.draw(data, options);
      },
      error: function(msg){
        alert("errore");
      }

  });
      

}

function drawMediaA() {


  $.ajax({
    url: "getStats.php?media=true",
    dataType: "json",
    async: true,
    success: function(msg){
      // Create our data table out of JSON data loaded from server.
      var data = new google.visualization.DataTable(msg);

      // Instantiate and draw our chart, passing in some options.
      var chart = new google.visualization.LineChart(document.getElementById('media-aritmetica-box'));
      
      var options={
        legend: { position: 'bottom' }
      };
      
      chart.draw(data, options);
      },
      error: function(msg){
        alert("errore");
      }

  });


}

function drawMediaP() {

  $.ajax({
    url: "getStats.php?pesata=true",
    dataType: "json",
    async: true,
    success: function(msg){
      // Create our data table out of JSON data loaded from server.
      var data = new google.visualization.DataTable(msg);

      // Instantiate and draw our chart, passing in some options.
      var chart = new google.visualization.LineChart(document.getElementById('media-pesata-box'));
      
      var options={
        legend: { position: 'bottom' }
      };
      
      chart.draw(data, options);
      },
      error: function(msg){
        alert("errore");
      }

  });

}

