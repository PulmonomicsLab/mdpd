groupSubGroupMap = null;
groupIsolationSourceMap = null;
checkedIsolationSources = new Set(['Sputum', 'BALF', 'Stool']);

function initializeMaps(groupSubGroupMapString, groupIsolationSourceMapString) {
    groupSubGroupMap = JSON.parse(groupSubGroupMapString);
    groupIsolationSourceMap = JSON.parse(groupIsolationSourceMapString);
}

function hideDiv(divId){
    document.getElementById(divId).style.display = 'none';
}

function getTPBioProjects(resultDivId) {
    var disease = document.querySelectorAll('input[name=taxonomic_ds]:checked')[0].value;
    var assayType = document.querySelectorAll('input[name=taxonomic_at]:checked')[0].value;
    var subGroups = new Array();
    for (var sgElement of document.querySelectorAll('.taxonomic_sg_class:checked'))
        subGroups.push(sgElement.value);
    var isolationSources = new Array();
    for (var isElement of document.querySelectorAll('.taxonomic_is_class:checked'))
        isolationSources.push(isElement.value);
    var libraryLayouts = new Array();
    for (var libElement of document.querySelectorAll('.taxonomic_lib_class:checked'))
        libraryLayouts.push(libElement.value);
//     alert('[' + disease + ']' + '[' + subGroups + ']' + '[' + isolationSources + ']' + '[' + assayType + ']' + '[' + libraryLayouts + ']');

    if (!validateTaxonomicForm(false))
        return;

    var httpReq = new XMLHttpRequest();
    httpReq.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var bioprojects = JSON.parse(this.responseText)

            var hideButton = '<center><button type="button" class="round" onclick="hideDiv(\'' + resultDivId + '\')">&#10005;</button></center>';
            var resultCountString = '<p style="margin:2px;text-align:center;">Total number of BioProjects found in the database: ' + bioprojects.length + '</p>';

            var s = '<table class="browse-result-summary" border="1"><tr><th>Select</th><th>BioProject ID</th><th>Group</th><th>Sub-group</th><th>Isolation Source</th><th>Biome</th><th>Assay Type</th><th>Library Layout</th></tr>';
            for(var i=0; i<bioprojects.length; ++i) {
                s += '<tr>';
                s += '<td><input type="checkbox" class="taxonomic_bp_class" id="tp_bp_' + bioprojects[i].BioProject + '" name="taxonomic_bp[]" value="' + bioprojects[i].BioProject + '" checked></td>';
                s += '<td><a style="color:#003325;" target="_blank" href="bioproject_id.php?key=' + bioprojects[i].BioProject + '">' + bioprojects[i].BioProject + ' <img src="resource/redirect-icon.png" height="14pt" width="auto" /></a></td>';
                s += '<td>' + bioprojects[i].Grp.replace(/;/g, '; ') + '</td>';
                s += '<td>' + bioprojects[i].SubGroup.replace(/;/g, '; ') + '</td>';
                s += '<td>' + bioprojects[i].IsolationSource.replace(/;/g, '; ') + '</td>';
                s += '<td>' + bioprojects[i].Biome.replace(/;/g, '; ') + '</td>';
                s += '<td>' + bioprojects[i].AssayType.replace(/;/g, '; ') + '</td>';
                s += '<td>' + bioprojects[i].LibraryLayout.replace(/;/g, '; ') + '</td>';
                s += '</tr>';
            }
            s += '</table>';

            document.getElementById(resultDivId).innerHTML = (bioprojects.length <= 0) ? (hideButton + resultCountString) : (hideButton + resultCountString + s)
            document.getElementById(resultDivId).style.display = 'block';
        }
    };
    httpReq.open('POST', 'get_filtered_bioprojects.php', true);
    httpReq.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    httpReq.send('disease=' + encodeURIComponent(disease) + '&' + 'subGroups=' + encodeURIComponent(JSON.stringify(subGroups)) + '&' + 'isolationSources=' + encodeURIComponent(JSON.stringify(isolationSources)) + '&' + 'assayType=' + encodeURIComponent(assayType) + '&' + 'libraryLayout=' + encodeURIComponent(JSON.stringify(libraryLayouts)));
}

