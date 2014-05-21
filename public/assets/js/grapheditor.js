// set up SVG for D3
var width = 960,
        height = 500,
        colors = d3.scale.category10();

var svg = d3.select('#graph')
        .append('svg')
        .attr('width', width)
        .attr('height', height);

// set up initial nodes and links
//  - nodes are known by 'id', not by index in array.
//  - reflexive edges are indicated on the node (as a bold black circle).
//  - links are always source < target; edge directions are set by 'left' and 'right'.
//  
//var nodes = [
//    {id: 0, reflexive: false, data: "test"},
//    {id: 1, reflexive: true, data: "test 2"},
//    {id: 2, reflexive: false, data: "test 3"}
//];
var lastNodeId = 2,
        links = [
//            {source: nodes[0], target: nodes[1], left: true, right: true},
//            {source: nodes[1], target: nodes[2], left: true, right: true}
        ];

// init D3 force layout
var force = d3.layout.force()
        .nodes(nodes)
        .links(links)
        .size([width, height])
        .linkDistance(150)
        .charge(-500)
        .on('tick', tick)

// define arrow markers for graph links
svg.append('svg:defs').append('svg:marker')
        .attr('id', 'end-arrow')
        .attr('viewBox', '0 -5 10 10')
        .attr('refX', 6)
        .attr('markerWidth', 3)
        .attr('markerHeight', 3)
        .attr('orient', 'auto')
        .append('svg:path')
        .attr('d', 'M0,-5L10,0L0,5')
        .attr('fill', '#000');

svg.append('svg:defs').append('svg:marker')
        .attr('id', 'start-arrow')
        .attr('viewBox', '0 -5 10 10')
        .attr('refX', 4)
        .attr('markerWidth', 3)
        .attr('markerHeight', 3)
        .attr('orient', 'auto')
        .append('svg:path')
        .attr('d', 'M10,-5L0,0L10,5')
        .attr('fill', '#000');

// line displayed when dragging new nodes
var drag_line = svg.append('svg:path')
        .attr('class', 'link dragline hidden')
        .attr('d', 'M0,0L0,0');

// handles to link and node element groups
var path = svg.append('svg:g').selectAll('path'),
        circle = svg.append('svg:g').selectAll('g');

// mouse event vars
var selected_node = null,
        selected_link = null,
        mousedown_link = null,
        mousedown_node = null,
        mouseup_node = null;

function resetMouseVars() {
    mousedown_node = null;
    mouseup_node = null;
    mousedown_link = null;
}

// update force layout (called automatically each iteration)
function tick() {
    // draw directed edges with proper padding from node centers
    path.attr('d', function(d) {
        var deltaX = d.target.x - d.source.x,
                deltaY = d.target.y - d.source.y,
                dist = Math.sqrt(deltaX * deltaX + deltaY * deltaY),
                normX = deltaX / dist,
                normY = deltaY / dist,
                sourcePadding = d.left ? 17 : 12,
                targetPadding = d.right ? 17 : 12,
                sourceX = d.source.x + (sourcePadding * normX),
                sourceY = d.source.y + (sourcePadding * normY),
                targetX = d.target.x - (targetPadding * normX),
                targetY = d.target.y - (targetPadding * normY);
        return 'M' + sourceX + ',' + sourceY + 'L' + targetX + ',' + targetY;
    });

    circle.attr('transform', function(d) {
        return 'translate(' + d.x + ',' + d.y + ')';
    });
}

