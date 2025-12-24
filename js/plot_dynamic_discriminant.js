function getColorScale(colorScaleName) {
    if (colorScaleName == 'Purples') {
        return [
            ['0.0', 'rgb(106,81,163)'],
            ['0.222222222222', 'rgb(128,125,186)'],
            ['0.444444444444', 'rgb(158,154,200)'],
            ['0.666666666667', 'rgb(188,189,220)'],
            ['0.888888888889', 'rgb(218,218,235)'],
            ['1.0', 'rgb(239,237,245)']
        ];
    } else if (colorScaleName == 'Oranges') {
        return [
            ['0.0', 'rgb(217,72,1)'],
            ['0.222222222222', 'rgb(241,105,19)'],
            ['0.444444444444', 'rgb(253,141,60)'],
            ['0.666666666667', 'rgb(253,174,107)'],
            ['0.888888888889', 'rgb(253,208,162)'],
            ['1.0', 'rgb(254,230,206)']
        ];
    } else if (colorScaleName == 'Greens') {
        return [
            ['0.0', 'rgb(35,139,69)'],
            ['0.222222222222', 'rgb(65,171,93)'],
            ['0.444444444444', 'rgb(116,196,118)'],
            ['0.666666666667', 'rgb(161,217,155)'],
            ['0.888888888889', 'rgb(199,233,192)'],
            ['1.0', 'rgb(229,245,224)']
        ];
    } else if (colorScaleName == 'PRGn') {
        return [
            ['0.0', 'rgb(64,0,75)'],
            ['0.111111111111', 'rgb(118,42,131)'],
            ['0.222222222222', 'rgb(153,112,171)'],
            ['0.333333333333', 'rgb(194,165,207)'],
            ['0.444444444444', 'rgb(231,212,232)'],
            ['0.555555555556', 'rgb(217,240,211)'],
            ['0.666666666667', 'rgb(166,219,160)'],
            ['0.777777777778', 'rgb(90,174,97)'],
            ['0.888888888889', 'rgb(27,120,55)'],
            ['1.0', 'rgb(0,68,27)']
        ];
    } else if (colorScaleName == 'PrOr') {
        return [
            ['0.0', 'rgb(127,59,8)'],
            ['0.111111111111', 'rgb(179,88,6)'],
            ['0.222222222222', 'rgb(224,130,20)'],
            ['0.333333333333', 'rgb(253,184,99)'],
            ['0.444444444444', 'rgb(254,224,182)'],
            ['0.555555555556', 'rgb(216,218,235)'],
            ['0.666666666667', 'rgb(178,171,210)'],
            ['0.777777777778', 'rgb(128,115,172)'],
            ['0.888888888889', 'rgb(84,39,136)'],
            ['1.0', 'rgb(45,0,75)']
        ];
    } else if (colorScaleName == 'RdYlBu') {
        return [
            ['0.0', 'rgb(165,0,38)'],
            ['0.111111111111', 'rgb(215,48,39)'],
            ['0.222222222222', 'rgb(244,109,67)'],
            ['0.333333333333', 'rgb(253,174,97)'],
            ['0.444444444444', 'rgb(254,224,144)'],
            ['0.555555555556', 'rgb(224,243,248)'],
            ['0.666666666667', 'rgb(171,217,233)'],
            ['0.777777777778', 'rgb(116,173,209)'],
            ['0.888888888889', 'rgb(69,117,180)'],
            ['1.0', 'rgb(49,54,149)']
        ];
    } else if (colorScaleName == 'RdYlBu_truncated') {
        return [
            ['0.0', 'rgb(244,109,67)'],
            ['0.2', 'rgb(253,174,97)'],
            ['0.4', 'rgb(254,224,144)'],
            ['0.6', 'rgb(224,243,248)'],
            ['0.8', 'rgb(171,217,233)'],
            ['1.0', 'rgb(116,173,209)'],
        ];
    } else {
        return 'Greys';
    }
}

// function reverseColorScales(colorscale) {
//     var reverse = new Array();
//     for (var i = 0; i < colorscale.length; ++i) {
//         var color = new Array(2);
//         color[0] = colorscale[i][0];
//         color[1] = colorscale[colorscale.length - i - 1][1];
//         reverse.push(color);
//     }
//     return reverse;
// }