function getDABioProjects(resultDivId) {
    var disease1 = document.getElementById('discriminant_ds_1').value;
    var disease2 = document.getElementById('discriminant_ds_2').value;
    var assayType = document.querySelectorAll('input[name=discriminant_at]:checked')[0].value;
    var subGroups1 = new Array();
    for (var sgElement of document.querySelectorAll('.discriminant_sg_1_class:checked'))
        subGroups1.push(sgElement.value);
    var subGroups2 = new Array();
    for (var sgElement of document.querySelectorAll('.discriminant_sg_2_class:checked'))
        subGroups2.push(sgElement.value);
    var isolationSources1 = new Array();
    for (var isElement of document.querySelectorAll('.discriminant_is_1_class:checked'))
        isolationSources1.push(isElement.value);
    var isolationSources2 = new Array();
    for (var isElement of document.querySelectorAll('.discriminant_is_2_class:checked'))
        isolationSources2.push(isElement.value);
    var libraryLayouts = new Array();
    for (var libElement of document.querySelectorAll('.discriminant_lib_class:checked'))
        libraryLayouts.push(libElement.value);
//     alert('[' + disease + ']' + '[' + subGroups + ']' + '[' + isolationSources + ']' + '[' + assayType + ']' + '[' + libraryLayouts + ']');

    if (!validateDiscriminantForm(false))
        return;

    var bioprojects1 = [];
    var bioprojects2 = [];
    if (disease1 != "null") {
        var httpReq = new XMLHttpRequest();
        httpReq.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200)
                bioprojects1 = JSON.parse(this.responseText)
        };
        httpReq.open('POST', 'get_filtered_bioprojects.php', false);
        httpReq.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        httpReq.send('subGroups=' + encodeURIComponent(JSON.stringify(subGroups1)) + '&' + 'isolationSources=' + encodeURIComponent(JSON.stringify(isolationSources1)) + '&' + 'assayType=' + encodeURIComponent(assayType) + '&' + 'libraryLayout=' + encodeURIComponent(JSON.stringify(libraryLayouts)));
    }
    if (disease2 != "null") {
        var httpReq = new XMLHttpRequest();
        httpReq.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200)
                bioprojects2 = JSON.parse(this.responseText)
        };
        httpReq.open('POST', 'get_filtered_bioprojects.php', false);
        httpReq.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        httpReq.send('subGroups=' + encodeURIComponent(JSON.stringify(subGroups2)) + '&' + 'isolationSources=' + encodeURIComponent(JSON.stringify(isolationSources2)) + '&' + 'assayType=' + encodeURIComponent(assayType) + '&' + 'libraryLayout=' + encodeURIComponent(JSON.stringify(libraryLayouts)));
    }

    var s = '';
    if (disease1 != "null") {
        s += '<p style="margin:5px 0 0 20px;"><b>'+disease1+':</b></p><table class="browse-result-summary" border="1"><tr><th>Select</th><th>BioProject ID</th><th>Group</th><th>Sub-group</th><th>Isolation Source</th><th>Biome</th><th>Assay Type</th><th>Library Layout</th></tr>';
        for(var i=0; i<bioprojects1.length; ++i) {
            s += '<tr>';
            s += '<td><input type="checkbox" class="discriminant_bp_1_class" id="da_bp_' + bioprojects1[i].BioProject + '" name="discriminant_bp_1[]" value="' + bioprojects1[i].BioProject + '" checked></td>';
            s += '<td><a style="color:#003325;" target="_blank" href="bioproject_id.php?key=' + bioprojects1[i].BioProject + '">' + bioprojects1[i].BioProject + ' <img src="resource/redirect-icon.png" height="14pt" width="auto" /></a></td>';
            s += '<td>' + bioprojects1[i].Grp.replace(/;/g, '; ') + '</td>';
            s += '<td>' + bioprojects1[i].SubGroup.replace(/;/g, '; ') + '</td>';
            s += '<td>' + bioprojects1[i].IsolationSource.replace(/;/g, '; ') + '</td>';
            s += '<td>' + bioprojects1[i].Biome.replace(/;/g, '; ') + '</td>';
            s += '<td>' + bioprojects1[i].AssayType.replace(/;/g, '; ') + '</td>';
            s += '<td>' + bioprojects1[i].LibraryLayout.replace(/;/g, '; ') + '</td>';
            s += '</tr>';
        }
        s += '</table>';
    }
    if (disease2 != "null") {
        s += '<p style="margin:5px 0 0 20px;"><b>'+disease2+':</b></p><table class="browse-result-summary" border="1"><tr><th>Select</th><th>BioProject ID</th><th>Group</th><th>Sub-group</th><th>Isolation Source</th><th>Biome</th><th>Assay Type</th><th>Library Layout</th></tr>';
        for(var i=0; i<bioprojects2.length; ++i) {
            s += '<tr>';
            s += '<td><input type="checkbox" class="discriminant_bp_2_class" id="da_bp_' + bioprojects2[i].BioProject + '" name="discriminant_bp_2[]" value="' + bioprojects2[i].BioProject + '" checked></td>';
            s += '<td><a style="color:#003325;" target="_blank" href="bioproject_id.php?key=' + bioprojects2[i].BioProject + '">' + bioprojects2[i].BioProject + ' <img src="resource/redirect-icon.png" height="14pt" width="auto" /></a></td>';
            s += '<td>' + bioprojects2[i].Grp.replace(/;/g, '; ') + '</td>';
            s += '<td>' + bioprojects2[i].SubGroup.replace(/;/g, '; ') + '</td>';
            s += '<td>' + bioprojects2[i].IsolationSource.replace(/;/g, '; ') + '</td>';
            s += '<td>' + bioprojects2[i].Biome.replace(/;/g, '; ') + '</td>';
            s += '<td>' + bioprojects2[i].AssayType.replace(/;/g, '; ') + '</td>';
            s += '<td>' + bioprojects2[i].LibraryLayout.replace(/;/g, '; ') + '</td>';
            s += '</tr>';
        }
        s += '</table>';
    }

    var nBioProjects = bioprojects1.length + bioprojects2.length;
    var hideButton = '<center><button type="button" class="round" onclick="hideDiv(\'' + resultDivId + '\')">&#10005;</button></center>';
    var resultCountString = '<p style="margin:2px;text-align:center;">Total number of BioProjects found in the database: ' + nBioProjects + '</p>';
    document.getElementById(resultDivId).innerHTML = (nBioProjects <= 0) ? (hideButton + resultCountString) : (hideButton + resultCountString + s)
    document.getElementById(resultDivId).style.display = 'block';

    if (document.getElementById('taxa_level')) {
        if (document.querySelectorAll('input[name=discriminant_at]:checked')[0].value == 'WMS')
            document.getElementById('taxa_level').innerHTML = '<option value="Species" selected>Species</option><option value="Genus">Genus</option><option value="Family">Family</option><option value="Order">Order</option>';
        else
            document.getElementById('taxa_level').innerHTML = '<option value="Genus" selected>Genus</option><option value="Family">Family</option><option value="Order">Order</option>';
    }
}

