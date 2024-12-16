var numPrintRows = 100;
var rows = null;
var currentPageNo = 1;

function initializeData(dataJSON) {
    rows = JSON.parse(dataJSON);
}

function createDownloadLink() {
    if(rows.length < 1)
        return;
    var totalPages = Math.ceil(rows.length / numPrintRows);
    var rowStart = numPrintRows * (currentPageNo - 1);
    if(rowStart + numPrintRows < rows.length)
        var rowEnd = rowStart + numPrintRows;
    else
        var rowEnd = rows.length;
    var s = 'Run ID\tBioProject ID\tDisease\tDisease Sub-group\tBiome\tInstrument\tAssay Type\tLibrary Layout\tProcessed Reads\tCountry\tYear\n';
    for(var i=rowStart; i<rowEnd; ++i) {
        s += rows[i].Run + '\t';
        s += rows[i].BioProject + '\t';
        s += rows[i].Grp + '\t';
        s += rows[i].SubGroup + '\t';
        s += rows[i].Biome + '\t';
        s += rows[i].Instrument + '\t';
        s += rows[i].AssayType + '\t';
        s += rows[i].LibraryLayout + '\t';
        s += rows[i].ProcessedRuns + '\t';
        s += rows[i].Country + '\t';
        s += rows[i].Year + '\n';
    }
    var blob = new Blob([s], {type: 'text/csv;charset=utf-8;'});
    var link = document.getElementById('download_button');
    link.href = URL.createObjectURL(blob);
}

function display(divId, pageNo) {
    var totalPages = Math.ceil(rows.length / numPrintRows);
    var rowStart = numPrintRows * (pageNo - 1);
    if(rowStart + numPrintRows < rows.length)
        var rowEnd = rowStart + numPrintRows;
    else
        var rowEnd = rows.length;
    
    var s = '<table class="summary" border="1"><tr><th>Run ID</th><th>BioProject ID</th><th>Disease</th><th>Disease Sub-group</th><th>Biome</th><th>Instrument</th><th>Assay Type</th><th>Library Layout</th><th>Processed Reads</th><th>Country</th><th>Year</th></tr>';
    
    for(var i=rowStart; i<rowEnd; ++i) {
        s += '<tr>';
        s += '<td><a style="color:#003325; font-weight:bold;" target="_blank" href="run_id.php?key=' + rows[i].Run + '">' + rows[i].Run + ' <img src="resource/redirect-icon.png" height="14pt" width="auto" /></a></td>';
        s += '<td><a style="color:#003325; font-weight:bold;" target="_blank" href="bioproject_id.php?key=' + rows[i].BioProject + '">'+rows[i].BioProject+' <img src="resource/redirect-icon.png" height="14pt" width="auto" /></a></td>';
        s += '<td>' + rows[i].Grp + '</td>';
        s += '<td>' + rows[i].SubGroup + '</td>';
        s += '<td>' + rows[i].Biome + '</td>';
        s += '<td>' + rows[i].Instrument + '</td>';
        s += '<td>' + rows[i].AssayType + '</td>';
        s += '<td>' + rows[i].LibraryLayout + '</td>';
        s += '<td>' + rows[i].ProcessedRuns + '</td>';
        s += '<td>' + rows[i].Country + '</td>';
        s += '<td>' + rows[i].Year + '</td>';
        s += '</tr>';
    }
    
    s += '</table>';
    
    document.getElementById(divId).innerHTML = s;
    document.getElementById('pages_top').innerHTML = 'Page ' + pageNo + ' / ' + totalPages;
    document.getElementById('pages_bottom').innerHTML = 'Page ' + pageNo + ' / ' + totalPages;
    currentPageNo = pageNo;
}

function displayFirstPage(divId) {
    display(divId, 1);
}

function displayLastPage(divId) {
    var totalPages = Math.ceil(rows.length / numPrintRows);
    display(divId, totalPages);
}

function displayNextPage(divId) {
    var totalPages = Math.ceil(rows.length / numPrintRows);
    if (currentPageNo < totalPages)
        display(divId, currentPageNo + 1);
}

function displayPrevPage(divId) {
    if (currentPageNo > 1)
        display(divId, currentPageNo - 1);
}

function goto_page(divId, pageNoId) {
    var totalPages = Math.ceil(rows.length / numPrintRows);
    var pageNo = parseInt(document.getElementById(pageNoId).value);
    if (pageNo >= 1 && pageNo <= totalPages)
        display(divId, pageNo);
}
