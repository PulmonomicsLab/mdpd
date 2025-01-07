networks = new Map();
networkCurrentZooms = new Map();
networkCurrentLayouts = new Map();
networkLayouts = new Map();
nodeMetrices = new Map();

function getControlPanelHTML(subgroup) {
    var controlPanelHTML =
//         '<p style="margin-top:5px; font-weight:bold; font-size:1.05em; text-align:center;"></p>' +
        '<center><table>' +
            '<tr><td></td><td><button title="Pan up" onclick="panUp(\''+subgroup+'\')">&uarr;</button></td><td></td></tr>' +
            '<tr><td><button title="Pan left" onclick="panLeft(\''+subgroup+'\')">&larr;</button></td><td><button  title="Reset axes" onclick="resetAxes(\''+subgroup+'\')"><i class="fa fa-solid fa-home"></i></button></td><td><button title="Pan right" onclick="panRight(\''+subgroup+'\')">&rarr;</button></td></tr>' +
            '<tr><td></td><td><button title="Pan down" onclick="panDown(\''+subgroup+'\')">&darr;</button></td><td></td></tr>' +
            '<tr><td colspan="3"><p style="margin:10px 0 0 0; font-weight:bold; text-align:center;">Zoom</p></td></tr>' +
            '<tr><td colspan="3"><button style="width:100%;" title="Zoom in" onclick="zoomIn(\''+subgroup+'\')">Zoom in</button></td></tr>' +
            '<tr><td colspan="3"><button style="width:100%;" title="Zoom out" onclick="zoomOut(\''+subgroup+'\')">Zoom out</button></td></tr>' +
            '<tr><td colspan="3"><p style="margin:10px 0 0 0; font-weight:bold; text-align:center;">Layout</p></td></tr>' +
            '<tr><td colspan="3"><select title="Change layout" style="width:100%;" id="layout_' + subgroup.replace(/ /g,"_") + '" name="layout" onchange="changeLayout(\''+subgroup+'\')"><option value="cose">CoSE</option><option value="circle">Circle</option><option value="random">Random</option><select></td></tr>' +
            '<tr><td colspan="3"><p style="margin:10px 0 0 0; font-weight:bold; text-align:center;">Download</p></td></tr>' +
            '<tr><td colspan="3"><a target="_blank" title="Download JPEG" id="jpeg_download_' + subgroup.replace(/ /g,"_") + '" download="plot.jpg"><button style="width:100%;" onclick="downloadJPEG(\''+subgroup+'\')">JPEG</button></a></td></tr>' +
            '<tr><td colspan="3"><a target="_blank" title="Download PNG" id="png_download_' + subgroup.replace(/ /g,"_") + '" download="plot.png"><button style="width:100%;" onclick="downloadPNG(\''+subgroup+'\')">PNG</button></a></td></tr>' +
            '<tr><td colspan="3"><a target="_blank" title="Download SVG" id="svg_download_' + subgroup.replace(/ /g,"_") + '" download="plot.svg"><button style="width:100%;" onclick="downloadSVG(\''+subgroup+'\')">SVG</button></a></td></tr>' +
        '</table></center><p id="node_info_' + subgroup.replace(/ /g,"_") + '" style="width:100%; padding:5px;"></p>';
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
                    'width': 15,
                    'height': 15,
                    'background-color': '#b3d7ff',
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
    var coseLayout = network.layout({name: 'cose', boundingBox: bounds});
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
            var message = 'Node: ' + node  + '<br/>Out-degree: ' + nodeMetrices.get(subgroup+'_'+node).out_degree + ', In-degree: ' + nodeMetrices.get(subgroup+'_'+node).in_degree;
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

function plotNetwork(div_id, response) {
    var csv = $.csv.toArrays(response, {delimiter: '"', separator: '\t'});
    var networkData = extractNetworkData(csv);
    for(var subgroup of networkData.keys()) {
        var clearingNode = document.createElement('div');
        clearingNode.style.clear = 'both';
        document.getElementById(div_id).appendChild(clearingNode);
        var labelNode = document.createElement('p');
        labelNode.innerHTML = 'Subgroup: ' + networkData.get(subgroup).label;
        labelNode.style.cssText += 'margin:10px 0 0 10%; font-size:1.2em; font-weight:bold;';
        document.getElementById(div_id).appendChild(labelNode);
        var plotNode = document.createElement('div');
        plotNode.id = div_id + '_' + subgroup;
        plotNode.style.cssText += 'width:60%; height:600px; margin:5px 0 0 10%; border:1px solid black; float:left';
        document.getElementById(div_id).appendChild(plotNode);
        makePlot(div_id + '_' + subgroup, networkData, subgroup);
        var controlPanelNode = document.createElement('div')
        controlPanelNode.style.cssText += 'width:19%; height:600px; margin:5px 10% 0 0; background-color:#eeeeee; border: 1px dashed black; float:right;';
        controlPanelNode.innerHTML = getControlPanelHTML(subgroup);
        document.getElementById(div_id).appendChild(controlPanelNode);
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
