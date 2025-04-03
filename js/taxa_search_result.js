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

    var s = '<table class="summary" border="1"><tr><th>Taxa</th><th>NCBI Taxa ID</th><th>Taxa Domain</th><th>Taxa Level</th></tr>';

    for(var i=rowStart; i<rowEnd; ++i) {
        s += '<tr>';
        s += '<td><a style="color:#003325; font-weight:bold;" target="_blank" href="taxa.php?key=' + rows[i].Taxa + '">' + rows[i].Taxa + ' <img src="resource/redirect-icon.png" height="14pt" width="auto" /></a></td>';
        s += '<td><a style="color:#003325; font-weight:bold;" target="_blank" href="https://www.ncbi.nlm.nih.gov/Taxonomy/Browser/wwwtax.cgi?mode=Info&id=' + rows[i].NCBITaxaID + '">'+rows[i].NCBITaxaID+' <img src="resource/redirect-icon.png" height="14pt" width="auto" /></a></td>';
        s += '<td>' + rows[i].Domain + '</td>';
        s += '<td>' + rows[i].TaxaLevel + '</td>';
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