function updateTPGroupOptions(disease, optionTableID, resultDivId) {
    var subGroups = groupSubGroupMap[disease];
    var isolationSources = groupIsolationSourceMap[disease];
    var s = '<tr><td class="row_heading" style="width:150px;">Sub-group</td><td class="even">';
    for(var i = 0; i < subGroups.length; ++i) {
        s += '<div style="float:left;">';
        if (i == 0)
            s += '<input type="checkbox" class="taxonomic_sg_class" style="margin-top:10px;float:left;" id="tp_sg_' + subGroups[i].replace(/ /g,"_") + '" name="taxonomic_sg[]" value="' + subGroups[i] + '" onclick="getTPBioProjects(\'' + resultDivId + '\')" checked>';
        else
            s += '<input type="checkbox" class="taxonomic_sg_class" style="margin-top:10px;float:left;" id="tp_sg_' + subGroups[i].replace(/ /g,"_") + '" name="taxonomic_sg[]" value="' + subGroups[i] + '" onclick="getTPBioProjects(\'' + resultDivId + '\')">';
        s += '<label for="tp_sg_' + subGroups[i].replace(/ /g,"_") + '" style="margin:5px 10px 5px 0;float:left;">' + subGroups[i] + '</label>';
        s += '</div>';
    }
    s += '</td></tr>';
    s += '<tr><td class="row_heading" style="width:150px;">Isolation Source</td><td class="odd">';
    for(var i = 0; i < isolationSources.length; ++i) {
        s += '<div style="float:left;">';
        if (checkedIsolationSources.has(isolationSources[i]))
            s += '<input type="checkbox" class="taxonomic_is_class" style="margin-top:10px;float:left;" id="tp_is_' + isolationSources[i].replace(/ /g,"_") + '" name="taxonomic_is[]" value="' + isolationSources[i] + '" onclick="getTPBioProjects(\'' + resultDivId + '\')" checked>';
        else
            s += '<input type="checkbox" class="taxonomic_is_class" style="margin-top:10px;float:left;" id="tp_is_' + isolationSources[i].replace(/ /g,"_") + '" name="taxonomic_is[]" value="' + isolationSources[i] + '" onclick="getTPBioProjects(\'' + resultDivId + '\')">';
        s += '<label for="tp_is_' + isolationSources[i].replace(/ /g,"_") + '" style="margin:5px 10px 5px 0;float:left;">' + isolationSources[i] + '</label>';
        s += '</div>';
    }
    s += '</td></tr>';
    document.getElementById(optionTableID).innerHTML = s;
    document.getElementById('browse-tp-other-options-div').style.display = 'block';
    getTPBioProjects(resultDivId);
}

