var keyToTypeMap = {
    'HostDisease': 'S',
    'AssayType': 'C',
    'Country': 'C',
    'Continent': 'C'
};
var keyToValueMap = {
    'AssayType': [['0', 'Amplicon'], ['1', 'WGS']],
    'Country': [['0', 'Australia'], ['1', 'Canada'], ['2', 'China'], ['3', 'Denmark'], ['4', 'France'], ['5', 'Germany'], ['6', 'Hong Kong'], ['7', 'Hungary'], ['8', 'India'], ['9', 'Italy'], ['10', 'Japan'], ['11', 'Korea'], ['12', 'Mali'], ['13', 'Morocco'], ['14', 'Netherlands'], ['15', 'Taiwan'], ['16', 'UK'], ['17', 'USA']],
    'Continent': [['0', 'Africa'], ['1', 'Asia'], ['2', 'Europe'], ['3', 'North America'], ['4', 'Oceania']]
};

function getOperatorHTML(keyType) {
    var operatorHTML = '<select class="full" id="op0" name="op0"><option value="eq" selected>=</option><option value="ne">&ne;</option>';
    if (keyType == 'S')
        operatorHTML = operatorHTML + '</select>';
    else if (keyType == 'C')
        operatorHTML = operatorHTML + '</select>';
    else if (keyType == 'N') {
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
                '<option value="HostDisease" selected>Host Disease</option>' +
                '<option value="AssayType">Assay Type</option>' +
                '<option value="Country">Country</option>' +
                '<option value="Continent">Continent</option>' +
            '</select>' +
        '</td>';
    var operatorHTML =
        '<td style="width:10%;">' + getOperatorHTML('N') + '</td>';
    var valueHTML = '<td style="width:10%;">' + getValueHTML('N', 'Age') + '</td>';
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
