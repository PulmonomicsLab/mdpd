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
    
    var layout = {
        height: 400,
        autocolorscale: true,
        margin: {
            t: 0,
            b: 0,
            l: 0,
            r: 0
        },
        geo: {
            oceancolor: 'skyblue',
            showocean: true,
            coastlinewidth: 1,
            coastlinecolor: '#000000',
            projection: {
                type: 'robinson'
            }
        }
    };
    
//     mapDiv.innerHTML = countries + '<br/><br/>' + noOfRuns;
    Plotly.plot(mapDiv, data, layout, {showLink: false});
}
