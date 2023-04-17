var keyToTypeMap = {
    'Disease': 'C',
    'AssayType': 'C',
    'Country': 'C',
    'Instrument': 'C',
    'Year': 'Y',
    'IsolationSource': 'C'
};
var keyToValueMap = {
    'AssayType': [['0', 'Amplicon'], ['1', 'WMS']],
    
    'Country': [['0', 'Australia'], ['1', 'Bangladesh'], ['2', 'Belgium'], ['3', 'Brazil'],  
                ['4', 'China'], ['5', 'Czech Republic'], ['6', 'Germany'], ['7', 'Hungary'], 
                ['8', 'India'], ['9', 'Italy'], ['10', 'Japan'], ['11', 'Mali'], ['12', 'Morocco'], 
                ['13', 'Nepal'], ['14', 'Netherlands'], ['15', 'Peru'], ['16', 'Poland'], 
                ['17', 'Russia'], ['18', 'South Africa'], ['19', 'South Korea'], ['20', 'Spain'], 
                ['21', 'Srilanka'], ['22', 'Switzerland'], ['23', 'Taiwan'], ['24', 'United Kingdom'], 
                ['25', 'USA']],
    
    'Instrument': [['0', 'HiSeq 2000'], ['1', 'HiSeq 2500'], ['2', 'HiSeq 4000'],
                    ['3', 'MiSeq'], ['4', 'NovaSeq 6000'], ['5', 'NextSeq 500'], 
                    ['6', 'NextSeq 550']],
    
    'IsolationSource': [['0', 'BAL'], ['1', 'Bronchial Brush'], ['2', 'Bronchial Mucosa'], ['3', 'Colon Mucus'], 
                        ['4', 'Endotracheal Aspirate'], ['5', 'Lung Biopsy'], ['6', 'Lung Tissue'],
                        ['7', 'Lung Tumour Tissue'], ['8', 'Sputum'], ['9', 'Stool'], ['10', 'Supraglottic Swab']],
    
    'Disease': [['0', 'Asthma'], ['1', 'COPD'], ['2', 'COVID-19'], ['3', 'Cystic Fibrosis'], ['4', 'Lung cancer'], 
                ['5', 'Pneumonia'], ['6', 'Tuberculosis']]
};

function getOperatorHTML(keyType) {
    var operatorHTML = '<select class="full" id="op0" name="op0"><option value="eq" selected>=</option><option value="ne">&ne;</option>';
    if (keyType == 'S')
        operatorHTML = operatorHTML + '</select>';
    else if (keyType == 'C')
        operatorHTML = operatorHTML + '</select>';
    else if (keyType == 'N' || keyType == 'Y') {
        operatorHTML = operatorHTML +
            '<option value="lt">&lt;</option>' +
            '<option value="lte">&lt;=</option>' +
            '<option value="gt">&gt;</option>' +
            '<option value="gte">&gt;=</option>' +
        '</select>';
    }
    return operatorHTML;
}

function getValueHTML(keyType, keyValue) {
    if (keyType == 'S')
        valueHTML = '<input type="text" class="full" placeholder="Enter text">';
    else if (keyType == 'N')
        valueHTML = '<input type="number" step="0.01" class="full" placeholder="Enter number">';
    else if (keyType == 'Y')
        valueHTML = '<input type="number" min="1900", max="'+ (new Date()).getFullYear() +'" step="1" class="full" placeholder="YYYY">';
    else if (keyType == 'C') {
        valueHTML = '<select class="full">';
        possibleValues = keyToValueMap[keyValue];
        for (v of possibleValues)
            valueHTML = valueHTML + '<option value="' + v[0] + '">' + v[1] + '</option>';
        valueHTML = valueHTML + '</select>';
    }
    return valueHTML;
}

