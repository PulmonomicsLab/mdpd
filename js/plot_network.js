networks = new Map();
networkCurrentZooms = new Map();
networkCurrentLayouts = new Map();
networkLayouts = new Map();
nodeMetrices = new Map();

function getControlPanelHTML(subgroup) {
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
                            '<option value="random">Random Layout</option>' +
                        '<select>' +
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

function extractNetworkData(csv) {
    var dataMap = new Map();
    for(var i=1; i<csv.length; ++i) {
        var label = csv[i][0];
        var subgroup = csv[i][0].replace(/ /g,"_").replace(/-/g,"_").replace(/\(/g,"_").replace(/\)/g,"_");//.replace(/\[/g,"_").replace(/\]/g,"_");
        if (dataMap.has(subgroup)) {
            dataMap.get(subgroup).label = label;
            dataMap.get(subgroup).sources.push(csv[i][1]);
            dataMap.get(subgroup).targets.push(csv[i][2]);
            dataMap.get(subgroup).weights.push(parseFloat(csv[i][3]));
            dataMap.get(subgroup).nodes.add(csv[i][1]);
            dataMap.get(subgroup).nodes.add(csv[i][2]);
        } else {
            var nodes = new Set();
            nodes.add(csv[i][1]);
            nodes.add(csv[i][2]);
            dataMap.set(subgroup, {
                label: label,
                sources: [csv[i][1]],
                targets: [csv[i][2]],
                weights: [parseFloat(csv[i][3])],
                nodes: nodes
            });
        }
    }
    return dataMap;
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
    for(const node of networkData.get(subgroup).nodes)
        network.add({
            group: 'nodes',
            data: {id: subgroup+'_'+node, label: node}
        });

    // Add edges to the network
    for(var i=0; i<networkData.get(subgroup).sources.length; ++i)
        network.add({
            group: 'edges',
            classes: (networkData.get(subgroup).weights[i] > 0) ? 'positive' : 'negative',
            data: {
                id: subgroup+'_'+networkData.get(subgroup).sources[i]+'_'+networkData.get(subgroup).targets[i],
                source: subgroup+'_'+networkData.get(subgroup).sources[i],
                target: subgroup+'_'+networkData.get(subgroup).targets[i],
                weight: networkData.get(subgroup).weights[i]
            }
        });

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
    coseLayout.run(); // CoSE as default layout

    // Compute information of nodes
    for(const node of networkData.get(subgroup).nodes) {
        var nodeElement = network.nodes('#'+subgroup+'_'+node);
        var degCentrality = network.$().degreeCentrality({root: '#'+subgroup+'_'+node, directed: true});
        nodeMetrices.set(subgroup+'_'+node, {
            out_degree: network.nodes('#'+subgroup+'_'+node).outdegree(),
            in_degree: network.nodes('#'+subgroup+'_'+node).indegree(),
            out_degree_centrality: degCentrality.outdegree,
            in_degree_centrality: degCentrality.indegree,
            betweenness_centrality: network.$().betweennessCentrality({directed: true}).betweennessNormalized('#'+subgroup+'_'+node)
        });
    }
    // Register events to show node info
    for(const node of networkData.get(subgroup).nodes) {
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
    }

    networks.set(subgroup, network);
    networkCurrentZooms.set(subgroup, 1);
    networkCurrentLayouts.set(subgroup, coseLayout);
    networkLayouts.set(subgroup, {circle: circleLayout, random: randomLayout, cose: coseLayout})
}

function createTaxaButtons(networkData, subgroup) {
    var s = '<p style="margin:3px; font-weight:bold;">Taxa details</p>'
    for(const node of networkData.get(subgroup).nodes)
        s += '<div style="float:left; margin:5px;"><a href="taxa.php?key=' + node.substr(3).replace(/_/g, " ") + '" target="_blank"><button style="padding:2px 5px;">' + node + '</button></a></div>'
    s += '<div style="clear:both;" />'
    document.getElementById('taxa_button_group_' + subgroup).innerHTML = s;
}

function plotNetwork(div_id, response) {
    var csv = $.csv.toArrays(response, {delimiter: '"', separator: '\t'});
    var networkData = extractNetworkData(csv);
    for(var subgroup of networkData.keys()) {
        var clearingNode = document.createElement('div');
        clearingNode.style.clear = 'both';
        document.getElementById(div_id).appendChild(clearingNode);
        var labelNode = document.createElement('p');
        labelNode.innerHTML = 'Subgroup: ' + networkData.get(subgroup).label;
        labelNode.style.cssText += 'margin:10px 0 0 0; font-size:1.2em; font-weight:bold;';
        document.getElementById(div_id).appendChild(labelNode);
        var plotNode = document.createElement('div');
        plotNode.id = div_id + '_' + subgroup;
        plotNode.style.cssText += 'height:400px; margin-top:5px; border:2px solid #004d99;';
        document.getElementById(div_id).appendChild(plotNode);
        makePlot(div_id + '_' + subgroup, networkData, subgroup);
        var controlPanelNode = document.createElement('div');
        controlPanelNode.style.cssText += 'width:100%; margin-top:5px; background-color:#fff9e6; border:1px dashed #004d99; border-radius:10px;';
        controlPanelNode.innerHTML = getControlPanelHTML(subgroup);
        document.getElementById(div_id).appendChild(controlPanelNode);
        var taxaDetailsNode = document.createElement('div');
        taxaDetailsNode.id = 'taxa_button_group_' + subgroup;
        taxaDetailsNode.style.cssText += 'width:100%; margin-top:5px;';
        document.getElementById(div_id).appendChild(taxaDetailsNode);
        createTaxaButtons(networkData, subgroup);
    }
}

function getNetworkData(div_id, bioproject, assayType, isolationSource) {
    var prefix = 'input/network/';
    var file = bioproject.replace(/ /g,"_") + '_' + assayType.replace(/ /g,"_") + '_' + isolationSource.replace(/ /g,"_") + '.csv';

    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
//             alert(this.responseText);
            plotNetwork(div_id, this.responseText);
        } else if (this.status == 404) {
            document.getElementById(div_id).innerHTML = '<p>Error in analysis parameters !!! Microbial co-occurrence analysis network(s) not available.</p>';
        }
    };
    xmlhttp.open('GET', prefix + file, true);
    xmlhttp.setRequestHeader('Content-type', 'text/csv');
    xmlhttp.send();
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