function updateDAGroupOptions(resultDivId) {
    var disease1 = document.getElementById('discriminant_ds_1').value;
    var disease2 = document.getElementById('discriminant_ds_2').value;
    if (disease1 == "null" && disease2 == "null") {
        document.getElementById('browse-da-sg-options').innerHTML = '';
        document.getElementById('browse-da-sg-options-div').style.display = 'none';
        // document.getElementById('browse-da-other-options-div').innerHTML = '';
        document.getElementById('browse-da-other-options-div').style.display = 'none';
        document.getElementById(resultDivId).innerHTML = '';
        document.getElementById(resultDivId).style.display = 'none';
        return;
    }
    var subGroups1 = (disease1 != "null") ? groupSubGroupMap[disease1] : null;
    var subGroups2 = (disease2 != "null") ? groupSubGroupMap[disease2] : null;
    var isolationSources1 = (disease1 != "null") ? new Set(groupIsolationSourceMap[disease1]) : null;
    var isolationSources2 = (disease2 != "null") ? new Set(groupIsolationSourceMap[disease2]) : null;

    var s = '';
    if (subGroups1) {
        s += '<tr>';
        s += '<td class="row_heading" rowspan="2" style="width:150px;">' + disease1 + '</td>';
        s += '<td class="row_heading" style="max-width:150px;">Sub-group</td>';
        s += '<td class="even">';
        for(var i=0; i<subGroups1.length; ++i) {
            s += '<div style="float:left;">';
            if (i == 0)
                s += '<input type="checkbox" class="discriminant_sg_1_class" style="margin-top:10px;float:left;" id="da_sg_1_' + subGroups1[i].replace(/ /g,"_") + '" name="discriminant_sg_1[]" value="' + subGroups1[i] + '" onclick="getDABioProjects(\'' + resultDivId + '\')" checked>';
            else
                s += '<input type="checkbox" class="discriminant_sg_1_class" style="margin-top:10px;float:left;" id="da_sg_1_' + subGroups1[i].replace(/ /g,"_") + '" name="discriminant_sg_1[]" value="' + subGroups1[i] + '" onclick="getDABioProjects(\'' + resultDivId + '\')">';
            s += '<label for="da_sg_1_' + subGroups1[i].replace(/ /g,"_") + '" style="margin:5px 10px 5px 0;float:left;">' + subGroups1[i] + '</label>';
            s += '</div>';
        }
        s += '</td>';
        s += '</tr>';
        s += '<tr>';
        s += '<td class="row_heading" style="max-width:150px;">Isolation Source</td>';
        s += '<td class="odd">';
        for(const iss of isolationSources1) {
            s += '<div style="float:left;">';
            if (checkedIsolationSources.has(iss))
                s += '<input type="checkbox" class="discriminant_is_1_class" style="margin-top:10px;float:left;" id="da_is_1_' + iss.replace(/ /g,"_") + '" name="discriminant_is_1[]" value="' + iss + '" onclick="getDABioProjects(\'' + resultDivId + '\')" checked>';
            else
                s += '<input type="checkbox" class="discriminant_is_1_class" style="margin-top:10px;float:left;" id="da_is_1_' + iss.replace(/ /g,"_") + '" name="discriminant_is_1[]" value="' + iss + '" onclick="getDABioProjects(\'' + resultDivId + '\')">';
            s += '<label for="da_is_1_' + iss.replace(/ /g,"_") + '" style="margin:5px 10px 5px 0;float:left;">' + iss + '</label>';
            s += '</div>';
        }
        s += '</td>';
        s += '</tr>';
    }
    if (subGroups2) {
        s += '<tr>';
        s += '<td class="row_heading" rowspan="2" style="width:150px;">' + disease2 + '</td>';
        s += '<td class="row_heading" style="max-width:150px;">Sub-group</td>';
        s += '<td class="even">';
        for(var i=0; i<subGroups2.length; ++i) {
            s += '<div style="float:left;">';
            if (i == 0)
                s += '<input type="checkbox" class="discriminant_sg_2_class" style="margin-top:10px;float:left;" id="da_sg_2_' + subGroups2[i].replace(/ /g,"_") + '" name="discriminant_sg_2[]" value="' + subGroups2[i] + '" onclick="getDABioProjects(\'' + resultDivId + '\')" checked>';
            else
                s += '<input type="checkbox" class="discriminant_sg_2_class" style="margin-top:10px;float:left;" id="da_sg_2_' + subGroups2[i].replace(/ /g,"_") + '" name="discriminant_sg_2[]" value="' + subGroups2[i] + '" onclick="getDABioProjects(\'' + resultDivId + '\')">';
            s += '<label for="da_sg_2_' + subGroups2[i].replace(/ /g,"_") + '" style="margin:5px 10px 5px 0;float:left;">' + subGroups2[i] + '</label>';
            s += '</div>';
        }
        s += '</td>';
        s += '</tr>';
        s += '<tr>';
        s += '<td class="row_heading" style="max-width:150px;">Isolation Source</td>';
        s += '<td class="odd">';
        for(const iss of isolationSources2) {
            s += '<div style="float:left;">';
            if (checkedIsolationSources.has(iss))
                s += '<input type="checkbox" class="discriminant_is_2_class" style="margin-top:10px;float:left;" id="da_is_2_' + iss.replace(/ /g,"_") + '" name="discriminant_is_2[]" value="' + iss + '" onclick="getDABioProjects(\'' + resultDivId + '\')" checked>';
            else
                s += '<input type="checkbox" class="discriminant_is_2_class" style="margin-top:10px;float:left;" id="da_is_2_' + iss.replace(/ /g,"_") + '" name="discriminant_is_2[]" value="' + iss + '" onclick="getDABioProjects(\'' + resultDivId + '\')">';
            s += '<label for="da_is_2_' + iss.replace(/ /g,"_") + '" style="margin:5px 10px 5px 0;float:left;">' + iss + '</label>';
            s += '</div>';
        }
        s += '</td>';
        s += '</tr>';
    }
    
    document.getElementById('browse-da-sg-options').innerHTML = s;
    document.getElementById('browse-da-sg-options-div').style.display = 'block';
    document.getElementById('browse-da-other-options-div').style.display = 'block';
    getDABioProjects(resultDivId);
}

