function getDataMap(csv) {
    var dataMap = new Map();
    for(var i=1; i<csv.length; ++i) {
        var group = csv[i][1];
        var genus = csv[i][2];
        var score = parseFloat(csv[i][4]);
        if(dataMap.has(group)) {
            dataMap.get(group).set(genus, score);
//             dataMap.get(group).genuses.push(genus);
//             dataMap.get(group).scores.push(score);
        } else {
            dataMap.set(group, new Map().set(genus, score));
        }
    }
//     alert(dataMap);
    return dataMap;
}

function getIntersectionMap(dataMap) {
    var intersection = new Map();
    var isolationSources = Array.from(dataMap.keys());
    for(var x of dataMap.get(isolationSources[0]).entries())
        if(dataMap.get(isolationSources[1]).has(x[0]))
            intersection.set(x[0], [x[1], dataMap.get(isolationSources[1]).get(x[0])]);
    return intersection;
}

function makeUpsetPlot(div_id, dataMap) {
    var graphDiv = document.getElementById(div_id);
    var isolationSources = Array.from(dataMap.keys());
    const colors = ['#e9967a', '#b0c4de'];
    var names = [isolationSources[0], isolationSources[1]];
    if (names[0] == 'Endotracheal Aspirate')
        names[0] = 'EA'
    else if (names[1] == 'Endotracheal Aspirate')
        names[1] = 'EA'

    const data = [
        { name: names[0], elems: Array.from(dataMap.get(isolationSources[0]).keys()) },
        { name: names[1], elems: Array.from(dataMap.get(isolationSources[1]).keys()) }
    ];

    const sets = UpSetJS.asSets(data);
    for (i=0; i<sets.length; ++i)
        sets[i].color = colors[i];

    const plot = {
        sets: sets,
        width: 800,
        height: 300,
        combinations: {
            type: 'distinctIntersection',
            order: 'cardinality',
        },
        selection: null,
//         theme: 'lavender'
    };

    plot.onHover = (set) => {
        plot.selection = set;
        UpSetJS.render(graphDiv, plot);
    };

    UpSetJS.render(graphDiv, plot);
}

function makeLikertPlot(div_id, yAxisHeadings, dataMap) {
    var intersection = getIntersectionMap(dataMap);
    var isolationSources = Array.from(dataMap.keys());
    const colors = ['#e9967a', '#b0c4de'];

    am4core.useTheme(am4themes_animated);

    document.getElementById(div_id).style.height = (150 + intersection.size * 30) + "px";
    var chart = am4core.create(div_id, am4charts.XYChart);

    chart.data = []
    for(var x of intersection.entries()) {
        chart.data.push({'category': '[font-style: italic]' + x[0] + '[/]', 'is1': -x[1][0], 'is2': x[1][1]});
    }
//     document.getElementById('foo').innerHTML = JSON.stringify(chart.data);

    var categoryAxis = chart.yAxes.push(new am4charts.CategoryAxis());
    categoryAxis.dataFields.category = "category";
    categoryAxis.title.text = yAxisHeadings;
    categoryAxis.title.fontSize = 20;
    categoryAxis.renderer.grid.template.location = 0;
    categoryAxis.renderer.inversed = true;
    categoryAxis.renderer.minGridDistance = 1;
//     categoryAxis.renderer.minGridDistance = 20;
    categoryAxis.renderer.axisFills.template.disabled = false;


    var valueAxis = chart.xAxes.push(new am4charts.ValueAxis());
    valueAxis.title.text = "Prevalance";
    valueAxis.title.fontSize = 20;
    valueAxis.min = -1;
    valueAxis.max = 1;
//     valueAxis.renderer.minGridDistance = 50;
    valueAxis.renderer.ticks.template.length = 10;
    valueAxis.renderer.ticks.template.disabled = false;
    valueAxis.renderer.ticks.template.strokeOpacity = 1;
    valueAxis.renderer.labels.template.adapter.add("text", function(text) {
        return text;
    })

    chart.legend = new am4charts.Legend();
    chart.legend.position = "top";

//     Use only absolute numbers
    chart.numberFormatter.numberFormat = "#.####s";

    function createSeries(field, name, color) { //     Create series
        var series = chart.series.push(new am4charts.ColumnSeries());
        series.dataFields.valueX = field;
        series.dataFields.categoryY = "category";
        series.stacked = true;
        series.name = name;
        series.stroke = color;
        series.fill = color;

        var label = series.bullets.push(new am4charts.LabelBullet);
        label.label.text = "{valueX}";
        label.label.fill = am4core.color("black");
        label.label.strokeWidth = 0;
        label.label.truncate = false;
        label.label.hideOversized = true;
        label.locationX = 0.5;
        return series;
    }

    createSeries('is1', isolationSources[0], colors[0]);
    createSeries('is2', isolationSources[1], colors[1]);
}

function plotCharts(div_id_upset, div_id_likert, response) {
    var csv = $.csv.toArrays(response);
    var dataMap = getDataMap(csv);

//     var msg = dataMap.size + '<br/>';
//     for(var x of dataMap.keys())
//         msg += x + ' => ' + Array.from(dataMap.get(x).entries()) + '<br/><br/>';
//     document.getElementById(div_id).innerHTML = msg;

    makeUpsetPlot(div_id_upset, dataMap);
    makeLikertPlot(div_id_likert, csv[0][2], dataMap);
}
