var keyToTypeMap = {
    'Disease': 'C',
    'AssayType': 'C',
    'Biome': 'C',
    'LibraryLayout': 'C',
    'Country': 'C',
    'Year': 'Y'
};
var keyToValueMap = {
    'Disease': [['0', 'Acute Respiratory Distress Syndrome (ARDS)'], ['1', 'Asthma'], ['2', 'Asthma-COPD Overlap (ACO)'],
                ['3', 'Bronchiectasis'], ['4', 'Bronchiolitis'], ['5', 'Bronchitis'], ['6', 'Chronic Obstructive Pulmonary Disease (COPD)'],
                ['7', 'COPD-Bronchiectasis Association (CBA)'], ['8', 'COVID-19'], ['9', 'Cystic Fibrosis'], ['10', 'Healthy'],
                ['11', 'Idiopathic Pulmonary Fibrosis (IPF)'], ['12', 'Interstitial Lung Disease (ILD)'], ['13', 'Lung Cancer'],
                ['14', 'Other Pulmonary Infections'], ['15', 'Pneumonia'], ['16', 'Pneumonitis'], ['17', 'Pulmonary Hypertension'],
                ['18', 'Sarcoidosis'], ['19', 'Tuberculosis']],

    'AssayType': [['0', 'Amplicon-16S'], ['1', 'Amplicon-ITS'], ['2', 'WMS']],

    'Biome': [['0', 'Anus'], ['1', 'Gut'], ['2', 'Large Intestine'], ['3', 'Lower Respiratory Tract'], ['4', 'Lung'],
                ['5', 'Nasal'], ['6', 'Oral'], ['7', 'Rectum'], ['8', 'Stomach'], ['9', 'Upper Respiratory Tract']],

    'LibraryLayout': [['0', 'PAIRED'], ['1', 'SINGLE']],
    
    'Country': [['0', 'Argentina'], ['1', 'Australia'], ['2', 'Austria'], ['3', 'Bangladesh'], ['4', 'Belgium'], ['5', 'Brazil'],
                ['6', 'Canada'], ['7', 'Chile'], ['8', 'China'], ['9', 'Colombia'], ['10', 'Czechia'], ['11', 'Denmark'], ['12', 'Egypt'],
                ['13', 'Ethiopia'], ['14', 'France'], ['15', 'Gambia'], ['16', 'Germany'], ['17', 'Ghana'], ['18', 'Greece'], ['19', 'Hong Kong'],
                ['20', 'Hungary'], ['21', 'India'], ['22', 'Ireland'], ['23', 'Israel'], ['24', 'Italy'], ['25', 'Japan'], ['26', 'Jordan'],
                ['27', 'Kuwait'], ['28', 'Kyrgyzstan'], ['29', 'Luxembourg'], ['30', 'Malaysia'], ['31', 'Mali'], ['32', 'Mexico'],
                ['33', 'Morocco'], ['34', 'Nepal'], ['35', 'Netherlands'], ['36', 'New Zealand'], ['37', 'Norway'], ['38', 'Panama'],
                ['39', 'Peru'], ['40', 'Poland'], ['41', 'Portugal'], ['42', 'Russia'], ['43', 'Singapore'], ['44', 'South Africa'],
                ['45', 'South Korea'], ['46', 'Spain'], ['47', 'Sri Lanka'], ['48', 'Sweden'], ['49', 'Switzerland'], ['50', 'Taiwan'],
                ['51', 'Turkey'], ['52', 'Uganda'], ['53', 'United Kingdom'], ['54', 'USA']]
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
    else if (keyType == 'Y') {
        valueHTML = '<select class="full">';
        for (var i = 2012; i <= 2024; ++i)
            valueHTML = valueHTML + '<option value="' + i + '">' + i + '</option>';
        valueHTML = valueHTML + '</select>';
    } else if (keyType == 'C') {
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
                '<option value="Biome">Body site</option>' +
                '<option value="LibraryLayout">Library Layout</option>' +
                '<option value="Country">Country</option>' + 
                '<option value="Year">Year</option>' + 
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
