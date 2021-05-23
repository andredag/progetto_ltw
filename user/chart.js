// Load the Visualization API and the linechart package.
google.charts.load('current', {'packages':['corechart']});
      
// Set a callback to run when the Google Visualization API is loaded.
google.charts.setOnLoadCallback(drawChart);
google.charts.setOnLoadCallback(drawChart2);
google.charts.setOnLoadCallback(drawChart3);
google.charts.setOnLoadCallback(drawChart4);
  
function drawChart() {
  var jsonData = $.ajax({
      url: "getStats.php?voti_esami=true",
      dataType: "json",
      async: false
      }).responseText;
      
  // Create our data table out of JSON data loaded from server.
  var data = new google.visualization.DataTable(jsonData);

  // Instantiate and draw our chart, passing in some options.
  var chart = new google.visualization.LineChart(document.getElementById('voti-box'));
  
  var options={
    legend: { position: 'bottom' }
    
  };
  
  chart.draw(data, options);
}

function drawChart2() {
  var jsonData = $.ajax({
      url: "getStats.php?cfu=true",
      dataType: "json",
      async: false
      }).responseText;
      
  // Create our data table out of JSON data loaded from server.
  var data = new google.visualization.DataTable(jsonData);

  // Instantiate and draw our chart, passing in some options.
  var chart = new google.visualization.PieChart(document.getElementById('cfu-box'));
  
  var options={
    is3D:true
  };

  chart.draw(data, options);
}

function drawChart3() {
  var jsonData = $.ajax({
      url: "getStats.php?media=true",
      dataType: "json",
      async: false
      }).responseText;
      
  // Create our data table out of JSON data loaded from server.
  var data = new google.visualization.DataTable(jsonData);

  // Instantiate and draw our chart, passing in some options.
  var chart = new google.visualization.LineChart(document.getElementById('media-aritmetica-box'));
  
  var options={
    legend: { position: 'bottom' }
  };

  chart.draw(data, options);
}

function drawChart4() {
  var jsonData = $.ajax({
      url: "getStats.php?pesata=true",
      dataType: "json",
      async: false
      }).responseText;
      
  // Create our data table out of JSON data loaded from server.
  var data = new google.visualization.DataTable(jsonData);

  // Instantiate and draw our chart, passing in some options.
  var chart = new google.visualization.LineChart(document.getElementById('media-pesata-box'));
  
  var options={
    legend: { position: 'bottom' }
  };

  chart.draw(data, options);
}

