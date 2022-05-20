var numPrintRows = 100;
var rows = null;
var currentPageNo = 1;

function initializeData(dataJSON) {
    rows = JSON.parse(dataJSON);
}

function display(divId, pageNo) {
    var totalPages = Math.ceil(rows.length / numPrintRows);
    var rowStart = numPrintRows * (pageNo - 1);
    if(rowStart + numPrintRows < rows.length)
        var rowEnd = rowStart + numPrintRows;
    else
        var rowEnd = rows.length;
    
    var s = '<table class="summary" style="//width:100%;" border="1"><tr><th>Run ID</th><th>Assay Type</th><th>BioProject ID</th><th>Country</th><th>Continent</th><th>Host Disease</th></tr>';
    
    for(var i=rowStart; i<rowEnd; ++i) {
        s += '<tr>';
        s += '<td><a target="_blank" href="run_id.php?key=' + rows[i].Run + '">' + rows[i].Run + '</a></td>';
        s += '<td>' + rows[i].AssayType + '</td>';
        s += '<td><a target="_blank" href="bioproject_id.php?key=' + rows[i].BioProject + '">' + rows[i].BioProject + '</td>';
        s += '<td>' + rows[i].geo_loc_name_country + '</td>';
        s += '<td>' + rows[i].geo_loc_name_country_continent + '</td>';
        s += '<td>' + rows[i].HostDisease + '</td>';
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
