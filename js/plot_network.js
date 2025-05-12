networks = new Map();
networkCurrentZooms = new Map();
networkCurrentLayouts = new Map();
networkLayouts = new Map();
nodeMetrices = new Map();

function getControlPanelHTML(subgroup, assayType) {
    var controlPanelHTML =
//         '<p style="margin-top:5px; font-weight:bold; font-size:1.05em; text-align:center;"></p>' +
        '<div style="float:left; margin:5px 20px;">' +
            '<table>' +
                '<tr><td colspan="3"><p style="margin:0; font-weight:bold; text-align:center;">Pan</p></td></tr>' +
                '<tr>' +
                    '<td></td>' +
                    '<td><button class="round" title="Pan up" onclick="panUp(\''+subgroup+'\')">&uarr;</button></td>' +
                    '<td></td>' +
                '</tr>' +
                '<tr>' +
                    '<td><button class="round" title="Pan left" onclick="panLeft(\''+subgroup+'\')">&larr;</button></td>' +
                    '<td><button class="round" title="Pan down" onclick="panDown(\''+subgroup+'\')">&darr;</button></td>' +
                    '<td><button class="round" title="Pan right" onclick="panRight(\''+subgroup+'\')">&rarr;</button></td>' +
                '</tr>' +
            '</table>' +
        '</div>' +
        '<div style="float:left; margin:5px 20px;">' +
            '<table>' +
                '<tr><td colspan="3"><p style="margin:0; font-weight:bold; text-align:center;">Zoom &amp; Layout</p></td></tr>' +
                '<tr>' +
                    '<td style="width:33%;"><button style="width:100%;" title="Zoom in" onclick="zoomIn(\''+subgroup+'\')"><i class="fa fa-solid fa-magnifying-glass-plus"></i></button></td>' +
                    '<td style="width:34%;"><button style="width:100%;" title="Reset axes" onclick="resetAxes(\''+subgroup+'\')"><i class="fa fa-solid fa-home"></i></button></td>' +
                    '<td style="width:33%;"><button style="width:100%;" title="Zoom out" onclick="zoomOut(\''+subgroup+'\')"><i class="fa fa-solid fa-magnifying-glass-minus"></i></button></td>' +
                '</tr>' +
                '<tr>' +
                    '<td colspan="3">' +
                        '<select title="Change layout" style="width:100%; background-color:#e6e6ff;" id="layout_' + subgroup.replace(/ /g,"_") + '" name="layout" onchange="changeLayout(\''+subgroup+'\')">' +
                            '<option value="cose">CoSE Layout</option>' +
                            '<option value="circle">Circle Layout</option>' +
                            ((assayType == 'WMS') ? '<option value="random" selected>Random Layout</option>' : '<option value="random">Random Layout</option>') +
                        '</select>' +
                    '</td>' +
                '</tr>' +
            '</table>' +
        '</div>' +
        '<div style="float:left; margin:5px 20px;">' +
            '<table>' +
                '<tr><td colspan="3"><p style="margin:0; font-weight:bold; text-align:center;">Download</p></td></tr>' +
                '<tr>' +
                    '<td>' +
                        '<a target="_blank" title="Download JPEG" id="jpeg_download_' + subgroup.replace(/ /g,"_") + '" download="plot.jpg">' +
                            '<button style="width:100%;" onclick="downloadJPEG(\''+subgroup+'\')">JPEG</button>' +
                        '</a>' +
                    '</td>' +
                    '<td>' +
                        '<a target="_blank" title="Download PNG" id="png_download_' + subgroup.replace(/ /g,"_") + '" download="plot.png">' +
                            '<button style="width:100%;" onclick="downloadPNG(\''+subgroup+'\')">PNG</button>' +
                        '</a>' +
                    '</td>' +
                '</tr>' +
                '<tr>' +
                    '<td>' +
                        '<a target="_blank" title="Download SVG" id="svg_download_' + subgroup.replace(/ /g,"_") + '" download="plot.svg">' +
                            '<button style="width:100%;" onclick="downloadSVG(\''+subgroup+'\')">SVG</button>' +
                        '</a>' +
                    '</td>' +
                    '<td>' +
                        '<a target="_blank" title="Download JSON" id="json_download_' + subgroup.replace(/ /g,"_") + '" download="plot.json">' +
                            '<button style="width:100%;" onclick="downloadJSON(\''+subgroup+'\')">JSON</button>' +
                        '</a>' +
                    '</td>' +
                '</tr>' +
            '</table>' +
        '</div>' +
        '<div style="float:left; margin:5px 20px;">' +
            '<p id="node_info_' + subgroup.replace(/ /g,"_") + '" style="width:100%; padding:5px;"></p>' +
        '</div>' +
        '<div style="clear:both;"></div>';
    return controlPanelHTML;
}

