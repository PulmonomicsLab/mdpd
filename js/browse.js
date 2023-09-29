var rows = null;
var healthyControlRows = null;
var subGroupRows = null;
var disease = null;
var subGroup = null;

function hideDiv(divId){
    document.getElementById(divId).style.display = 'none';
}

function initializeDiseaseWiseData(dataJSON, dis) {
    rows = JSON.parse(dataJSON);
    disease = dis;
    healthyControlRows = new Set();
    for(var i=0; i<rows.length; ++i) {
        var groups = rows[i].Grp.split(';');
        for(var j=0; j<groups.length; ++j) {
            if(groups[j] == 'Healthy' || groups[j] == 'Control')
                healthyControlRows.add(i);
        }
    }
//     alert(Array.from(healthyControlRows));
}

function initializeSubGroupWiseData(dataJSON, sg) {
    subGroupRows = JSON.parse(dataJSON);
    subGroup = sg;
}

function getAllDiseasewiseResultHTML(divId) {
    var s = '<table class="browse-result-summary" border="1"><tr><th>BioProject ID</th><th>SRA Study ID</th><th>Run Count</th><th>Group</th><th>Sub-group</th><th>Isolation Source</th><th>Biome</th><th>Assay Type</th></tr>';
    for(var i=0; i<rows.length; ++i) {
        s += '<tr>';
        s += '<td><a style="color:#003325;" target="_blank" href="bioproject_id.php?key=' + rows[i].BioProject + '">' + rows[i].BioProject + ' <img src="resource/redirect-icon.png" height="14pt" width="auto" /></a></td>';
        s += '<td>' + rows[i].SRA + '</td>';
        s += '<td>' + rows[i].RunCount + '</td>';
        s += '<td>' + rows[i].Grp.replace(/;/g, '; ') + '</td>';
        s += '<td>' + rows[i].SubGroup.replace(/;/g, '; ') + '</td>';
        s += '<td>' + rows[i].IsolationSource.replace(/;/g, '; ') + '</td>';
        s += '<td>' + rows[i].Biome.replace(/;/g, '; ') + '</td>';
        s += '<td>' + rows[i].AssayType.replace(/;/g, '; ') + '</td>';
        s += '</tr>';
    }
    s += '</table>';
    
    return s;
}

function getFilteredDiseaseWiseResultHTML(divId) {
    var s = '<table class="browse-result-summary" border="1"><tr><th>BioProject ID</th><th>SRA Study ID</th><th>Run Count</th><th>Group</th><th>Sub-group</th><th>Isolation Source</th><th>Biome</th><th>Assay Type</th></tr>';
    for(i of healthyControlRows.values()) {
        s += '<tr>';
        s += '<td><a style="color:#003325;" target="_blank" href="bioproject_id.php?key=' + rows[i].BioProject + '">' + rows[i].BioProject + ' <img src="resource/redirect-icon.png" height="14pt" width="auto" /></a></td>';
        s += '<td>' + rows[i].SRA + '</td>';
        s += '<td>' + rows[i].RunCount + '</td>';
        s += '<td>' + rows[i].Grp.replace(/;/g, '; ') + '</td>';
        s += '<td>' + rows[i].SubGroup.replace(/;/g, '; ') + '</td>';
        s += '<td>' + rows[i].IsolationSource.replace(/;/g, '; ') + '</td>';
        s += '<td>' + rows[i].Biome.replace(/;/g, '; ') + '</td>';
        s += '<td>' + rows[i].AssayType.replace(/;/g, '; ') + '</td>';
        s += '</tr>';
    }
    s += '</table>';

    return s;
}

function getAllSubGroupWiseResultHTML(divId) {
    var s = '<table class="browse-result-summary" border="1"><tr><th>BioProject ID</th><th>SRA Study ID</th><th>Run Count</th><th>Group</th><th>Sub-group</th><th>Isolation Source</th><th>Biome</th><th>Assay Type</th></tr>';
    for(var i=0; i<subGroupRows.length; ++i) {
        s += '<tr>';
        s += '<td><a style="color:#003325;" target="_blank" href="bioproject_id.php?key=' + subGroupRows[i].BioProject + '">' + subGroupRows[i].BioProject + ' <img src="resource/redirect-icon.png" height="14pt" width="auto" /></a></td>';
        s += '<td>' + subGroupRows[i].SRA + '</td>';
        s += '<td>' + subGroupRows[i].RunCount + '</td>';
        s += '<td>' + subGroupRows[i].Grp.replace(/;/g, '; ') + '</td>';
        s += '<td>' + subGroupRows[i].SubGroup.replace(/;/g, '; ') + '</td>';
        s += '<td>' + subGroupRows[i].IsolationSource.replace(/;/g, '; ') + '</td>';
        s += '<td>' + subGroupRows[i].Biome.replace(/;/g, '; ') + '</td>';
        s += '<td>' + subGroupRows[i].AssayType.replace(/;/g, '; ') + '</td>';
        s += '</tr>';
    }
    s += '</table>';

    return s;
}

