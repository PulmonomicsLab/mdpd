var rows = null;

function hideDiv(divId){
    document.getElementById(divId).style.display = 'none';
}

function initializeData(dataJSON) {
    rows = JSON.parse(dataJSON);
}

function getDiseasewiseResultHTML(divId) {
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

function getBioProjects(disease, resultDivId){
    var httpReq = new XMLHttpRequest();
    httpReq.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            initializeData(this.responseText)
            var hideButton = '<center><button type="button" class="round" onclick="hideDiv(\'disease-wise-results\')">&#10005;</button></center>';
            var resultCountString = '<p style="margin:2px;text-align:center;">Total number of BioProjects found in the database for Disease = "' + disease + '" : ' + rows.length + '</p>';
            var resultElement = document.getElementById(resultDivId);
            resultElement.style.display = 'block';
            if(rows.length <= 0)
                resultElement.innerHTML = hideButton + resultCountString;
            else
                resultElement.innerHTML = hideButton + resultCountString + getDiseasewiseResultHTML('result_display');
        }
    };
    httpReq.open('GET', 'disease_wise_bioprojects.php?key='+ encodeURIComponent(disease), true);
    httpReq.setRequestHeader("Content-type", "text/json");
    httpReq.send();
}

function showDiseasePairResults(resultId, htmlDivId){
    var hideButton = '<center><button type="button" class="round" onclick="hideDiv(\''+resultId+'\')">&#10005;</button></center>';
    document.getElementById(resultId).innerHTML = hideButton + document.getElementById(htmlDivId).innerHTML;
    document.getElementById(resultId).style.display = 'block';
    document.getElementById(resultId).scrollIntoView();
}
