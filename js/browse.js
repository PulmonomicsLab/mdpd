var rows = null;
var healthyControlFilterRows = null;
var assayTypeFilterRows = null;
var libLayoutFilterRows = null;
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

    healthyControlFilterRows = new Set();

    assayTypeFilterRows = new Map();
    assayTypeFilterRows.set('Amplicon-16S', new Set());
    assayTypeFilterRows.set('Amplicon-ITS', new Set());
    assayTypeFilterRows.set('WMS', new Set());

    libLayoutFilterRows = new Map();
    libLayoutFilterRows.set('PAIRED', new Set());
    libLayoutFilterRows.set('SINGLE', new Set());

    for(var i=0; i<rows.length; ++i) {
        var groups = rows[i].Grp.split(';');
        for(var j=0; j<groups.length; ++j)
            if(groups[j] == 'Healthy' || groups[j] == 'Control')
                healthyControlFilterRows.add(i);

        var assayTypes = rows[i].AssayType.split(';');
        for(var j=0; j<assayTypes.length; ++j)
            assayTypeFilterRows.get(assayTypes[j]).add(i);

        var libLayouts = rows[i].LibraryLayout.split(';');
        for(var j=0; j<libLayouts.length; ++j)
            libLayoutFilterRows.get(libLayouts[j]).add(i);
    }
}

function initializeSubGroupWiseData(dataJSON, sg) {
    subGroupRows = JSON.parse(dataJSON);
    subGroup = sg;
}

function initializeIsolationSourceWiseData(dataJSON, is) {
    isolationSourceRows = JSON.parse(dataJSON);
    isolationSource = is;
}

function getDiseaseWiseResultHTML(filterRows) {
    var s = '<table class="browse-result-summary" border="1"><tr><th>BioProject ID</th><th>Disease</th><th>Sub-group</th><th>Total Runs</th><th>Processed Runs</th><th>Biome</th><th>Assay Type</th><th>Library Layout</th></tr>';
    for(i of filterRows.values()) {
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

function getFilterRows(healthyFilter, assayTypeFilter, libLayoutFilter) {
    var filterRows = new Set([...Array(rows.length).keys()]);
    if(healthyFilter)
        for(var i=0; i<rows.length; ++i)
            if(!healthyControlFilterRows.has(i))
                filterRows.delete(i);
    if(assayTypeFilter != 'Any')
        for(var i=0; i<rows.length; ++i)
            if (!assayTypeFilterRows.get(assayTypeFilter).has(i))
                filterRows.delete(i);
    if(libLayoutFilter != 'Any')
        for(var i=0; i<rows.length; ++i)
            if(!libLayoutFilterRows.get(libLayoutFilter).has(i))
                filterRows.delete(i);
    return filterRows;
}

function filter_diseases(resultDivId) {
    var hideButton = '<center><button type="button" class="round" style="margin:5px;" onclick="hideDiv(\'' + resultDivId + '\')">&#10005;</button></center>';
    var resultElement = document.getElementById(resultDivId);
    resultElement.style.display = 'block';
    var healthyFilter = (document.getElementById('filter_cb') != null && document.getElementById('filter_cb').checked == true);
    var assayTypeFilter = (document.getElementById('filter_at_Any') != null) ? document.querySelector('input[name="filter_at"]:checked').value : 'Any';
    var libLayoutFilter = (document.getElementById('filter_ll_Any') != null) ? document.querySelector('input[name="filter_ll"]:checked').value : 'Any';

    var filterRows = getFilterRows(healthyFilter, assayTypeFilter, libLayoutFilter);
    var diseaseResultHTML = getDiseaseWiseResultHTML(filterRows);

    var healthyCheckbox = '<input type="checkbox" id="filter_cb" onclick="filter_diseases(\'' + resultDivId + '\')" /><label for="filter_cb">&nbsp;<b>BioProjects with Healthy/Control</b></label>';
    var assayTypeButtons =
        '<input type="radio" style="margin-left:5px;" id="filter_at_Any" name="filter_at" value="Any" onclick="filter_diseases(\'' + resultDivId + '\')" /><label for="filter_at_Any"><b>Any</b></label>' +
        '<input type="radio" style="margin-left:5px;" id="filter_at_Amplicon-16S" name="filter_at" value="Amplicon-16S" onclick="filter_diseases(\'' + resultDivId + '\')" /><label for="filter_at_Amplicon-16S"><b>Amplicon-16S</b></label>' +
        '<input type="radio" style="margin-left:5px;" id="filter_at_Amplicon-ITS" name="filter_at" value="Amplicon-ITS" onclick="filter_diseases(\'' + resultDivId + '\')" /><label for="filter_at_Amplicon-ITS"><b>Amplicon-ITS</b></label>' +
        '<input type="radio" style="margin-left:5px;" id="filter_at_WMS" name="filter_at" value="WMS" onclick="filter_diseases(\'' + resultDivId + '\')" /><label for="filter_at_WMS"><b>WMS</b></label>';
    var libLayoutButtons =
        '<input type="radio" style="margin-left:5px;" id="filter_ll_Any" name="filter_ll" value="Any" onclick="filter_diseases(\'' + resultDivId + '\')" /><label for="filter_ll_Any"><b>Any</b></label>' +
        '<input type="radio" style="margin-left:5px;" id="filter_ll_PAIRED" name="filter_ll" value="PAIRED" onclick="filter_diseases(\'' + resultDivId + '\')" /><label for="filter_ll_PAIRED"><b>PAIRED</b></label>' +
        '<input type="radio" style="margin-left:5px;" id="filter_ll_SINGLE" name="filter_ll" value="SINGLE" onclick="filter_diseases(\'' + resultDivId + '\')" /><label for="filter_ll_SINGLE"><b>SINGLE</b></label>';
    var filterHTML =
        '<table style="width:98%; margin:10px 1% 10px 1%; background-color:#ffe799;">' +
            '<tr>' +
                '<td>' + healthyCheckbox + '</td>' +
                '<td>Assay Types: ' + assayTypeButtons + '</td>' +
                '<td>Library Layouts: ' + libLayoutButtons + '</td>' +
            '</tr>' +
        '</table>';

    var resultCountString = '<p style="margin:2px;text-align:center;">Total number of BioProjects found in the database for Disease = "' + disease + '" with provided filters : ' + filterRows.size + '</p>';

    if(filterRows.size <= 0)
        resultElement.innerHTML = hideButton + filterHTML + resultCountString + hideButton;
    else
        resultElement.innerHTML = hideButton + filterHTML + resultCountString + diseaseResultHTML + hideButton;

    document.getElementById('filter_cb').checked = (healthyFilter) ? true : false;
    document.getElementById('filter_at_'+assayTypeFilter).checked = true;
    document.getElementById('filter_ll_'+libLayoutFilter).checked = true;
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
            resultElement.scrollIntoView({behavior: 'smooth'});
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
            resultElement.scrollIntoView({behavior: 'smooth'});
        }
    };
    httpReq.open('GET', 'get_bioprojects.php?key=' + encodeURIComponent(is) + '&keyType=is', true);
    httpReq.setRequestHeader("Content-type", "text/json");
    httpReq.send();
}
