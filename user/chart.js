// Load the Visualization API and the linechart package.
google.charts.load('current', {'packages':['corechart']});
      
// Set a callback to run when the Google Visualization API is loaded.
google.charts.setOnLoadCallback(drawChart);
google.charts.setOnLoadCallback(drawChart2);
google.charts.setOnLoadCallback(drawChart3);
  
function drawChart() {
  var jsonData = $.ajax({
      url: "getData.php",
      dataType: "json",
      async: false
      }).responseText;
      
  // Create our data table out of JSON data loaded from server.
  var data = new google.visualization.DataTable(jsonData);

  // Instantiate and draw our chart, passing in some options.
  var chart = new google.visualization.LineChart(document.getElementById('chart-box'));
  
  var options={
    title: 'Voti',
    legend: { position: 'bottom' }
    
  };
  
  chart.draw(data, options);
}

function drawChart2() {
  var jsonData = $.ajax({
      url: "getData2.php",
      dataType: "json",
      async: false
      }).responseText;
      
  // Create our data table out of JSON data loaded from server.
  var data = new google.visualization.DataTable(jsonData);

  // Instantiate and draw our chart, passing in some options.
  var chart = new google.visualization.PieChart(document.getElementById('cfu-box'));
  
  var options={
    title: 'Cfu',
    is3D:true
  };

  chart.draw(data, options);
}

function drawChart3() {
  var jsonData = $.ajax({
      url: "getData3.php",
      dataType: "json",
      async: false
      }).responseText;
      
  // Create our data table out of JSON data loaded from server.
  var data = new google.visualization.DataTable(jsonData);

  // Instantiate and draw our chart, passing in some options.
  var chart = new google.visualization.LineChart(document.getElementById('num-box'));
  
  var options={
    title: 'Media',
    legend: { position: 'bottom' }
  };

  chart.draw(data, options);
}


