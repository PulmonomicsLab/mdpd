function getHistogramLayout(height, xLabel) {
    var histogramLayout = {
        plot_bgcolor: 'ffffff', //'#fff0f5',
        paper_bgcolor: 'ffffff', //'#fff0f5',
        height: height,
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
                text : xLabel,
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
    return histogramLayout;
}

var choroplethLayout = {
    paper_bgcolor: '#ffffff', //'#fff0f5',
    height: 400,
    autocolorscale: true,
    margin: {t:0, b:0, l:0, r:0},
    hoverlabel: {
        bgcolor: 'white',
        font: {size: 16, color: 'black'}
    },
    geo: {
        bgcolor: '#ffffff', //'#fff0f5',
        oceancolor: 'skyblue',
        showocean: true,
        coastlinewidth: 1,
        coastlinecolor: '#000000',
        projection: {type: 'robinson'}
    }
};

var sunburstLayout = {
    plot_bgcolor: '#ffffff', //'#fff0f5',
    paper_bgcolor: '#ffffff', //'#fff0f5',
    height: 600,
    hoverlabel: {
        bgcolor: 'white',
        font: {size: 14, color: 'black'}
    },
    margin: {l: 0, r: 0, b: 0, t: 0}
};

var pieLayout = {
    plot_bgcolor: '#ffffff', //'#fff0f5',
    paper_bgcolor: '#ffffff', //'#fff0f5',
    height: 400,
    margin: {l: 0, r: 0, b: 10, t: 40},
    showlegend: false
};



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
    var amplicon16SCounts = [];
    var ampliconITSCounts = [];
    var wmsCounts = [];
    for(var i=0; i<histogramData.length; ++i) {
        years[i] = histogramData[i].Year;
        amplicon16SCounts[i] = histogramData[i].Amplicon16SRunCount;
        ampliconITSCounts[i] = histogramData[i].AmpliconITSRunCount;
        wmsCounts[i] = histogramData[i].WMSRunCount;
    }

    var amplicon16STrace = {
        type: 'lines+markers',
        name: 'Amplicon-16S',
        x: years,
        y: amplicon16SCounts,
        marker: {size: 10},
        line: {width: 3}
    };
    var ampliconITSTrace = {
        type: 'lines+markers',
        name: 'Amplicon-ITS',
        x: years,
        y: ampliconITSCounts,
        marker: {size: 10},
        line: {width: 3}
    };
    var wmsTrace = {
        type: 'lines+markers',
        name: 'WMS',
        x: years,
        y: wmsCounts,
        opacity:0.8,
        marker: {size: 10},
        line: {width: 3}
    };
    Plotly.plot(plotDiv, [amplicon16STrace, ampliconITSTrace, wmsTrace], getHistogramLayout(300, 'Year'), {showSendToCloud:false});
}

function plotBiomeHistogramData(divId, histogramDataJSON) {
    var plotDiv = document.getElementById(divId);
    var histogramData = JSON.parse(histogramDataJSON);
    var years = [2012, 2013, 2014, 2015, 2016, 2017, 2018, 2019, 2020, 2021, 2022, 2023, 2024];
    var names = ['Anus', 'Gut', 'Large Intestine', 'Lower Respiratory Tract', 'Lung', 'Nasal', 'Oral', 'Rectum', 'Stomach', 'Upper Respiratory Tract'];

    var dataMap = new Map();
    for(var i=0; i<histogramData.length; ++i) {
        if(!dataMap.has(histogramData[i].Biome))
            dataMap.set(histogramData[i].Biome, new Map());
        dataMap.get(histogramData[i].Biome).set(histogramData[i].Year, histogramData[i].Counts);
    }

    var data = [];
    for (var i=0; i<names.length; ++i) {
        var yVector = [];
        for(var j=0; j<years.length; ++j)
            if(dataMap.has(names[i]) && dataMap.get(names[i]).has(years[j]))
                yVector.push(dataMap.get(names[i]).get(years[j]));
            else
                yVector.push(0);

        var trace = {
            type: 'lines+markers',
            name: names[i],
            x: years,
            y: yVector,
            opacity:0.8,
            marker: {size: 10},
            line: {width: 3}
        };
        data.push(trace);
    }

    Plotly.plot(plotDiv, data, getHistogramLayout(300, 'Year'), {showSendToCloud:false});
}

