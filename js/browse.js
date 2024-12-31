var rows = null;
var healthyControlRows = null;
var subGroupRows = null;
var isolationSourceRows = null;
var disease = null;
var subGroup = null;
var isolationSource = null;

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

function initializeIsolationSourceWiseData(dataJSON, is) {
    isolationSourceRows = JSON.parse(dataJSON);
    isolationSource = is;
}

function getAllDiseasewiseResultHTML(divId) {
    var s = '<table class="browse-result-summary" border="1"><tr><th>BioProject ID</th><th>Disease</th><th>Sub-group</th><th>Total Runs</th><th>Processed Runs</th><th>Biome</th><th>Assay Type</th><th>Library Layout</th></tr>';
    for(var i=0; i<rows.length; ++i) {
        s += '<tr>';
        s += '<td><a style="color:#003325;" target="_blank" href="bioproject_id.php?key=' + rows[i].BioProject + '">' + rows[i].BioProject + ' <img src="resource/redirect-icon.png" height="14pt" width="auto" /></a></td>';
        s += '<td>' + rows[i].Grp.replace(/;/g, '; ') + '</td>';
        s += '<td>' + rows[i].SubGroup.replace(/;/g, '; ') + '</td>';
        s += '<td>' + rows[i].TotalRuns + '</td>';
        s += '<td>' + rows[i].ProcessedRuns + '</td>';
        s += '<td>' + rows[i].Biome.replace(/;/g, '; ') + '</td>';
        s += '<td>' + rows[i].AssayType.replace(/;/g, '; ') + '</td>';
        s += '<td>' + rows[i].LibraryLayout.replace(/;/g, '; ') + '</td>';
        s += '</tr>';
    }
    s += '</table>';
    
    return s;
}

function getFilteredDiseaseWiseResultHTML(divId) {
    var s = '<table class="browse-result-summary" border="1"><tr><th>BioProject ID</th><th>Disease</th><th>Sub-group</th><th>Total Runs</th><th>Processed Runs</th><th>Biome</th><th>Assay Type</th><th>Library Layout</th></tr>';
    for(i of healthyControlRows.values()) {
        s += '<tr>';
        s += '<td><a style="color:#003325;" target="_blank" href="bioproject_id.php?key=' + rows[i].BioProject + '">' + rows[i].BioProject + ' <img src="resource/redirect-icon.png" height="14pt" width="auto" /></a></td>';
        s += '<td>' + rows[i].Grp.replace(/;/g, '; ') + '</td>';
        s += '<td>' + rows[i].SubGroup.replace(/;/g, '; ') + '</td>';
        s += '<td>' + rows[i].TotalRuns + '</td>';
        s += '<td>' + rows[i].ProcessedRuns + '</td>';
        s += '<td>' + rows[i].Biome.replace(/;/g, '; ') + '</td>';
        s += '<td>' + rows[i].AssayType.replace(/;/g, '; ') + '</td>';
        s += '<td>' + rows[i].LibraryLayout.replace(/;/g, '; ') + '</td>';
        s += '</tr>';
    }
    s += '</table>';

    return s;
}

function getAllSubGroupWiseResultHTML(divId) {
    var s = '<table class="browse-result-summary" border="1"><tr><th>BioProject ID</th><th>Disease</th><th>Sub-group</th><th>Total Runs</th><th>Processed Runs</th><th>Biome</th><th>Assay Type</th><th>Library Layout</th></tr>';
    for(var i=0; i<subGroupRows.length; ++i) {
        s += '<tr>';
        s += '<td><a style="color:#003325;" target="_blank" href="bioproject_id.php?key=' + subGroupRows[i].BioProject + '">' + subGroupRows[i].BioProject + ' <img src="resource/redirect-icon.png" height="14pt" width="auto" /></a></td>';
        s += '<td>' + subGroupRows[i].Grp.replace(/;/g, '; ') + '</td>';
        s += '<td>' + subGroupRows[i].SubGroup.replace(/;/g, '; ') + '</td>';
        s += '<td>' + subGroupRows[i].TotalRuns + '</td>';
        s += '<td>' + subGroupRows[i].ProcessedRuns + '</td>';
        s += '<td>' + subGroupRows[i].Biome.replace(/;/g, '; ') + '</td>';
        s += '<td>' + subGroupRows[i].AssayType.replace(/;/g, '; ') + '</td>';
        s += '<td>' + subGroupRows[i].LibraryLayout.replace(/;/g, '; ') + '</td>';
        s += '</tr>';
    }
    s += '</table>';

    return s;
}

