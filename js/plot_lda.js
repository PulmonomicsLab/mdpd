function getDataMap(csv) {
    var dataMap = new Map();
    for(var i=1; i<csv.length; ++i) {
        var group = csv[i][2];
        var name = csv[i][1];
        var score = parseFloat(csv[i][3]);
        if(dataMap.has(group)) {
            dataMap.get(group).names.push(name);
            dataMap.get(group).scores.push(score);
        } else {
            dataMap.set(group, {names: [name], scores: [score]});
        }
    }
    return dataMap;
}

function makePlot(div_id, dataMap, assayType) {
    var graphDiv = document.getElementById(div_id);
    
    var data = [];
    var colors = ['#e9967a', '#b0c4de', '#f1ce8e', '#9ec08c'];
    var i = 0;
    var yTitle = (assayType == 'Amplicon') ? 'Differentially abundant genus' : 'Differentially abundant species';

    for(var x of dataMap.keys()){
        var barChart = {
            type: 'bar',
            name: x,
            x: dataMap.get(x).scores,
            y: dataMap.get(x).names,
            orientation: 'h',
            marker: {color: colors[i]}
        };
        i++;
        data.push(barChart);
    }

    var layout = {
        plot_bgcolor: '#fff0f5',//'#ffe6cc',//'#e6e6e6',
        paper_bgcolor: '#fff0f5',//'#ffe6cc',//'#e6e6e6',
        height: 800,
        bargap: 0.2,
        hoverlabel: {
            bgcolor: 'white',
            font: {size: 18, color: 'black'}
        },
        hovertext: {
            font: {color: 'black'}
        },
        margin: {
            t: 10
        },
        xaxis: {
            visible : true,
            automargin: true,
            color: 'black',
            linewidth: 2,
            ticks: 'outside',
            ticklen: 10,
            tickwidth: 2,
            tickfont: {size: 16},
            title : {
                text : 'LDA score (log<sub>10</sub>)',
                font: {size: 22}
            }
        },
        yaxis: {
            visible : true,
            automargin: true,
            color: 'black',
            linewidth: 2,
            ticks: 'outside',
            ticklen: 10,
            tickwidth: 2,
            tickfont: {size: 10},
            title : {
                text : yTitle,
                font: {size: 22}
            }
        },
        showlegend: true,
        legend: {
            font: {
                size: 16,
                color: 'black'
            },
            borderwidth: 2,
            bordercolor: 'black',
            y: 0.5
        }
    };

    Plotly.plot(graphDiv, data, layout, {showSendToCloud:true});
}

function plotLDA(div_id, response, assayType, score) {
    var csv = $.csv.toArrays(response);
    var dataMap = getDataMap(csv);
    
//     var msg = dataMap.size + '<br/>';
//     for(var x of dataMap.keys())
//         msg += x + ' => ' + JSON.stringify(dataMap.get(x)) + '<br/>';
//     document.getElementById(div_id).innerHTML = msg;
    
    makePlot(div_id, dataMap, assayType);
}

