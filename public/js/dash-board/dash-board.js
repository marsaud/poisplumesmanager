function turnOverChart(containerId, layers, data) {
    var chart = new AmCharts.AmSerialChart();
    chart.dataProvider = data;
    chart.categoryField = "month";

    var colors = ["#ff0000", "#00ff00", "#0000ff", "#ffff00", "#ff00ff", "#0000ff"];
    var key;
    for (key = 0; key < layers.length; key++)
    {
        var graph = new AmCharts.AmGraph();
        graph.valueField = layers[key];
        graph.title = layers[key];
        graph.type = "line";
        graph.connect = false;
        graph.lineColor = colors[key % colors.length];
        graph.bullet = "round";
        graph.balloonText = "[[category]]: <b>[[value]]</b>";
        
        chart.addGraph(graph);
    }

    var categoryAxis = chart.categoryAxis;
    categoryAxis.autoGridCount = false;
    categoryAxis.gridCount = data.length;
    categoryAxis.gridPosition = "start";
    categoryAxis.labelRotation = 45;

    var legend = new AmCharts.AmLegend();
    legend.borderAlpha = 0.2;
    legend.horizontalGap = 10;
    legend.useGraphSettings = true;
    chart.addLegend(legend);

    chart.write(containerId);
}

function cateringChart(containerId, data) {
    // SERIAL CHART
    var chart = new AmCharts.AmSerialChart();
    chart.dataProvider = data;
    chart.categoryField = "day";
    // chart.plotAreaBorderAlpha = 0.2;

    // AXES
    // category
    var categoryAxis = chart.categoryAxis;
    // categoryAxis.gridAlpha = 0.1;
    // categoryAxis.axisAlpha = 0;
    categoryAxis.gridPosition = "start";

    // value
    var valueAxis = new AmCharts.ValueAxis();
    valueAxis.stackType = "regular";
    // valueAxis.gridAlpha = 0.1;
    // valueAxis.axisAlpha = 0;
    chart.addValueAxis(valueAxis);

    // GRAPHS
    // first graph    
    var graph1 = new AmCharts.AmGraph();
    graph1.title = "Midi - Boissons ";
    graph1.labelText = "[[value]]";
    graph1.valueField = "drink_lunch";
    graph1.type = "column";
    graph1.lineAlpha = 0;
    graph1.fillAlphas = 1;
    graph1.lineColor = "#bbffbb";
    graph1.balloonText = "[[category]] : [[title]] [[value]]";
    chart.addGraph(graph1);

    // second graph              
    var graph2 = new AmCharts.AmGraph();
    graph2 = new AmCharts.AmGraph();
    graph2.title = "Midi - Restaurant";
    graph2.labelText = "[[value]]";
    graph2.valueField = "food_lunch";
    graph2.type = "column";
    graph2.lineAlpha = 0;
    graph2.fillAlphas = 1;
    graph2.lineColor = "#88ff88";
    graph2.balloonText = "[[category]] : [[title]] [[value]]";
    chart.addGraph(graph2);

    // third graph                 
    var graph3 = new AmCharts.AmGraph();
    graph3.title = "Goûter - Boissons";
    graph3.labelText = "[[value]]";
    graph3.valueField = "drink_snack";
    graph3.type = "column";
    graph3.lineAlpha = 0;
    graph3.fillAlphas = 1;
    graph3.lineColor = "#bbbbff";
    graph3.balloonText = "[[category]] : [[title]] [[value]]";
    chart.addGraph(graph3);

    // fourth graph  
    var graph4 = new AmCharts.AmGraph();
    graph4.title = "Goûter - Restaurant";
    graph4.labelText = "[[value]]";
    graph4.valueField = "food_snack";
    graph4.type = "column";
    graph4.lineAlpha = 0;
    graph4.fillAlphas = 1;
    graph4.lineColor = "#8888ff";
    graph4.balloonText = "[[category]] : [[title]] [[value]]";
    chart.addGraph(graph4);

    // LEGEND                  
    var legend = new AmCharts.AmLegend();
    legend.borderAlpha = 0.2;
    legend.horizontalGap = 10;
    legend.useGraphSettings = true;
    chart.addLegend(legend);

    // WRITE
    chart.write(containerId);
}

function cateringHourChart(containerId, data) {
    // SERIAL CHART
    var chart = new AmCharts.AmSerialChart();
    chart.dataProvider = data;
    chart.categoryField = "hour";
    // chart.plotAreaBorderAlpha = 0.2;

    // AXES
    // category
    var categoryAxis = chart.categoryAxis;
    // categoryAxis.gridAlpha = 0.1;
    // categoryAxis.axisAlpha = 0;
    categoryAxis.gridPosition = "start";

    // value
    var valueAxis = new AmCharts.ValueAxis();
    valueAxis.stackType = "regular";
    // valueAxis.gridAlpha = 0.1;
    // valueAxis.axisAlpha = 0;
    chart.addValueAxis(valueAxis);

    // GRAPHS
    // first graph    
    var graph1 = new AmCharts.AmGraph();
    graph1.title = "Boissons";
    graph1.labelText = "[[value]]";
    graph1.valueField = "Café - boisson";
    graph1.type = "column";
    graph1.lineAlpha = 0;
    graph1.fillAlphas = 1;
    graph1.lineColor = "#ffbbbb";
    graph1.balloonText = "[[category]] : [[title]] [[value]]";
    chart.addGraph(graph1);

    // second graph              
    var graph2 = new AmCharts.AmGraph();
    graph2 = new AmCharts.AmGraph();
    graph2.title = "Restauration";
    graph2.labelText = "[[value]]";
    graph2.valueField = "resto";
    graph2.type = "column";
    graph2.lineAlpha = 0;
    graph2.fillAlphas = 1;
    graph2.lineColor = "#ff8888";
    graph2.balloonText = "[[category]] : [[title]] [[value]]";
    chart.addGraph(graph2);

    // LEGEND                  
    var legend = new AmCharts.AmLegend();
    legend.borderAlpha = 0.2;
    legend.horizontalGap = 10;
    legend.useGraphSettings = true;
    chart.addLegend(legend);

    // WRITE
    chart.write(containerId);
}

function categoryPie(containerId, data)
{
    var chart = new AmCharts.AmPieChart();
    chart.dataProvider = data;
    chart.titleField = "name";
    chart.valueField = "sales";
    chart.outlineColor = "#FFFFFF";
    chart.outlineAlpha = 0.8;
    chart.outlineThickness = 1;

    // LEGEND                  
    var legend = new AmCharts.AmLegend();
    legend.borderAlpha = 0.2;
    legend.horizontalGap = 10;
    legend.markerType = "circle";
    // legend.useGraphSettings = true;
    chart.addLegend(legend);

    // WRITE
    chart.write(containerId);
}