var histogramLayout = {
    plot_bgcolor: '#fff0f5',//'#ffe6cc',//'#e6e6e6',
    paper_bgcolor: '#fff0f5',//'#ffe6cc',//'#e6e6e6',
    height: 400,
    barmode: 'stack',
    hoverlabel: {
        bgcolor: 'white',
        font: {size: 14, color: 'black'}
    },
    hovertext: {font: {color: 'black'}},
    margin: {t: 10},
    xaxis: {
        visible : true,
        automargin: true,
        color: 'black',
        linewidth: 2,
        ticks: 'outside',
        ticklen: 10,
        tickwidth: 2,
        tickfont: {size: 12},
        title : {
            text : 'Release Year',
            font: {size: 14}
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
        tickfont: {size: 12},
        title : {
            text : 'Number of runs',
            font: {size: 14}
        }
    },
    legend: {font: {size: 12, color: 'black'}, y: 0.5}
};

var choroplethLayout = {
    paper_bgcolor: '#fff0f5',
    height: 400,
    autocolorscale: true,
    margin: {t:0, b:0, l:0, r:0},
    hoverlabel: {
        bgcolor: 'white',
        font: {size: 16, color: 'black'}
    },
    geo: {
        bgcolor: '#fff0f5',
        oceancolor: 'skyblue',
        showocean: true,
        coastlinewidth: 1,
        coastlinecolor: '#000000',
        projection: {type: 'robinson'}
    }
};

var sunburstLayout = {
    plot_bgcolor: '#fff0f5',//'#ffe6cc',//'#e6e6e6',
    paper_bgcolor: '#fff0f5',//'#ffe6cc',//'#e6e6e6',
    height: 600,
    hoverlabel: {
        bgcolor: 'white',
        font: {size: 14, color: 'black'}
    },
    margin: {l: 0, r: 0, b: 0, t: 0}
};

var pieLayout = {
    plot_bgcolor: '#fff0f5',//'#ffe6cc',//'#e6e6e6',
    paper_bgcolor: '#fff0f5',//'#ffe6cc',//'#e6e6e6',
    height: 300,
    margin: {l: 0, r: 0, b: 0, t: 0},
    showlegend: false
};

var barlayout = {
    plot_bgcolor: '#fff0f5',//'#ffe6cc',//'#e6e6e6',
    paper_bgcolor: '#fff0f5',//'#ffe6cc',//'#e6e6e6',
    height: 300,
    margin: {l: 0, r: 0, b: 0, t: 0},
    hoverlabel: {
        bgcolor: 'white',
        font: {size: 14, color: 'black'}
    },
    xaxis: {
        visible : true,
        automargin: true,
        color: 'black',
        linewidth: 1,
        ticks: 'outside',
        tickfont: {size: 12},
        title : {
            text : 'Diseases',
            font: {size: 14}
        }
    },
    yaxis: {
        visible : true,
        automargin: true,
        color: 'black',
        linewidth: 1,
        ticks: 'outside',
        tickfont: {size: 12},
        title : {
            text : 'Number of runs',
            font: {size: 14}
        }
    },
    showlegend: false
};

var colors = ['#e9967a', '#b0c4de'];



function plotChoropleth(divId, choroplethDataJSON) {
    var mapDiv = document.getElementById(divId);
    
    var choroplethData = JSON.parse(choroplethDataJSON);
    var countries = [];
    var noOfRuns = [];
    for(var i=0; i<choroplethData.length; ++i) {
        countries[i] = choroplethData[i].Country;
        noOfRuns[i] = choroplethData[i].RunCount;
    }
    
    var data = [{
        type: 'choropleth',
        colorscale: 'YlOrRd',
        reversescale: true,
        locationmode: 'country names',
        locations: countries,
        z: noOfRuns,
        text: countries
    }];
    
    Plotly.plot(mapDiv, data, choroplethLayout, {showLink: false});
}

function plotAssayTypeHistogramData(divId, histogramDataJSON) {
    var plotDiv = document.getElementById(divId);

    var histogramData = JSON.parse(histogramDataJSON);
    var years = [];
    var ampliconCounts = [];
    var wgsCounts = [];
    for(var i=0; i<histogramData.length; ++i) {
        years[i] = histogramData[i].ReleaseYear;
        ampliconCounts[i] = histogramData[i].AmpliconRunCount;
        wgsCounts[i] = histogramData[i].WGSRunCount;
    }

    var ampliconTrace = {
            type: 'lines+markers',
            name: 'Amplicon',
            x: years,
            y: ampliconCounts,
            marker: {
                color: colors[1],
                size: 10
            },
            line: {width: 3}
        };
    var wgsTrace = {
            type: 'lines+markers',
            name: 'WGS',
            x: years,
            y: wgsCounts,
            marker: {
                color: colors[0],
                size: 10
            },
            line: {width: 3}
        };
//     plotDiv.innerHTML = histogramDataJSON + '<br/><br/>' + years + '<br/><br/>' + ampliconCounts + '<br/><br/>' + wgsCounts;
    Plotly.plot(plotDiv, [ampliconTrace, wgsTrace], histogramLayout, {showSendToCloud:false});
}

function plotBiomeHistogramData(divId, histogramDataJSON) {
    var plotDiv = document.getElementById(divId);

    var histogramData = JSON.parse(histogramDataJSON);
    var years = [];
    var lungCounts = [];
    var gutCounts = [];
    for(var i=0; i<histogramData.length; ++i) {
        years[i] = histogramData[i].ReleaseYear;
        lungCounts[i] = histogramData[i].LungRunCount;
        gutCounts[i] = histogramData[i].GutRunCount;
    }

    var lungTrace = {
            type: 'lines+markers',
            name: 'Lung',
            x: years,
            y: lungCounts,
            marker: {
                color: colors[1],
                size: 10
            },
            line: {width: 3}
        };
    var gutTrace = {
            type: 'lines+markers',
            name: 'Gut',
            x: years,
            y: gutCounts,
            marker: {
                color: colors[0],
                size: 10
            },
            line: {width: 3}
        };
    Plotly.plot(plotDiv, [lungTrace, gutTrace], histogramLayout, {showSendToCloud:false});
}

function plotDiseaseHistogramData(divId, histogramDataJSON) {
    var plotDiv = document.getElementById(divId);

    var histogramData = JSON.parse(histogramDataJSON);
    var years = [];
    var asthmaCounts = [];
    var copdCounts = [];
    var covidCounts = [];
    var cfCounts = [];
    var lcCounts = [];
    var pneumoniaCounts = [];
    var tbCounts = [];
    var controlCounts = [];
    var healthyCounts = [];
    for(var i=0; i<histogramData.length; ++i) {
        years[i] = histogramData[i].ReleaseYear;
        asthmaCounts[i] = histogramData[i].AsthmaRunCount;
        copdCounts[i] = histogramData[i].COPDRunCount;
        covidCounts[i] = histogramData[i].COVIDRunCount;
        cfCounts[i] = histogramData[i].CFRunCount;
        lcCounts[i] = histogramData[i].LCRunCount;
        pneumoniaCounts[i] = histogramData[i].PneumoniaRunCount;
        tbCounts[i] = histogramData[i].TBRunCount;
        controlCounts[i] = histogramData[i].ControlRunCount;
        healthyCounts[i] = histogramData[i].HealthyRunCount;
    }
    var yVectors = [asthmaCounts, copdCounts, covidCounts, cfCounts, lcCounts, pneumoniaCounts, tbCounts, controlCounts, healthyCounts];
    var names = ['Asthma', 'COPD', 'COVID-19', 'Cystic Fibrosis', 'Lung Cancer', 'Pneumonia', 'Tuberculosis', 'Control', 'Healthy'];
    var data = [];

    for (var i=0; i<names.length; ++i) {
        var trace = {
            type: 'bar',
            name: names[i],
            x: years,
            y: yVectors[i],
            marker: {size: 10},
        };
        data.push(trace);
    }

//     plotDiv.innerHTML = histogramDataJSON + '<br/><br/>' + years + '<br/><br/>' + ampliconCounts + '<br/><br/>' + wgsCounts;
    Plotly.plot(plotDiv, data, histogramLayout, {showSendToCloud:false});
}

function plotStatData(stat1DivId, stat2DivId, stat3DivId, sunburstDivId, statDataJSON) {
    var stat1Div = document.getElementById(stat1DivId);
    var stat2Div = document.getElementById(stat2DivId);
    var stat3Div = document.getElementById(stat3DivId);
    var sunburstDiv = document.getElementById(sunburstDivId);
    var colors = ['#e9967a', '#b0c4de'];
    
    var data = JSON.parse(statDataJSON);
    
    var biomePieTrace = {
        type: 'pie',
        hole: .4,
        labels: ['Lung', 'Gut'],
        values: [data.lungCount, data.gutCount],
        marker: {colors: colors},
        hoverinfo: 'label+percent+value',
        textinfo: 'label+percent+value'
    };
    var assayTypePieTrace = {
        type: 'pie',
        hole: .4,
        labels: ['Amplicon', 'WGS'],
        values: [data.ampCount, data.wgsCount],
        marker: {colors: colors},
        hoverinfo: 'label+percent+value',
        textinfo: 'label+percent+value'
    };
    var diseaseBarTrace = {
        type: 'bar',
        x: ['Asthma', 'COPD', 'COVID-19', 'Cystic Fibrosis', 'Lung Cancer', 'Pneumonia', 'Tuberculosis', 'Control', 'Healthy'],
        y: [data.asthmaCount, data.copdCount, data.covidCount, data.cfCount, data.lcCount, data.pneumoniaCount, data.tbCount, data.controlCount, data.healthyCount],
        hoverinfo: 'label+percent+value',
        textinfo: 'label+percent+value'
    };

    var sunburstTrace = {
            type: 'sunburst',
            ids: [
                'Total', 'Asthma', 'COPD', 'COVID', 'CF', 'LC', 'Pneumonia', 'TB', 'Control', 'Healthy',
                'Asthma_Lung', 'Asthma_Gut', 'COPD_Lung', 'COPD_Gut', 'COVID_Lung', 'COVID_Gut', 'CF_Lung', 'CF_Gut', 'LC_Lung', 'LC_Gut',
                'Pneumonia_Lung', 'Pneumonia_Gut', 'TB_Lung', 'TB_Gut', 'Control_Lung', 'Control_Gut', 'Healthy_Lung', 'Healthy_Gut',
                'Asthma_Lung_Amplicon', 'Asthma_Lung_WGS', 'Asthma_Gut_Amplicon', 'Asthma_Gut_WGS',
                'COPD_Lung_Amplicon', 'COPD_Lung_WGS', 'COPD_Gut_Amplicon', 'COPD_Gut_WGS',
                'COVID_Lung_Amplicon', 'COVID_Lung_WGS', 'COVID_Gut_Amplicon', 'COVID_Gut_WGS',
                'CF_Lung_Amplicon', 'CF_Lung_WGS', 'CF_Gut_Amplicon', 'CF_Gut_WGS',
                'LC_Lung_Amplicon', 'LC_Lung_WGS', 'LC_Gut_Amplicon', 'LC_Gut_WGS',
                'Pneumonia_Lung_Amplicon', 'Pneumonia_Lung_WGS', 'Pneumonia_Gut_Amplicon', 'Pneumonia_Gut_WGS',
                'TB_Lung_Amplicon', 'TB_Lung_WGS', 'TB_Gut_Amplicon', 'TB_Gut_WGS',
                'Control_Lung_Amplicon', 'Control_Lung_WGS', 'Control_Gut_Amplicon', 'Control_Gut_WGS',
                'Healthy_Lung_Amplicon', 'Healthy_Lung_WGS', 'Healthy_Gut_Amplicon', 'Healthy_Gut_WGS'
            ],
            labels: ['Total number of runs', 'Asthma', 'COPD', 'COVID-19', 'Cystic Fibrosis', 'Lung Cancer', 'Pneumonia', 'TB', 'Control', 'Healthy',
                'Lung', 'Gut', 'Lung', 'Gut', 'Lung', 'Gut', 'Lung', 'Gut', 'Lung', 'Gut',
                'Lung', 'Gut', 'Lung', 'Gut', 'Lung', 'Gut', 'Lung', 'Gut',
                'Amplicon', 'WGS', 'Amplicon', 'WGS',
                'Amplicon', 'WGS', 'Amplicon', 'WGS',
                'Amplicon', 'WGS', 'Amplicon', 'WGS',
                'Amplicon', 'WGS', 'Amplicon', 'WGS',
                'Amplicon', 'WGS', 'Amplicon', 'WGS',
                'Amplicon', 'WGS', 'Amplicon', 'WGS',
                'Amplicon', 'WGS', 'Amplicon', 'WGS',
                'Amplicon', 'WGS', 'Amplicon', 'WGS',
                'Amplicon', 'WGS', 'Amplicon', 'WGS'],
            parents: ['', 'Total', 'Total', 'Total', 'Total', 'Total', 'Total', 'Total', 'Total', 'Total',
                'Asthma', 'Asthma', 'COPD', 'COPD', 'COVID', 'COVID', 'CF', 'CF', 'LC', 'LC',
                'Pneumonia', 'Pneumonia', 'TB', 'TB', 'Control', 'Control', 'Healthy', 'Healthy',
                'Asthma_Lung', 'Asthma_Lung', 'Asthma_Gut', 'Asthma_Gut',
                'COPD_Lung', 'COPD_Lung', 'COPD_Gut', 'COPD_Gut',
                'COVID_Lung', 'COVID_Lung', 'COVID_Gut', 'COVID_Gut',
                'CF_Lung', 'CF_Lung', 'CF_Gut', 'CF_Gut',
                'LC_Lung', 'LC_Lung', 'LC_Gut', 'LC_Gut',
                'Pneumonia_Lung', 'Pneumonia_Lung', 'Pneumonia_Gut', 'Pneumonia_Gut',
                'TB_Lung', 'TB_Lung', 'TB_Gut', 'TB_Gut',
                'Control_Lung', 'Control_Lung', 'Control_Gut', 'Control_Gut',
                'Healthy_Lung', 'Healthy_Lung', 'Healthy_Gut', 'Healthy_Gut'],
            values: [
                data.totalCount, data.asthmaCount, data.copdCount, data.covidCount, data.cfCount, data.lcCount, data.pneumoniaCount, data.tbCount, data.controlCount, data.healthyCount,
                data.asthmaLungCount, data.asthmaGutCount, data.copdLungCount, data.copdGutCount, data.covidLungCount, data.covidGutCount, data.cfLungCount, data.cfGutCount, data.lcLungCount, data.lcGutCount,
                data.pneumoniaLungCount, data.pneumoniaGutCount, data.tbLungCount, data.tbGutCount, data.controlLungCount, data.controlGutCount, data.healthyLungCount, data.healthyGutCount,
                data.asthmaLungAmpCount, data.asthmaLungWGSCount, data.asthmaGutAmpCount, data.asthmaGutWGSCount,
                data.copdLungAmpCount, data.copdLungWGSCount, data.copdGutAmpCount, data.copdGutWGSCount,
                data.covidLungAmpCount, data.covidLungWGSCount, data.covidGutAmpCount, data.covidGutWGSCount,
                data.cfLungAmpCount, data.cfLungWGSCount, data.cfGutAmpCount, data.cfGutWGSCount,
                data.lcLungAmpCount, data.lcLungWGSCount, data.lcGutAmpCount, data.lcGutWGSCount,
                data.pneumoniaLungAmpCount, data.pneumoniaLungWGSCount, data.pneumoniaGutAmpCount, data.pneumoniaGutWGSCount,
                data.tbLungAmpCount, data.tbLungWGSCount, data.tbGutAmpCount, data.tbGutWGSCount,
                data.controlLungAmpCount, data.controlLungWGSCount, data.controlGutAmpCount, data.controlGutWGSCount,
                data.healthyLungAmpCount, data.healthyLungWGSCount, data.healthyGutAmpCount, data.healthyGutWGSCount
            ]
    }
//     var sunburstValues = [
//         data.totalCount, data.asthmaCount, data.copdCount, data.covidCount, data.cfCount, data.lcCount, data.pneumoniaCount, data.tbCount, data.controlCount, data.healthyCount,
//         data.asthmaLungCount, data.asthmaGutCount, data.copdLungCount, data.copdGutCount, data.covidLungCount, data.covidGutCount, data.cfLungCount, data.cfGutCount, data.lcLungCount, data.lcGutCount,
//         data.pneumoniaLungCount, data.pneumoniaGutCount, data.tbLungCount, data.tbGutCount, data.controlLungCount, data.controlGutCount, data.healthyLungCount, data.healthyGutCount,
//         data.asthmaLungAmpCount, data.asthmaLungWGSCount, data.asthmaGutAmpCount, data.asthmaGutWGSCount,
//         data.copdLungAmpCount, data.copdLungWGSCount, data.copdGutAmpCount, data.copdGutWGSCount,
//         data.covidLungAmpCount, data.covidLungWGSCount, data.covidGutAmpCount, data.covidGutWGSCount,
//         data.cfLungAmpCount, data.cfLungWGSCount, data.cfGutAmpCount, data.cfGutWGSCount,
//         data.lcLungAmpCount, data.lcLungWGSCount, data.lcGutAmpCount, data.lcGutWGSCount,
//         data.pneumoniaLungAmpCount, data.pneumoniaLungWGSCount, data.pneumoniaGutAmpCount, data.pneumoniaGutWGSCount,
//         data.tbLungAmpCount, data.tbLungWGSCount, data.tbGutAmpCount, data.tbGutWGSCount,
//         data.controlLungAmpCount, data.controlLungWGSCount, data.controlGutAmpCount, data.controlGutWGSCount,
//         data.healthyLungAmpCount, data.healthyLungWGSCount, data.healthyGutAmpCount, data.healthyGutWGSCount
//     ];
//     for (var i=0; i<sunburstTrace.labels.length; ++i)
//         sunburstTrace.labels[i] = sunburstTrace.labels[i] + " (" + sunburstValues[i] + ")";

    // Plotly.plot(statDiv, [biomePieTrace, assayTypePieTrace, diseasePieTrace], pieLayout, {showSendToCloud:false});
    Plotly.plot(stat1Div, [biomePieTrace], pieLayout, {showSendToCloud:false});
    Plotly.plot(stat2Div, [assayTypePieTrace], pieLayout, {showSendToCloud:false});
    Plotly.plot(stat3Div, [diseaseBarTrace], barlayout, {showSendToCloud:false});
    Plotly.plot(sunburstDiv, [sunburstTrace], sunburstLayout, {showSendToCloud:false});
}



function getStatisticsData(divId1, divId2) {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            plotStatData(divId1+'_1', divId1+'_2', divId1+'_3', divId2, this.responseText);
        }
    };
    xmlhttp.open('GET', 'get_statistics_data.php', true);
    xmlhttp.setRequestHeader("Content-type", "text/json");
    xmlhttp.send();
}

function getChoroplethData(divId) {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            plotChoropleth(divId, this.responseText);
        }
    };
    xmlhttp.open('GET', 'get_choropleth_data.php', true);
    xmlhttp.setRequestHeader("Content-type", "text/json");
    xmlhttp.send();
}

function getYearWiseHistogramData(divId) {
    getTypeBasedYearWiseHistogramData(divId + '_1', 'AssayType');
    getTypeBasedYearWiseHistogramData(divId + '_2', 'Biome');
    getTypeBasedYearWiseHistogramData(divId + '_3', 'Disease');
}

function getTypeBasedYearWiseHistogramData(divId, type) {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            if (type == 'AssayType')
                plotAssayTypeHistogramData(divId, this.responseText);
            else if (type == 'Biome')
                plotBiomeHistogramData(divId, this.responseText);
            else if (type == 'Disease')
                plotDiseaseHistogramData(divId, this.responseText);
        }
    };
    xmlhttp.open('GET', 'get_yearwise_histogram_data.php?type=' + type, true);
    xmlhttp.setRequestHeader("Content-type", "text/json");
    xmlhttp.send();
}