function makePlot(div_id, networkData, subgroup) {
    // Initialize the network
    var network = cytoscape({
        userZoomingEnabled: false,
        container: document.getElementById(div_id),
        style: [
            {
                selector: 'node',
                style: {
                    'width': 20,
                    'height': 20,
                    'background-color': '#b3ccff',
                    'border-color': '#000000',
                    'border-width': 1,
                    'label': 'data(label)',
                    'font-size': 13
                }
            },
            {
                selector: 'edge',
                style: {
                    'width': 2,
                    'target-arrow-shape': 'triangle',
//                     'arrow-scale': 0.8,
                    'curve-style': 'bezier'
                }
            }
        ]
    });

    // Add nodes to the network
    for(const node of networkData[subgroup].nodelist)
        network.add(node);

    // Add edges to the network
    for(const edge of networkData[subgroup].edgelist)
        network.add(edge);

    // Add style for positive and negative edges of the network using mappers
    network.style()
        .selector('.negative').
        style({
            'line-color': 'mapData(weight,-1,0,rgb(255,51,51),rgb(255,230,230))',
            'target-arrow-color': 'mapData(weight,-1,0,rgb(255,51,51),rgb(255,230,230))'
        })

        .selector('.positive').
        style({
            'line-color': 'mapData(weight,0,1,rgb(193,240,193),rgb(41,163,41))',
            'target-arrow-color': 'mapData(weight,0,1,rgb(193,240,193),rgb(41,163,41))'
        })
    ;

    // Mount network
    network.fit().center().mount();

    // Add layouts for the network
    var circleLayout = network.layout({name: 'circle'});
    var randomLayout = network.layout({name: 'random'});
    var bounds = network.renderedExtent();
    bounds = {x1: bounds.x1, y1: bounds.y1, w: bounds.w, h: bounds.h};
    var coseLayout = network.layout({name: 'cose', boundingBox: bounds, padding: 5, animationThreshold: 10});
    if(networkData[subgroup].at == "WMS")
        randomLayout.run(); // Random layout as default layout for WMS
    else
        coseLayout.run(); // CoSE as default layout otherwise

    // Compute information of nodes
    for(const node of networkData[subgroup].nodenames) {
        var nodeElement = network.nodes('#'+subgroup+'_'+node);
        var degCentrality = network.$().degreeCentrality({root: '#'+subgroup+'_'+node, directed: true});
        nodeMetrices.set(subgroup+'_'+node, {
            out_degree: network.nodes('#'+subgroup+'_'+node).outdegree(),
            in_degree: network.nodes('#'+subgroup+'_'+node).indegree(),
//             out_degree_centrality: degCentrality.outdegree,
//             in_degree_centrality: degCentrality.indegree,
//             betweenness_centrality: network.$().betweennessCentrality({directed: true}).betweennessNormalized('#'+subgroup+'_'+node)
        });
    }
    // Register events to show node info
    for(const node of networkData[subgroup].nodenames) {
        network.$('#'+subgroup+'_'+node).on('mouseover', function(event) {
            var message = 'Node: ' + node
                + '<br/>Out-degree: ' + nodeMetrices.get(subgroup+'_'+node).out_degree + ', In-degree: ' + nodeMetrices.get(subgroup+'_'+node).in_degree;
//                 + '<br/>Out-degree centrality: ' + nodeMetrices.get(subgroup+'_'+node).out_degree_centrality
//                 + '<br/>In-degree centrality: ' + nodeMetrices.get(subgroup+'_'+node).in_degree_centrality
//                 + '<br/>Betweenness centrality: ' + nodeMetrices.get(subgroup+'_'+node).betweenness_centrality;
            document.getElementById('node_info_' + subgroup.replace(/ /g,"_")).innerHTML = message;
        });
        network.$('#'+subgroup+'_'+node).on('mouseout', function(event) {
            document.getElementById('node_info_' + subgroup.replace(/ /g,"_")).innerHTML = '';
        });
        network.$('#'+subgroup+'_'+node).on('dblclick', function(event) {
            window.open('taxa.php?key=' + node.substr(3).replace(/_/g, " "), '_blank');
        });
    }

    networks.set(subgroup, network);
    networkCurrentZooms.set(subgroup, 1);
    networkCurrentLayouts.set(subgroup, coseLayout);
    networkLayouts.set(subgroup, {circle: circleLayout, random: randomLayout, cose: coseLayout})
}