function validateTaxonomicForm(checkBioProjects=true) {
    var disease = document.querySelectorAll('input[name=taxonomic_ds]:checked');
    if (disease == null || disease.length < 1) {
        alert("Group must be selected");
        return false;
    }
    var assayType = document.querySelectorAll('input[name=taxonomic_at]:checked');
    if (assayType == null || assayType.length < 1) {
        alert("Assay type must be selected");
        return false;
    }
    var subGroups = document.querySelectorAll('.taxonomic_sg_class:checked');
    if (subGroups == null || subGroups.length < 1) {
        alert("At least one sub-group must be selected");
        return false;
    }
    var isolationSources = document.querySelectorAll('.taxonomic_is_class:checked');
    if (isolationSources == null || isolationSources.length < 1) {
        alert("At least one isolation source must be selected");
        return false;
    }
    var libraryLayouts = document.querySelectorAll('.taxonomic_lib_class:checked');
    if (libraryLayouts == null || libraryLayouts.length < 1) {
        alert("At least one library layout must be selected");
        return false;
    }
    var bioProjects = document.querySelectorAll('.taxonomic_bp_class:checked');
    if (checkBioProjects) {
        if (bioProjects == null || bioProjects.length < 1) {
            alert("At least one BioProject must be selected");
            return false;
        }
    }
    return true;
}