// update graph (called when needed)
function restart() {
    // path (link) group
    path = path.data(links);

    // update existing links
    path.classed('selected', function(d) {
        return d === selected_link;
    })
            .style('marker-start', function(d) {
                return d.left ? 'url(#start-arrow)' : '';
            })
            .style('marker-end', function(d) {
                return d.right ? 'url(#end-arrow)' : '';
            });


    // add new links
    path.enter().append('svg:path')
            .attr('class', 'link')
            .classed('selected', function(d) {
                return d === selected_link;
            })
            .style('marker-start', function(d) {
                return d.left ? 'url(#start-arrow)' : '';
            })
            .style('marker-end', function(d) {
                return d.right ? 'url(#end-arrow)' : '';
            })
            .on('mousedown', function(d) {
                // select link
                mousedown_link = d;
                if (mousedown_link === selected_link)
                    selected_link = null;
                else
                    selected_link = mousedown_link;
                selected_node = null;
                restart();
            });

    // remove old links
    path.exit().remove();


    // circle (node) group
    // NB: the function arg is crucial here! nodes are known by id, not by index!
    circle = circle.data(nodes, function(d) {
        return d.id;
    });

    // update existing nodes (reflexive & selected visual states)
    circle.selectAll('circle')
            .style('fill', function(d) {
                return (d === selected_node) ? d3.rgb(colors(d.id)).brighter().toString() : colors(d.id);
            })
            .classed('reflexive', function(d) {
                return d.reflexive;
            });

    // add new nodes
    var g = circle.enter().append('svg:g');

    g.append('svg:circle')
            .attr('class', 'node')
            .attr('r', 6)
            .style('fill', function(d) {
                return (d === selected_node) ? d3.rgb(colors(d.id)).brighter().toString() : colors(d.id);
            })
            .style('stroke', function(d) {
                return d3.rgb(colors(d.id)).darker().toString();
            })
            .classed('reflexive', function(d) {
                return d.reflexive;
            })
            .on('mouseover', function(d) {
                if (!mousedown_node || d === mousedown_node)
                    return;
                // enlarge target node
                d3.select(this).attr('transform', 'scale(1.5 )');
            })
            .on('mouseout', function(d) {
                if (!mousedown_node || d === mousedown_node)
                    return;
                // unenlarge target node
                d3.select(this).attr('transform', '');
            })
            .on('mousedown', function(d) { 

                // select node
                mousedown_node = d;
                if (mousedown_node === selected_node)
                    selected_node = null;
                else
                    selected_node = mousedown_node;
                selected_link = null;

                if (selected_node) {
                    $("#selected").removeClass("hidden");
                    $("#selected").val(selected_node.data);
                }

                // reposition drag line
                drag_line
                        .style('marker-end', 'url(#end-arrow)')
                        .classed('hidden', false)
                        .attr('d', 'M' + mousedown_node.x + ',' + mousedown_node.y + 'L' + mousedown_node.x + ',' + mousedown_node.y);

                restart();
            })
            .on('mouseup', function(d) {
                if (!mousedown_node)
                    return;

                // needed by FF
                drag_line
                        .classed('hidden', true)
                        .style('marker-end', '');

                // check for drag-to-self
                mouseup_node = d;
                if (mouseup_node === mousedown_node) {
                    resetMouseVars();
                    return;
                }

                // unenlarge target node
                d3.select(this).attr('transform', '');

                // add link to graph (update if exists)
                // NB: links are strictly source < target; arrows separately specified by booleans
                var source, target, direction;
                if (mousedown_node.id < mouseup_node.id) {
                    source = mousedown_node;
                    target = mouseup_node;
                    direction = 'right';
                } else {
                    source = mouseup_node;
                    target = mousedown_node;
                    direction = 'left';
                }

                var link;
                link = links.filter(function(l) {
                    return (l.source === source && l.target === target);
                })[0];

                if (link) {
                    link[direction] = true;
                } else {
                    link = {source: source, target: target, left: false, right: false};
                    link[direction] = true;
                    links.push(link);
                }

                // select new link
                selected_link = link;
                selected_node = null;
                restart();
            });

    // show node IDs
    g.append('svg:text')
            .attr('x', 0)
            .attr('y', -12)
            .attr('class', 'id')
            .text(function(d) {
                return d.data;
            });

    // remove old nodes
    circle.exit().remove();

    // set the graph in motion
    force.start();
}

function mousedown() {
    // prevent I-bar on drag
    //d3.event.preventDefault();

    // because :active only works in WebKit?
    svg.classed('active', true);

    if ( mousedown_node || mousedown_link)
        return;

    // insert new node at point
    var point = d3.mouse(this),
            node = {id: ++lastNodeId, reflexive: false};
    node.x = point[0];
    node.y = point[1];
    nodes.push(node);

    restart();
}

function mousemove() {
    if (!mousedown_node)
        return;

    // update drag line
    drag_line.attr('d', 'M' + mousedown_node.x + ',' + mousedown_node.y + 'L' + d3.mouse(this)[0] + ',' + d3.mouse(this)[1]);

    restart();
}

function mouseup() {
    if (mousedown_node) {
        // hide drag line
        drag_line
                .classed('hidden', true)
                .style('marker-end', '');
    }

    // because :active only works in WebKit?
    svg.classed('active', false);
    // clear mouse event vars
    resetMouseVars();
}

function spliceLinksForNode(node) {
    var toSplice = links.filter(function(l) {
        return (l.source === node || l.target === node);
    });
    toSplice.map(function(l) {
        links.splice(links.indexOf(l), 1);
    });
}

// app starts here
svg.on('mousedown', mousedown)
        .on('mousemove', mousemove)
        .on('mouseup', mouseup);

restart();