function getAllIsolationSourceWiseResultHTML(divId) {
    var s = '<table class="browse-result-summary" border="1"><tr><th>BioProject ID</th><th>Disease</th><th>Sub-group</th><th>Total Runs</th><th>Processed Runs</th><th>Biome</th><th>Assay Type</th><th>Library Layout</th></tr>';
    for(var i=0; i<isolationSourceRows.length; ++i) {
        s += '<tr>';
        s += '<td><a style="color:#003325;" target="_blank" href="bioproject_id.php?key=' + isolationSourceRows[i].BioProject + '">' + isolationSourceRows[i].BioProject + ' <img src="resource/redirect-icon.png" height="14pt" width="auto" /></a></td>';
        s += '<td>' + isolationSourceRows[i].Grp.replace(/;/g, '; ') + '</td>';
        s += '<td>' + isolationSourceRows[i].SubGroup.replace(/;/g, '; ') + '</td>';
        s += '<td>' + isolationSourceRows[i].TotalRuns + '</td>';
        s += '<td>' + isolationSourceRows[i].ProcessedRuns + '</td>';
        s += '<td>' + isolationSourceRows[i].Biome.replace(/;/g, '; ') + '</td>';
        s += '<td>' + isolationSourceRows[i].AssayType.replace(/;/g, '; ') + '</td>';
        s += '<td>' + isolationSourceRows[i].LibraryLayout.replace(/;/g, '; ') + '</td>';
        s += '</tr>';
    }
    s += '</table>';

    return s;
}

function filter_diseases(resultDivId) {
    var hideButton = '<center><button type="button" class="round" style="margin:5px;" onclick="hideDiv(\'' + resultDivId + '\')">&#10005;</button></center>';
    var resultElement = document.getElementById(resultDivId);
    resultElement.style.display = 'block';
    if(document.getElementById('filter_cb') != null && document.getElementById('filter_cb').checked == true) {
        var filterCheckbox = '<div style="margin:10px 0 0 10px;"><input type="checkbox" id="filter_cb" onclick="filter_diseases(\'' + resultDivId + '\')" checked /><label>&nbsp;<b><u>Show only BioProjects with Healthy/Control</u></b></label></div>';
        var resultCountString = '<p style="margin:2px;text-align:center;">Total number of BioProjects found in the database for Disease = "' + disease + '" with Healthy/Control groups : ' + healthyControlRows.size + '</p>';
        var diseaseResultHTML = getFilteredDiseaseWiseResultHTML('result_display');
        if(healthyControlRows.size <= 0)
            resultElement.innerHTML = hideButton + filterCheckbox + resultCountString + hideButton;
        else
            resultElement.innerHTML = hideButton + filterCheckbox + resultCountString + diseaseResultHTML + hideButton;
    } else {
        var filterCheckbox = '<div style="margin:10px 0 0 10px;"><input type="checkbox" id="filter_cb" onclick="filter_diseases(\'' + resultDivId + '\')" /><label>&nbsp;<b><u>Show only BioProjects with Healthy/Control</u></b></label></div>';
        var resultCountString = '<p style="margin:2px;text-align:center;">Total number of BioProjects found in the database for Disease = "' + disease + '" : ' + rows.length + '</p>';
        var diseaseResultHTML = getAllDiseasewiseResultHTML('result_display');
        if(rows.length <= 0)
            resultElement.innerHTML = hideButton + filterCheckbox + resultCountString + hideButton;
        else
            resultElement.innerHTML = hideButton + filterCheckbox + resultCountString + diseaseResultHTML + hideButton;
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
    httpReq.open('GET', 'get_bioprojects.php?key=' + encodeURIComponent(dis) + '&keyType=ds', true);
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
    httpReq.open('GET', 'get_bioprojects.php?key=' + encodeURIComponent(sg) + '&keyType=sg', true);
    httpReq.setRequestHeader("Content-type", "text/json");
    httpReq.send();
}

function getIsolationSourceBioProjects(is, resultDivId){
    var httpReq = new XMLHttpRequest();
    httpReq.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            initializeIsolationSourceWiseData(this.responseText, is);
            var resultElement = document.getElementById(resultDivId);
            resultElement.style.display = 'block';
            var hideButton = '<center><button type="button" class="round" onclick="hideDiv(\'' + resultDivId + '\')">&#10005;</button></center>';
            var resultCountString = '<p style="margin:2px;text-align:center;">Total number of BioProjects found in the database for IsolationSource = "' + is + '" : ' + isolationSourceRows.length + '</p>';
            var isolationSourceResultHTML = getAllIsolationSourceWiseResultHTML('result_display');
            if(isolationSourceRows.length <= 0)
                resultElement.innerHTML = hideButton + resultCountString;
            else
                resultElement.innerHTML = hideButton + resultCountString + isolationSourceResultHTML;
        }
    };
    httpReq.open('GET', 'get_bioprojects.php?key=' + encodeURIComponent(is) + '&keyType=is', true);
    httpReq.setRequestHeader("Content-type", "text/json");
    httpReq.send();
}