function plotNetwork(div_id, response) {
    if (response.length <= 2) {
        document.getElementById(div_id).innerHTML = 'No significant taxa co-occurrence found !!';
        return;
    }
    document.getElementById(div_id).innerHTML = '';
    var networkData = JSON.parse(response);
    for(var subgroup of Object.keys(networkData)) {
        var clearingNode = document.createElement('div');
        clearingNode.style.clear = 'both';
        document.getElementById(div_id).appendChild(clearingNode);
        var labelNode = document.createElement('p');
        labelNode.innerHTML = 'Subgroup: ' + networkData[subgroup].label;
        labelNode.style.cssText += 'margin:10px 0 0 0; font-size:1.2em; font-weight:bold;';
        document.getElementById(div_id).appendChild(labelNode);
        var plotNode = document.createElement('div');
        plotNode.id = div_id + '_' + subgroup;
        plotNode.style.cssText += 'height:400px; margin-top:5px; border:2px solid #004d99;';
        document.getElementById(div_id).appendChild(plotNode);
        makePlot(div_id + '_' + subgroup, networkData, subgroup);
        var nbNode = document.createElement('p');
        nbNode.innerHTML = '(Hover the mouse pointer on the nodes to get node information and double-click on the nodes to get taxa details)';
        nbNode.style.cssText += 'margin:0; font-size:0.9em; font-style:italic;';
        document.getElementById(div_id).appendChild(nbNode);
        var controlPanelNode = document.createElement('div');
        controlPanelNode.style.cssText += 'width:100%; margin-top:5px; background-color:#fff9e6; border:1px dashed #004d99; border-radius:10px;';
        controlPanelNode.innerHTML = getControlPanelHTML(subgroup, networkData[subgroup].at);
        document.getElementById(div_id).appendChild(controlPanelNode);
        document.getElementById('network_note').style.display = 'block';
    }
}

function getNetworkData(div_id, dataJSON) {
    var data = JSON.parse(dataJSON);
    var httpReq = new XMLHttpRequest();
    httpReq.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            plotNetwork(div_id, this.responseText)
        }
    };
    httpReq.open('POST', 'bioproject_network_analysis_data.php', true);
    httpReq.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    httpReq.send(
        'bioproject=' + encodeURIComponent(data.bioproject) +
        '&' + 'at=' + encodeURIComponent(data.at) +
        '&' + 'is=' + encodeURIComponent(data.is)
    );
}


// Network manipulation and utility functions
function resetAxes(subgroup) {
//     networkCurrentLayouts.get(subgroup).run();
    networks.get(subgroup).fit();
    networkCurrentZooms.set(subgroup, 1);
}

function zoomIn(subgroup) {
    var network = networks.get(subgroup);
    var currentZoom = networkCurrentZooms.get(subgroup);
    network.zoom({level: currentZoom + 0.2, renderedPosition: {x: network.width() / 2, y: network.height() / 2}});
    networkCurrentZooms.set(subgroup, currentZoom + 0.2);
}

function zoomOut(subgroup) {
    var network = networks.get(subgroup);
    var currentZoom = networkCurrentZooms.get(subgroup);
    network.zoom({level: currentZoom - 0.2, renderedPosition: {x: network.width() / 2, y: network.height() / 2}});
    networkCurrentZooms.set(subgroup, currentZoom - 0.2);
}

function panLeft(subgroup) {
    networks.get(subgroup).panBy({x: -20, y: 0});
}

function panRight(subgroup) {
    networks.get(subgroup).panBy({x: 20, y: 0});
}

function panUp(subgroup) {
    networks.get(subgroup).panBy({x: 0, y: -20});
}

function panDown(subgroup) {
    networks.get(subgroup).panBy({x: 0, y: 20});
}

function changeLayout(subgroup) {
    var layoutName = document.getElementById('layout_' + subgroup.replace(/ /g,"_")).value;
    var layout = networkLayouts.get(subgroup)[layoutName];
    layout.run();
    networkCurrentLayouts.set(subgroup, layout);
    networks.get(subgroup).fit();
    networkCurrentZooms.set(subgroup, 1);
}

function downloadPNG(subgroup) {
    var pngBlob = networks.get(subgroup).png({output: 'blob', bg: '#ffffff', maxHeight: 600});
    var link = document.getElementById('png_download_' + subgroup.replace(/ /g,"_"));
    link.href = URL.createObjectURL(pngBlob);
}

function downloadJPEG(subgroup) {
    var jpegBlob = networks.get(subgroup).jpg({output: 'blob', bg: '#ffffff', quality: 0.8, maxHeight: 600});
    var link = document.getElementById('jpeg_download_' + subgroup.replace(/ /g,"_"));
    link.href = URL.createObjectURL(jpegBlob);
}

function downloadSVG(subgroup) {
    var svgContent = networks.get(subgroup).svg({bg: '#ffffff'});
    var svgBlob = new Blob([svgContent], {type:"image/svg+xml;charset=utf-8"});
    var link = document.getElementById('svg_download_' + subgroup.replace(/ /g,"_"));
    link.href = URL.createObjectURL(svgBlob);
}

function downloadJSON(subgroup) {
    var jsonData = networks.get(subgroup).json();
    var link = document.getElementById('json_download_' + subgroup.replace(/ /g,"_"));
    link.href = 'data:text/json;charset=utf-8,' + encodeURIComponent(JSON.stringify(jsonData));
}
