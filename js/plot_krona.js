function getKronaData(bioproject, assayType, isolationSource, kronaType) {
    var prefix = 'input/krona/';
    if(kronaType == 'runwise') {
        var file = bioproject.replace(/ /g,"_") + '_' + assayType.replace(/ /g,"_") + '_krona_' + kronaType.replace(/ /g,"_") + '.html';
    } else if(kronaType == 'subgroup') {
        var file = bioproject.replace(/ /g,"_") + '_' + assayType.replace(/ /g,"_") + '_' + isolationSource.replace(/ /g,"_") + '_krona_' + kronaType.replace(/ /g,"_") + '.html';
    }
//     alert(queryType+'<br/>'+bioproject+'<br/>'+disease+'<br/>'+assayType+'<br/>'+isolationSource+'<br/>'+kronaType+'\n'+file);

    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
//             alert(this.responseText);
            var frame = document.getElementById('krona_frame');
            frame.contentWindow.document.open();
            frame.contentWindow.document.write(this.responseText);
            frame.contentWindow.document.close();

            if (document.getElementById('download_div_krona') !== null) {
                document.getElementById('download_div_krona').style.display = 'block';
                document.getElementById('download_button_krona').href = 'resource/public/krona/' + file;
                document.getElementById('download_button_krona').download = file;
            }
        }
    };
    xmlhttp.open('GET', (prefix + file), true);
    xmlhttp.setRequestHeader('Content-type', 'text/html');
    xmlhttp.send();
}

// Function to select a single Run ID for single-Run-ID Krona plot
function selectRun(run) {
    var frame = document.getElementById('krona_frame');
    // Select the appropriate Run ID from the selector dropdown
    frame.contentWindow.document.getElementById('datasets').value = run;
    // Trigger an event to change the plot to the selected Run ID
    frame.contentWindow.document.getElementById('datasets').dispatchEvent(new Event('change'));
    // Hide the Run ID selector dropdown
    frame.contentWindow.document.getElementById('datasets').style.display = 'none';
}
