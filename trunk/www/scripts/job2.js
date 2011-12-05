YUI().use('node', 'event', 'dd', function (Y) {
    // The Node and Event modules are loaded and ready to use.
    // Your code goes here!


    var createworkspace = function() {
        var bodyNode = Y.one(document.body);
        var container = Y.Node.create('<div id="container"></div>');  
        var toolbar = Y.Node.create('<div id="toolbar"><h1>Toolbar</h1></div>');  
        var workspace = Y.Node.create('<div id="workspace"><h1>Workspace</h1></div>');  
        
        container.append(toolbar);
        container.append(workspace);
        bodyNode.append(container);

        initToolbar()
    };

    var initToolbar = function() {
        var dd = new Y.DD.Drag({
            node: '#toolbar'
        });

        var _dragStart = function(e) {
            e.target.get('node').setStyles({opacity: '.5'});
        };

        var _dragEnd = function(e) {
            e.target.get('node').setStyles({opacity: '1'});
        };
        
        dd.on('drag:start', _dragStart);
        dd.on('drag:end', _dragEnd);

        loadConnectors();
        configdrop();
    };

    var configdrop = function() {
        var workspace = Y.one('#workspace');
        var dropbox = Y.Node.create('<div id="dropzone" class="dropbox"></div>');
        workspace.append(dropbox);

        var drop = new Y.DD.Drop({
            node: '#dropzone'
        });
        
        var _dropOver = function(e) {
            e.target.get('node').setStyles({
                'background-color' : '#00FFFF'
            });
        };

        var _dropHit = function(e) {
            if (e.target.get('node').test('div') && e.target.get('node').hasClass('dropbox')) {
                var drag = e.drag;
                drag.get('node').remove();
                e.target.get('node').append(drag.get('node'));
                drag.get('node').setStyles({
                    'left' : '0px',
                    'top' : '0px'
                });
                alert('We have a hit');

            }
        };

        drop.on('drop:over', _dropOver);
        drop.on('drop:hit', _dropHit);
    };

    var loadConnectors = function() {
        var toolbar = Y.one('#toolbar');

        var connectors = {
            'cpr': {
                id: 'CPR',
                name: 'CPR connector'
            },
            'vip': {
                id: 'VIP',
                name: 'VIP connector'
            }
        };
        
        var _dragStart = function(e) {
            e.target.get('node').setStyles({opacity: '.5'});
        };

        var _dragEnd = function(e) {
            e.target.get('node').setStyles({opacity: '1'});
        };

        for(x in connectors) {
            var node = Y.Node.create('<div id="'+connectors[x].id+'" class="connectorbox"></div>');
            toolbar.append(node);

            var dd = new Y.DD.Drag({node: '#'+connectors[x].id});
            dd.on('drag:start', _dragStart);
            dd.on('drag:end', _dragEnd);
        }
    };

    createworkspace();
});