function plotDiseaseHistogramData(divId, histogramDataJSON) {
    var plotDiv = document.getElementById(divId);
    var histogramData = JSON.parse(histogramDataJSON);
    var years = [2012, 2013, 2014, 2015, 2016, 2017, 2018, 2019, 2020, 2021, 2022, 2023, 2024];
    var names = [
        'Acute Respiratory Distress Syndrome (ARDS)', 'Asthma', 'Asthma-COPD Overlap (ACO)', 'Bronchiectasis', 'Bronchiolitis', 'Bronchitis',
        'Chronic Obstructive Pulmonary Disease (COPD)', 'COPD-Bronchiectasis Association (CBA)', 'COVID-19', 'Cystic Fibrosis',
        'Idiopathic Pulmonary Fibrosis (IPF)', 'Interstitial Lung Disease (ILD)', 'Lung Cancer', 'Other Pulmonary Infections', 'Pneumonia',
        'Pneumonitis', 'Pulmonary Hypertension', 'Sarcoidosis', 'Tuberculosis', 'Control', 'Healthy'
    ];

    var dataMap = new Map();
    for(var i=0; i<histogramData.length; ++i) {
        if(!dataMap.has(histogramData[i].Grp))
            dataMap.set(histogramData[i].Grp, new Map());
        dataMap.get(histogramData[i].Grp).set(histogramData[i].Year, histogramData[i].Counts);
    }

    var data = [];
    for (var i=0; i<names.length; ++i) {
        var yVector = [];
        for(var j=0; j<years.length; ++j)
            if(dataMap.has(names[i]) && dataMap.get(names[i]).has(years[j]))
                yVector.push(dataMap.get(names[i]).get(years[j]));
            else
                yVector.push(0);

        var trace = {
            type: 'bar',
            name: names[i],
            x: years,
            y: yVector,
            opacity:0.8,
            marker: {size: 10}
        };
        data.push(trace);
    }

    Plotly.plot(plotDiv, data, getHistogramLayout(500, 'Year'), {showSendToCloud:false});
}