function getLDADataMap(taxa, name, value) {
    var dataMap = {taxa: [], name: [], values: []};
    dataMap.taxa = [...new Set(taxa)].sort();
    dataMap.name = [...new Set(name)].sort();
    var valueMap = new Map();
    for(var i=0; i<taxa.length; ++i)
        // valueMap.set(name[i] + taxa[i], Math.abs(value[i]));
        valueMap.set(name[i] + taxa[i], value[i]);
    for(var i=0; i < dataMap.name.length; ++i) {
        var valueRow = [];
        for(var j=0; j < dataMap.taxa.length; ++j)
                valueRow.push(valueMap.get(dataMap.name[i] + dataMap.taxa[j]));
        dataMap.values.push(valueRow);
    }
    return dataMap;
}

function createLDADownloadLink(heatmapData, button_id) {
    var s = '';
    for(var i=0; i<heatmapData.taxa.length; ++i)
        s += '\t' + heatmapData.taxa[i];
    s += '\n';
    for(var i=0; i<heatmapData.name.length; ++i) {
        s += heatmapData.name[i];
        for(var j=0; j<heatmapData.taxa.length; ++j)
            s += (heatmapData.values[i][j] == undefined) ? ('\t0') : ('\t' + heatmapData.values[i][j]);
        s += '\n';
    }
    var blob = new Blob([s], {type: 'text/csv;charset=utf-8;'});
    document.getElementById(button_id).href = URL.createObjectURL(blob);
}

function createTaxaButtons(heatmapData, mergedHeatmapData) {
    var s = '';
    var all_taxa = [...new Set([...heatmapData.taxa, ...mergedHeatmapData.taxa])];
    for(var i=0; i<all_taxa.length; ++i)
        s += '<div style="float:left; margin:5px;"><a href="taxa.php?key=' + all_taxa[i].substr(3).replace(/_/g, " ") + '" target="_blank"><button style="padding:2px 5px;">' + all_taxa[i] + '</button></a></div>';
    s += '<div style="clear:both;" />';
    document.getElementById('taxa_button_group').innerHTML = s;
}

function makeHeatmapPlot(div_id, heatmapData, method, colorScaleName) {
    var graphDiv = document.getElementById(div_id);
    var computedHeight = (30*heatmapData.name.length + 250);
    var xTitle = 'Taxa';
    var scoreLabel = '';
    if (method === 'lefse')
        scoreLabel = 'LDA score (log10)';
    if (method === 'ALDEx2_t')
        scoreLabel = 'diff.btw';
    if (method === 'linda')
        scoreLabel = 'Log2FC';
    if (method === 'ancombc2')
        scoreLabel = 'Log2FC';

    var data = [{
        x: heatmapData.taxa,
        y: heatmapData.name,
        z: heatmapData.values,
        zmid: 0,
        xgap: 2,
        ygap: 2,
        colorscale: getColorScale(colorScaleName),
        reversescale: true,
        colorbar: {
            len: (computedHeight > 300) ? 150 : 1,
            lenmode: (computedHeight > 300) ? 'pixels' : 'fraction',
            outlinewidth: 0,
            tickfont: {color: '#000000'},
            ticks: 'inside',
            title: {
                text: scoreLabel,
                side: 'bottom',
                font: {
                    size: 14,
                    color: '#000000'
                }
            },
        },
        type: 'heatmap'
    }];

    var layout = {
        plot_bgcolor: '#ffffff', //'#fff0f5',
        paper_bgcolor: '#ffffff', //'#fff0f5',
        height: computedHeight,
        margin: {
            t: 30,
//             l: 250,
//             b: 140
        },
        modebar: {
            color: '#262626',
            activecolor: '#262626'
        },
        hoverlabel: {
            font: {size: 16}
        },
        yaxis: {
            visible : true,
            automargin: true,
            showgrid: false,
            color: 'black',
            linewidth: 2,
            mirror: true,
            ticks: 'outside',
            ticklen: 10,
            tickwidth: 2,
            tickfont: {size: 10},
        },
        xaxis: {
            visible : true,
            automargin: true,
            showgrid: false,
            color: 'black',
            linewidth: 2,
            mirror: true,
            ticks: 'outside',
            ticklen: 10,
            tickwidth: 2,
            tickfont: {size: 10},
            title : {
                text : xTitle,
                font: {size: 22}
            }
        }
    };

    var config = {
        showSendToCloud: false,
        displayModeBar: true,
        modeBarButtonsToRemove: ['toggleSpikelines', 'zoom2d'],
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
                        filename: 'discriminant_analysis_heatmap_plot',
                        format: 'svg'
                    }
                );
            }
        }]
    }

    Plotly.plot(graphDiv, data, layout, config);
}

