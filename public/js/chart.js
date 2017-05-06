
function histChart(histData) {
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart (histData) {
        var data = new google.visualization.arrayToDataTable(
            <?php echo histData ?>
        , true);

        var options = {
          legend:'none'
        };
        var chart = new google.visualization.CandlestickChart(document.getElementById('candlechart'));
        chart.draw(data, options);
    }
}