function filter_diseases(resultDivId) {
    var hideButton = '<center><button type="button" class="round" onclick="hideDiv(\'' + resultDivId + '\')">&#10005;</button></center>';
    var resultElement = document.getElementById(resultDivId);
    resultElement.style.display = 'block';
    if(document.getElementById('filter_cb') != null && document.getElementById('filter_cb').checked == true) {
        var filterCheckbox = '<div style="margin:10px 0 0 10px;"><input type="checkbox" id="filter_cb" onclick="filter_diseases(\'' + resultDivId + '\')" checked /><label>&nbsp;<b><u>Show only BioProjects with Healthy/Control</u></b></label></div>';
        var resultCountString = '<p style="margin:2px;text-align:center;">Total number of BioProjects found in the database for Disease = "' + disease + '" with Healthy/Control groups : ' + healthyControlRows.size + '</p>';
        var diseaseResultHTML = getFilteredDiseaseWiseResultHTML('result_display');
        if(healthyControlRows.size <= 0)
            resultElement.innerHTML = hideButton + filterCheckbox + resultCountString;
        else
            resultElement.innerHTML = hideButton + filterCheckbox + resultCountString + diseaseResultHTML;
    } else {
        var filterCheckbox = '<div style="margin:10px 0 0 10px;"><input type="checkbox" id="filter_cb" onclick="filter_diseases(\'' + resultDivId + '\')" /><label>&nbsp;<b><u>Show only BioProjects with Healthy/Control</u></b></label></div>';
        var resultCountString = '<p style="margin:2px;text-align:center;">Total number of BioProjects found in the database for Disease = "' + disease + '" : ' + rows.length + '</p>';
        var diseaseResultHTML = getAllDiseasewiseResultHTML('result_display');
        if(rows.length <= 0)
            resultElement.innerHTML = hideButton + filterCheckbox + resultCountString;
        else
            resultElement.innerHTML = hideButton + filterCheckbox + resultCountString + diseaseResultHTML;
    }
}

function getBioProjects(dis, resultDivId){
    var httpReq = new XMLHttpRequest();
    httpReq.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            initializeDiseaseWiseData(this.responseText, dis);
            if(document.getElementById('filter_cb') != null)
                document.getElementById('filter_cb').checked = false;
            filter_diseases(resultDivId);
        }
    };
    httpReq.open('GET', 'disease_wise_bioprojects.php?key='+ encodeURIComponent(dis), true);
    httpReq.setRequestHeader("Content-type", "text/json");
    httpReq.send();
}

function getSubgroupBioProjects(sg, resultDivId){
    var httpReq = new XMLHttpRequest();
    httpReq.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            initializeSubGroupWiseData(this.responseText, sg);
            var resultElement = document.getElementById(resultDivId);
            resultElement.style.display = 'block';
            var hideButton = '<center><button type="button" class="round" onclick="hideDiv(\'' + resultDivId + '\')">&#10005;</button></center>';
            var resultCountString = '<p style="margin:2px;text-align:center;">Total number of BioProjects found in the database for SubGroup = "' + sg + '" : ' + subGroupRows.length + '</p>';
            var subgroupResultHTML = getAllSubGroupWiseResultHTML('result_display');
            if(subGroupRows.length <= 0)
                resultElement.innerHTML = hideButton + resultCountString;
            else
                resultElement.innerHTML = hideButton + resultCountString + subgroupResultHTML;
        }
    };
    httpReq.open('GET', 'subgroup_wise_bioprojects.php?key='+ encodeURIComponent(sg), true);
    httpReq.setRequestHeader("Content-type", "text/json");
    httpReq.send();
}

function showDiseasePairResults(resultId, htmlDivId){
    var hideButton = '<center><button type="button" class="round" onclick="hideDiv(\''+resultId+'\')">&#10005;</button></center>';
    document.getElementById(resultId).innerHTML = hideButton + document.getElementById(htmlDivId).innerHTML;
    document.getElementById(resultId).style.display = 'block';
    document.getElementById(resultId).scrollIntoView();
}