function validateDiscriminantForm(checkBioProjects=true) {
    var disease1 = document.getElementById('discriminant_ds_1');
    var disease2 = document.getElementById('discriminant_ds_2');
    if (disease1 == null || disease2 == null || (disease1.value == "null" && disease2.value == "null")) {
        alert("At least one group must be selected");
        return false;
    }
    var assayType = document.querySelectorAll('input[name=discriminant_at]:checked');
    if (assayType == null || assayType.length < 1) {
        alert("Assay type must be selected");
        return false;
    }
    var libraryLayouts = document.querySelectorAll('.discriminant_lib_class:checked');
    if (libraryLayouts == null || libraryLayouts.length < 1) {
        alert("At least one library layout must be selected");
        return false;
    }
    if (disease1 != null && disease1.value != "null") {
        var subGroups1 = document.querySelectorAll('.discriminant_sg_1_class:checked');
        if (subGroups1 == null || subGroups1.length < 1) {
            alert("At least one sub-group must be selected for Group 1");
            return false;
        }
        var isolationSources1 = document.querySelectorAll('.discriminant_is_1_class:checked');
        if (isolationSources1 == null || isolationSources1.length < 1) {
            alert("At least one isolation source must be selected for Group 1");
            return false;
        }
        var bioProjects1 = document.querySelectorAll('.discriminant_bp_1_class:checked');
        if (checkBioProjects) {
            if (bioProjects1 == null || bioProjects1.length < 1) {
                alert("At least one BioProject must be selected for Group 1");
                return false;
            }
        }
    }
    if (disease2 != null && disease2.value != "null") {
        var subGroups2 = document.querySelectorAll('.discriminant_sg_2_class:checked');
        if (subGroups2 == null || subGroups2.length < 1) {
            alert("At least one sub-group must be selected for Group 2");
            return false;
        }
        var isolationSources2 = document.querySelectorAll('.discriminant_is_2_class:checked');
        if (isolationSources2 == null || isolationSources2.length < 1) {
            alert("At least one isolation source must be selected for Group 2");
            return false;
        }
        var bioProjects2 = document.querySelectorAll('.discriminant_bp_2_class:checked');
        if (checkBioProjects) {
            if (bioProjects2 == null || bioProjects2.length < 1) {
                alert("At least one BioProject must be selected for Group 2");
                return false;
            }
        }
    }
    return true;
}