function plotData(lda_div_id, merged_lda_div_id, method, response) {
    var data = JSON.parse(response);
    var ldaData = data.lda;
    var mergedLdaData = data.merged_lda;
    if(ldaData.taxa.length > 0) {
        var ldaDataMap = getLDADataMap(ldaData.taxa, ldaData.subgroup, ldaData.value);
        document.getElementById(lda_div_id).innerHTML = '';
        makeHeatmapPlot(lda_div_id, ldaDataMap, method, 'RdYlBu_truncated');
        createLDADownloadLink(ldaDataMap, 'lda_download_button');
        document.getElementById('lda_download_div').style.display = 'block';
        document.getElementById('plot_1_heading').style.display = 'block';
        document.getElementById('plot_1_legend').style.display = 'block';
    } else {
        document.getElementById(lda_div_id).innerHTML = '<p id="plot_1_heading" style="margin-bottom:0; font-weight:bold; display:none;">A. Discriminant analysis across BioProjects</p><p>No significant taxa found</p>';
        document.getElementById('plot_1_heading').style.display = 'block';
    }

    if(mergedLdaData.taxa.length > 0) {
        var mergedLdaDataMap = getLDADataMap(mergedLdaData.taxa, mergedLdaData.subgroup, mergedLdaData.value);
        document.getElementById(merged_lda_div_id).innerHTML = '';
        makeHeatmapPlot(merged_lda_div_id, mergedLdaDataMap, method, 'PRGn');
        createLDADownloadLink(mergedLdaDataMap, 'merged_lda_download_button');
        document.getElementById('merged_lda_download_div').style.display = 'block';
        document.getElementById('plot_2_heading').style.display = 'block';
        document.getElementById('plot_2_legend').style.display = 'block';
    } else {
        document.getElementById(merged_lda_div_id).innerHTML = '<p id="plot_2_heading" style="margin-bottom:0; font-weight:bold; display:none;">B. Disciriminant analysis across Subgroups (with batch-effect correction)</p><p>No significant taxa found</p>';
        document.getElementById('plot_2_heading').style.display = 'block';
    }

    if (ldaData.taxa.length > 0 || mergedLdaData.taxa.length > 0) {
        createTaxaButtons(ldaDataMap, mergedLdaData);
        document.getElementById('taxa_button_group_heading').style.display = 'block';
        document.getElementById('taxa_button_group').style.display = 'block';
    }
}

function getHeatmapData(lda_div_id, merged_lda_div_id, dataJSON) {
    var data = JSON.parse(dataJSON);
    var httpReq = new XMLHttpRequest();
    httpReq.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            plotData(lda_div_id, merged_lda_div_id, data.method, this.responseText)
        }
    };
    httpReq.open('POST', 'dynamic_discriminant_analysis_data.php', true);
    httpReq.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    httpReq.send(
        'at=' + encodeURIComponent(data.at) +
        '&' + 'bioprojects=' + encodeURIComponent(JSON.stringify(data.bioprojects)) +
        '&' + 'runs=' + encodeURIComponent(JSON.stringify(data.runs)) +
        '&' + 'method=' + encodeURIComponent(data.method) +
        '&' + 'alpha=' + encodeURIComponent(data.alpha) +
        '&' + 'p_adjust_method=' + encodeURIComponent(data.p_adjust_method) +
        '&' + 'filter_thres=' + encodeURIComponent(data.filter_thres) +
        '&' + 'taxa_level=' + encodeURIComponent(data.taxa_level) +
        '&' + 'threshold=' + encodeURIComponent(data.threshold)
    );
}

