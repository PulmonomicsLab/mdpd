function showGroups(resultDivId){
    var httpReq = new XMLHttpRequest();
    httpReq.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById(resultDivId).innerHTML = this.responseText;
            var svg = document.querySelectorAll('svg#diseases_svg')[0];
            svg.style.width = '100%';
            svg.style.maxHeight = '600px';
            svg.style.maxWidth = '100%';
        }
    };
    httpReq.open('GET', 'resource/home_figures/Diseases.svg', true);
    httpReq.setRequestHeader("Content-type", "text/json");
    httpReq.send();
}

function showBiomes(resultDivId){
    var httpReq = new XMLHttpRequest();
    httpReq.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById(resultDivId).innerHTML = this.responseText;
            var svg = document.querySelectorAll('svg#bioms_svg')[0];
            svg.style.width = '100%';
            svg.style.maxHeight = '600px';
            svg.style.maxWidth = '100%';
        }
    };
    httpReq.open('GET', 'resource/home_figures/Bioms.svg', true);
    httpReq.setRequestHeader("Content-type", "text/json");
    httpReq.send();
}

function showDomains(resultDivId){
    var httpReq = new XMLHttpRequest();
    httpReq.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById(resultDivId).innerHTML = this.responseText;
            var svg = document.querySelectorAll('svg#domains_svg')[0];
            svg.style.width = '100%';
            svg.style.maxHeight = '130px';
            svg.style.maxWidth = '100%';
        }
    };
    httpReq.open('GET', 'resource/home_figures/Taxa_domains.svg', true);
    httpReq.setRequestHeader("Content-type", "text/json");
    httpReq.send();
}
