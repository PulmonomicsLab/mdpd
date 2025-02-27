function getDataMap(taxa, subgroup, value) {
    var dataMap = new Map();
    for(var i=0; i<taxa.length; ++i) {
        if(dataMap.has(subgroup[i])) {
            dataMap.get(subgroup[i]).taxa.push(taxa[i]);
            dataMap.get(subgroup[i]).value.push(Math.abs(value[i]));
        } else {
            dataMap.set(subgroup[i], {taxa: [taxa[i]], value: [Math.abs(value[i])]});
        }
    }
    return dataMap;
}

function createDownloadLink(method, p_adjust_method, taxa, subgroup, value, pval, significance) {
    var scoreLabel = (method == 'edgeR') ? 'Log2FC' : 'LDA score (log10)';
    var pvalLabel = (p_adjust_method == 'none') ? 'P-value' : 'FDR-adjusted p-value';
    var s = 'Taxa\tSubGroup\t' + scoreLabel + '\t' + pvalLabel + '\tSignificance\n';
    for(var i=0; i<taxa.length; ++i) {
        s += taxa[i] + '\t';
        s += subgroup[i] + '\t';
        s += value[i] + '\t';
        s += pval[i] + '\t';
        s += significance[i] + '\n';
    }
    var blob = new Blob([s], {type: 'text/csv;charset=utf-8;'});
    document.getElementById('download_button').href = URL.createObjectURL(blob);
}

function createTaxaButtons(taxa) {
    var s = ''
    for(var i=0; i<taxa.length; ++i)
        s += '<div style="float:left; margin:5px;"><a href="taxa.php?key=' + taxa[i].substr(3).replace(/_/g, " ") + '" target="_blank"><button style="padding:2px 5px;">' + taxa[i] + '</button></a></div>'
    s += '<div style="clear:both;" />'
    document.getElementById('taxa_button_group').innerHTML = s;
}

function createPlotData(dataMap) {
//     var colors = ['#e9967a', '#b0c4de', '#f1ce8e', '#9ec08c'];
    var data = [];
    for(var subgroup of dataMap.keys()){
        var barChart = {
            type: 'bar',
            orientation: 'h',
            name: subgroup,
            x: dataMap.get(subgroup).value,
            y: dataMap.get(subgroup).taxa,
            text: dataMap.get(subgroup).taxa,
            textposition: 'inside',
            insidetextanchor: 'start',
            textfont: {color: '#000000'},
            marker: {/*color: colors[i], */opacity: 0.6},
        };
        data.push(barChart);
    }
    return data;
}

function makePlot(div_id, dataMap, method) {
    var graphDiv = document.getElementById(div_id);
    
    var cutoffs = Array.from(dataMap.keys());

    var xTitle = (method == 'edgeR') ? 'Log<sub>2</sub> fold change' : 'LDA score (log<sub>10</sub>)';
    var yTitle = 'Differentially abundant taxa';

    var data = createPlotData(dataMap)

    var layout = {
        plot_bgcolor: '#ffffff', //'#fff0f5',
        paper_bgcolor: '#ffffff', //'#fff0f5',
        height: 600,
        dragmode: 'pan',
//         bargap: 0.1,
        modebar: {
            color: '#262626',
            activecolor: '#262626'
        },
        hoverlabel: {
            bgcolor: 'white',
            font: {size: 18, color: '#000000'}
        },
        hovertext: {
            font: {color: '#000000'}
        },
        margin: {
            t: 30,
            l: 300,
            r: 300
        },
        xaxis: {
            visible : true,
            automargin: true,
            color: '#000000',
            linewidth: 2,
            ticks: 'outside',
            ticklen: 10,
            tickwidth: 2,
            tickfont: {size: 16},
            title : {
                text : xTitle,
                font: {size: 22}
            }
        },
        yaxis: {
            visible : true,
            automargin: true,
            color: '#000000',
            linewidth: 1,
            showticklabels: false,
            title : {
                text : yTitle,
                font: {size: 22}
            }
        },
        showlegend: true,
        legend: {
            font: {size: 16, color: '#000000'},
            borderwidth: 2,
            bordercolor: '#000000',
            y: 0.5
        }
    };

    var config = {
        showSendToCloud: false,
        displayModeBar: true,
        modeBarButtonsToRemove: ['toggleSpikelines', 'zoom2d', 'select2d', 'lasso2d'],
        modeBarButtonsToAdd: [{
            name: 'Export as SVG',
            icon: {
                width: 600,
                height: 600,
                path: 'M288 32c0-17.7-14.3-32-32-32s-32 14.3-32 32V274.7l-73.4-73.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l128 128c12.5 12.5 32.8 12.5 45.3 0l128-128c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L288 274.7V32zM64 352c-35.3 0-64 28.7-64 64v32c0 35.3 28.7 64 64 64H448c35.3 0 64-28.7 64-64V416c0-35.3-28.7-64-64-64H346.5l-45.3 45.3c-25 25-65.5 25-90.5 0L165.5 352H64zm368 56a24 24 0 1 1 0 48 24 24 0 1 1 0-48z'
            },
            click: function(img) {
                Plotly.downloadImage(
                    img,
                    {
                        filename: 'lda_plot_',
                        format: 'svg'
                    }
                );
            }
        }]
    }

//     Plotly.plot(graphDiv, {data: data, layout: layout, config: config, frames: frames})
    Plotly.plot(graphDiv, {data: data, layout: layout, config: config})
}

function plotLDA(div_id, response, method, taxa_level) {
    var data = JSON.parse(response);
    if(data.taxa.length > 0) {
        var dataMap = getDataMap(data.taxa, data.subgroup, data.value);
        document.getElementById(div_id).innerHTML = '';
        makePlot(div_id, dataMap, method);
        if (data.p_adjust == 'none')
            createDownloadLink(method, data.p_adjust, data.taxa, data.subgroup, data.value, data.pval, data.significance);
        else
            createDownloadLink(method, data.p_adjust, data.taxa, data.subgroup, data.value, data.padj, data.significance);
        document.getElementById('download_div').style.display = 'block';
        if (taxa_level == 'Species' || taxa_level == 'Genus') {
            createTaxaButtons(data.taxa);
            document.getElementById('taxa_button_group_heading').style.display = 'block';
            document.getElementById('taxa_button_group').style.display = 'block';
        }
    } else {
        document.getElementById(div_id).innerHTML = '<p>No significant taxa found</p>';
    }
}

function getLDAData(div_id, dataJSON) {
    var data = JSON.parse(dataJSON);
    var httpReq = new XMLHttpRequest();
    httpReq.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            plotLDA(div_id, this.responseText, data.method, data.taxa_level)
        }
    };
    httpReq.open('POST', 'lda_data.php', true);
    httpReq.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    httpReq.send(
        'bioproject=' + encodeURIComponent(data.bioproject) +
        '&' + 'at=' + encodeURIComponent(data.at) +
        '&' + 'is=' + encodeURIComponent(data.is) +
        '&' + 'method=' + encodeURIComponent(data.method) +
        '&' + 'alpha=' + encodeURIComponent(data.alpha) +
        '&' + 'p_adjust_method=' + encodeURIComponent(data.p_adjust_method) +
        '&' + 'filter_thres=' + encodeURIComponent(data.filter_thres) +
        '&' + 'taxa_level=' + encodeURIComponent(data.taxa_level) +
        '&' + 'threshold=' + encodeURIComponent(data.threshold)
    );
}
