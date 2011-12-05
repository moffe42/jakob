goog.provide('jakob.admin');
goog.provide('jakob.admin.ui');

goog.require('goog.dom');
goog.require('goog.events');
goog.require('goog.fx.DragDrop');






jakob.admin.initialize = function() {
    var container = jakob.admin.ui.setup();

    /*
    goog.events.listen(
        goog.dom.getElement('createcontainer'),
        goog.events.EventType.CLICK,
        jakob.admin.ui.createDrop
    );
    */
}



jakob.admin.ui.setup = function() {
    // Setup outer container
    var jobcontainer = goog.dom.createDom('div', 
        {
            'id': 'jobconfiguration',
            'style' : 'height: 802px; width: 1000px; border: 1px solid #000000; position:relative;'
        }
    );
    goog.dom.appendChild(document.body, jobcontainer);

    // Setup confiig container
    var configcontainer = goog.dom.createDom('div', 
        {
            'id': 'configcontainer',
            'style' : 'height: 780px; width: 200px; border: 1px solid #000000; display: inline-block; float: left; position: absolute; top: 10px; left: 10px;'
        }
    );
    goog.dom.appendChild(jobcontainer, configcontainer);

    // Setup create container
    var createcontainer = goog.dom.createDom('div', 
        {
            'id': 'createcontainer',
            'style' : 'height: 780px; width: 700px; border: 1px solid #000000; display: inline-block; float: right; position: absolute; top: 10px; right: 10px;'
        }
    );
    goog.dom.appendChild(jobcontainer, createcontainer);

    var dropcontainer = goog.dom.createDom('div', 
        {
            'id': 'dropcontainer',
            'style' : 'height: 100px; width: 100px; border: 1px solid #000000; display: inline-block;'
        }
    );
    goog.dom.appendChild(createcontainer, dropcontainer);

    var dragcontainer = goog.dom.createDom('div', 
        {
            'id': 'dragcontainer',
            'style' : 'height: 100px; width: 100px; border: 1px solid #000000; display: inline-block; position: absolute; left: 200px; top: 200px;'
        }
    );
    goog.dom.appendChild(createcontainer, dragcontainer);
    
    var source = new goog.fx.DragDrop('dragcontainer');
    var target = new goog.fx.DragDrop('dropcontainer');

    source.addTarget(target);

    target.setTargetClass('target');
    source.setSourceClass('source');

    source.init();
    target.init();


    goog.events.listen(target, 'drop', drop);
    goog.events.listen(target, 'dragover', dragOver);
    goog.events.listen(source, 'dragstart', dragStart);
    goog.events.listen(source, 'dragout', dragOut);
/*





    drop1.setSourceClass('source');
      drop1.setTargetClass('target');
    drop1.init();
    
    goog.events.listen(drop1, 'dragover', dragOver);
      goog.events.listen(drop1, 'dragout', dragOut);
        goog.events.listen(drop1, 'drop', drop);
  goog.events.listen(drop1, 'dragstart', dragStart);
    goog.events.listen(drop1, 'dragend', dragEnd);
*/

    return jobcontainer;    
}
  function dragStart(event) {
          goog.style.setOpacity(event.dragSourceItem.element, 0.5);
            }

  function dragEnd(event) {
          goog.style.setOpacity(event.dragSourceItem.element, 1.0);
            }
function dragOver(event) {
        event.dropTargetItem.element.style.background = 'red';
          }

  function dragOut(event) {
          event.dropTargetItem.element.style.background = 'silver';
            }

  function drop(event) {
      alert('drop');
      return;
          event.dropTargetItem.element.style.background = 'silver';
              var str = [
                        event.dragSourceItem.data,
                        ' dropped onto ',
                              event.dropTargetItem.data,
                                    ' at ',
                                          event.viewportX,
                                                'x',
                                                      event.viewportY
                                                              ];
                  alert(str.join(''));
                    }












jakob.admin.ui.createDrop = function(e) {
    var x = e.offsetX,
        y = e.offsetY;
    
    var target = goog.dom.createDom('div', {'id':'target', 'style': 'height: 30px; width: 30px; border: 1px solid #000000; position: absolute; left: ' + x + 'px; top: ' + y + 'px;'});

    goog.dom.appendChild(goog.dom.getElement('createcontainer'), target);

    return target;
}







jakob.admin.ui.createButton = function() {
    var newHeader = goog.dom.createDom('h1', {'style': 'background-color:#EEE'},
            'Hello world!');
    goog.dom.appendChild(document.body, newHeader);
}