function plotStatData(stat1DivId, stat2DivId, stat3DivId, sunburstDivId, statDataJSON) {
    var stat1Div = document.getElementById(stat1DivId);
    var stat2Div = document.getElementById(stat2DivId);
    var stat3Div = document.getElementById(stat3DivId);
    var sunburstDiv = document.getElementById(sunburstDivId);
    var colors = ['#e9967a', '#b0c4de'];
    
    var data = JSON.parse(statDataJSON);

    var biomes = [];
    var biomeCounts = [];
    for(var i=0; i<data.biomeResult.length; ++i) {
        biomes.push(data.biomeResult[i].Biome);
        biomeCounts.push(data.biomeResult[i].BiomeCount);
    }
    var biomePieTrace = {
        type: 'pie',
        hole: .4,
        labels: biomes,
        values: biomeCounts,
//         marker: {size: 2},
        sort: false,
        hoverinfo: 'label+percent+value',
        textinfo: 'label+percent'
    };

    var assayTypes = [];
    var assayTypeCounts = [];
    for(var i=0; i<data.assayTypeResult.length; ++i) {
        assayTypes.push(data.assayTypeResult[i].AssayType);
        assayTypeCounts.push(data.assayTypeResult[i].AssayTypeCount);
    }
    var assayTypePieTrace = {
        type: 'pie',
        hole: 0.4,
        labels: assayTypes,
        values: assayTypeCounts,
//         marker: {colors: colors},
        sort: false,
        hoverinfo: 'label+percent+value',
        textinfo: 'label+percent'
    };

    var diseases = [
        'Acute Respiratory Distress Syndrome (ARDS)', 'Asthma', 'Asthma-COPD Overlap (ACO)', 'Bronchiectasis', 'Bronchiolitis', 'Bronchitis',
        'Chronic Obstructive Pulmonary Disease (COPD)', 'COPD-Bronchiectasis Association (CBA)', 'COVID-19', 'Cystic Fibrosis',
        'Idiopathic Pulmonary Fibrosis (IPF)', 'Interstitial Lung Disease (ILD)', 'Lung Cancer', 'Other Pulmonary Infections', 'Pneumonia',
        'Pneumonitis', 'Pulmonary Hypertension', 'Sarcoidosis', 'Tuberculosis', 'Control', 'Healthy'
    ];
    var diseasesLabels = [
        'Acute Respiratory Distress Syndrome (ARDS)', 'Asthma', 'Asthma-COPD Overlap (ACO)', 'Bronchiectasis', 'Bronchiolitis', 'Bronchitis',
        'Chronic <br>Obstructive <br>Pulmonary <br>Disease <br>(COPD)', 'COPD-Bronchiectasis Association (CBA)', 'COVID-19', 'Cystic <br>Fibrosis',
        'Idiopathic Pulmonary Fibrosis (IPF)', 'Interstitial Lung Disease (ILD)', 'Lung <br>Cancer', 'Other Pulmonary Infections', 'Pneumonia',
        'Pneumonitis', 'Pulmonary Hypertension', 'Sarcoidosis', 'Tuberculosis', 'Control', 'Healthy'
    ];
    var diseasesShort = [
        'ARDS', 'Asthma', 'ACO', 'Bronchiectasis', 'Bronchiolitis', 'Bronchitis', 'COPD', 'CBA', 'COVID', 'CF',
        'IPF', 'ILD', 'LC', 'OPI', 'Pneumonia', 'Pneumonitis', 'PH', 'Sarcoidosis', 'TB', 'Control', 'Healthy'
    ];
    var assayTypesShort = ['16S', 'ITS', 'WMS'];
    var dataMap = new Map();
    for(var i=0; i<data.diseaseBiomeCountResult.length; ++i) {
        if(!dataMap.has(data.diseaseBiomeCountResult[i].Biome))
            dataMap.set(data.diseaseBiomeCountResult[i].Biome, new Map());
        dataMap.get(data.diseaseBiomeCountResult[i].Biome).set(data.diseaseBiomeCountResult[i].Grp, data.diseaseBiomeCountResult[i].DiseaseCount);
    }
    var diseaseBarTrace = [];
    for (var i=0; i<biomes.length; ++i) {
        var yVector = [];
        for(var j=0; j<diseases.length; ++j)
            if(dataMap.has(biomes[i]) && dataMap.get(biomes[i]).has(diseases[j]))
                yVector.push(dataMap.get(biomes[i]).get(diseases[j]));
            else
                yVector.push(0);
        diseaseBarTrace.push({
            type: 'bar',
            name: biomes[i],
            x: diseases,
            y: yVector,
            opacity: 0.8,
            marker: {size: 10}
        });
    }

    var sunburstIds = ['Total'].concat(diseasesShort);
//     var sunburstIds = Array.from(diseasesShort);
    for (var i=0; i<diseasesShort.length; ++i)
        for (var j=0; j<assayTypesShort.length; ++j)
            sunburstIds.push(diseasesShort[i] + '_' + assayTypesShort[j]);

    var sunburstLabels = ['Total number of runs'].concat(diseasesLabels);
//     var sunburstLabels = Array.from(diseasesLabels);
    for (var i=0; i<diseasesShort.length; ++i)
        for (var j=0; j<assayTypes.length; ++j)
            sunburstLabels.push(assayTypes[j]);

    var sunburstParents = [''];
//     var sunburstParents = [];
    for (var i=0; i<diseasesShort.length; ++i)
        sunburstParents.push('Total');
//         sunburstParents.push('');
    for (var i=0; i<diseasesShort.length; ++i)
        for (var j=0; j<assayTypes.length; ++j)
            sunburstParents.push(diseasesShort[i]);

    var diseaseCountMap = new Map();
    for(var i=0; i<data.diseaseCountResult.length; ++i)
        diseaseCountMap.set(data.diseaseCountResult[i].Grp, data.diseaseCountResult[i].C);
    var diseaseATCountMap = new Map();
    for(var i=0; i<data.diseaseATCountResult.length; ++i) {
        if(!diseaseATCountMap.has(data.diseaseATCountResult[i].Grp))
            diseaseATCountMap.set(data.diseaseATCountResult[i].Grp, new Map());
        diseaseATCountMap.get(data.diseaseATCountResult[i].Grp).set(data.diseaseATCountResult[i].AssayType, data.diseaseATCountResult[i].C);
    }
    var sunburstValues = [data.totalCountResult[0].C];
//     var sunburstValues = [];
    for (var i=0; i<diseases.length; ++i)
        sunburstValues.push((diseaseCountMap.has(diseases[i])) ? diseaseCountMap.get(diseases[i]) : 0);
    for (var i=0; i<diseases.length; ++i) {
        if(!diseaseATCountMap.has(diseases[i]))
            for (var j=0; j<assayTypes.length; ++j)
                sunburstValues.push(0);
        else
            for (var j=0; j<assayTypes.length; ++j)
                sunburstValues.push((diseaseATCountMap.get(diseases[i]).has(assayTypes[j])) ? diseaseATCountMap.get(diseases[i]).get(assayTypes[j]) : 0);
    }
//     alert('' + sunburstIds.length + ' ' + sunburstLabels.length + ' ' + sunburstParents.length + ' ' + sunburstValues.length);

    var sunburstTrace = {
        type: 'sunburst',
        ids: sunburstIds,
        labels: sunburstLabels,
        parents: sunburstParents,
        values: sunburstValues,
        sort: false,
        branchvalues: 'total'
    }

    Plotly.plot(stat1Div, [biomePieTrace], pieLayout, {showSendToCloud:false});
    Plotly.plot(stat2Div, [assayTypePieTrace], pieLayout, {showSendToCloud:false});
    Plotly.plot(stat3Div, diseaseBarTrace, getHistogramLayout(600, 'Group'), {showSendToCloud:false});
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