function getHTML() {
    var checkboxHTML = '<td style="width:10%;"><input class="full" type="checkbox" /></td>';
    var logicalOperatorHTML =
        '<td style="width:10%;">' +
            '<select class="full">' +
                '<option value="AND" selected>AND</option>' +
                '<option value="OR">OR</option>' +
            '</select>' +
        '</td>';
    var keyHTML =
        '<td style="width:30%;">' +
            '<select class="full"  onchange="updateKeyChoice(this)">' +
                '<option value="Disease" selected>Disease</option>' + 
                '<option value="AssayType">Assay Type</option>' + 
                '<option value="Country">Country</option>' + 
                '<option value="Instrument">Instrument</option>' + 
                '<option value="Year">Year</option>' + 
                '<option value="IsolationSource">Isolation source</option>' + 
            '</select>' +
        '</td>';
    var operatorHTML =
        '<td style="width:10%;">' + getOperatorHTML('C') + '</td>';
    var valueHTML = '<td style="width:10%;">' + getValueHTML('C', 'Disease') + '</td>';
	return checkboxHTML + logicalOperatorHTML + keyHTML + operatorHTML + valueHTML;
}

function updateKeyChoice(selectElement){
    var keyValue = selectElement.value;
    var operatorColElement = selectElement.parentElement.nextElementSibling;
    var valueColElement = operatorColElement.nextElementSibling;
    operatorColElement.innerHTML = getOperatorHTML(keyToTypeMap[keyValue]);
    valueColElement.innerHTML = getValueHTML(keyToTypeMap[keyValue], keyValue);
    nameRows();
}

function addRow() {
	var tableElement = document.getElementById('form_input_table');
	var rowElement = document.createElement("tr");
	rowElement.innerHTML = getHTML();
	rowElement.classList.add('input_row');
	tableElement.appendChild(rowElement);

	(document.getElementById('total_count').value)++;
	nameRows();

}

function deleteRow() {
	var allRows = document.getElementsByClassName('input_row');
	var selectedRows = [];

	for(var i=1; i<allRows.length; ++i)
		if(allRows[i].firstChild.firstChild.checked == true)
			selectedRows.push(allRows[i]);

	for(var i=0; i<selectedRows.length; ++i) {
		document.getElementById('form_input_table').removeChild(selectedRows[i]);
		(document.getElementById('total_count').value)--;
	}
	nameRows();
}

function nameRows() {
	var allRows = document.getElementsByClassName('input_row');

	allRows[0].children[3].firstElementChild.name = 'op0';
	allRows[0].children[4].firstElementChild.name = 'v0';
    allRows[0].children[3].firstElementChild.id = 'op0';
    allRows[0].children[4].firstElementChild.id = 'v0';

	for(var i=1; i<allRows.length; ++i) {
		var row = allRows[i];
		//var input = row.childNodes[j].firstChild;
		row.children[0].firstElementChild.name = 'c' + i;
		row.children[1].firstElementChild.name = 'lo' + i;
		row.children[2].firstElementChild.name = 'k' + i;
		row.children[3].firstElementChild.name = 'op' + i;
		row.children[4].firstElementChild.name = 'v' + i;

		row.children[0].firstElementChild.id = 'c' + i;
		row.children[1].firstElementChild.id = 'lo' + i;
		row.children[2].firstElementChild.id = 'k' + i;
		row.children[3].firstElementChild.id = 'op' + i;
		row.children[4].firstElementChild.id = 'v' + i;
	}
//	var txt = ''
//	for(var i=1; i<allRows.length; ++i)
//	{
//		var row = allRows[i];
//		txt = txt+
//		    row.children[0].firstElementChild.name+'->'+row.children[0].firstElementChild.value+'\n'+
//			row.children[1].firstElementChild.name+'->'+row.children[1].firstElementChild.value+'\n'+
//			row.children[2].firstElementChild.name+'->'+row.children[2].firstElementChild.value+'\n'+
//			row.children[3].firstElementChild.name+'->'+row.children[3].firstElementChild.value+'\n'+
//			row.children[4].firstElementChild.name+'->'+row.children[4].firstElementChild.value+'\n\n' ;
//	}
//	alert(txt);
}
