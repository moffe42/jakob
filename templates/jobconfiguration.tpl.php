<!DOCTYPE html>
<html lang="en">
    <head>
        <title>JAKOB - Attribute collector by WAYF</title>
        <meta charset="utf-8" />
        <meta name="application-name" content="JAKOB" />
        <meta http-equiv="Cache-Control" content="no-cache" />
        <meta http-equiv="expires" content="Mon, 22 Jul 2002 11:12:01 GMT" />
        <meta http-equiv="pragma" content="no-cache" />
        <meta name="robots" content="none" />
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <script src="http://yui.yahooapis.com/3.4.1/build/yui/yui-min.js"></script>
        <!-- <script src="/scripts/job2.js"></script> -->
        <link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/3.4.1/build/cssreset/cssreset-min.css">         
        <link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/3.4.1/build/cssbase/cssbase-min.css" />
        <link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/3.4.1/build/cssfonts/cssfonts-min.css">
        <link rel="stylesheet" href="/style/admin.css" type="text/css" />
        <style>
.success {
    background-color: green;
}
.fail {
    background-color: red;
}
ul.tree, ul.tree ul {
     list-style-type: none;
     background: url(vline.png) repeat-y;
     margin: 0;
     padding: 0;
   }
   
   ul.tree ul {
     margin-left: 30px;
   }

   ul.tree li {
     margin: 0;
     padding: 0 0 0 24px;
     line-height: 60px;
     background: url(node.png) no-repeat;
     color: #000;
     font-weight: bold;
   }

   ul.tree li.last {
     background: #fff url(lastnode.png) no-repeat;
   }
   
    ul.tree li.root {
     background: #fff url(hline.png) no-repeat;
    background-position: -5px 30px;
   }
    ul.tree div {
        border: 1px solid #000; 
        height: 55px; 
        width: 300px;
        display: inline-block; 
        vertical-align: middle; 
        text-align: center;
}
body > div {
    margin-left: 20px;
}
        </style>
    </head>
    <body>
<div>
    <h1>Step 1: Empty configuration</h1>
    <ul class="tree" id="tree">
        <li class="root">
            <div>Drag connector here</div>
        </li>
    </ul>
</div>
<hr />
<div>
    <h1>Step 2: One connector</h1>
    <ul class="tree" id="tree">
        <li class="root">
            <div>Connector: CULR</div>
            <ul>
                <li><div>Drag connector here (If success)</div></li>
            </ul>
            <ul>
                <li class="last"><div>Drag connector here (If error)</div></li>
            </ul>
        </li>
    </ul>
</div>
<hr />
<div>
    <h1>Step 3: Added a connector to fail state for CULR connector</h1>
    <ul class="tree" id="tree">
        <li class="root">
            <div>Connector: CULR</div>
            <ul>
                <li><div>Drag connector here (If success)</div></li>
            </ul>
            <ul>
                <li class="last"><div>Connector: CPR</div>
                    <ul>
                        <li><div>Drag connector here (If success)</div></li>
                    </ul>
                    <ul>
                        <li class="last"><div>Drag connector here (If error)</div></li>
                    </ul>
                </li>
            </ul>
        </li>
    </ul>
</div>
<hr />
<div>
    <h1>Step 4: Added a connector to success state for CPR connector</h1>
    <ul class="tree" id="tree">
        <li class="root">
            <div>Connector: CULR</div>
            <ul>
                <li><div>Drag connector here (If success)</div></li>
            </ul>
            <ul>
                <li class="last"><div>Connector: CPR</div>
                    <ul>
                        <li><div>Connector: KommuneDB</div>
                            <ul>
                                <li><div>Drag connector here (If success)</div></li>
                            </ul>
                            <ul>
                                <li class="last"><div>Drag connector here (If error)</div></li>
                            </ul>
                        </li>
                    </ul>
                    <ul>
                        <li class="last"><div>Drag connector here (If error)</div></li>
                    </ul>
                </li>
            </ul>
        </li>
    </ul>
</div>
<hr />
<div>
    <h1>Step 5: Addeding success states for all valid paths</h1>
    <ul class="tree" id="tree">
        <li class="root">
            <div>Connector: CULR</div>
            <ul>
                <li><div class="success"> Return all collected attributes</div></li>
            </ul>
            <ul>
                <li class="last"><div>Connector: CPR</div>
                    <ul>
                        <li><div>Connector: KommuneDB</div>
                            <ul>
                                <li><div class="success">Return all collected attributes</div></li>
                            </ul>
                            <ul>
                                <li class="last"><div>Drag connector here (If error)</div></li>
                            </ul>
                        </li>
                    </ul>
                    <ul>
                        <li class="last"><div>Drag connector here (If error)</div></li>
                    </ul>
                </li>
            </ul>
        </li>
    </ul>
</div>
<hr />
<div>
    <h1>Step 6: Addeding fail states for all non-valid paths</h1>
    <ul class="tree" id="tree">
        <li class="root">
            <div>Connecotr: CULR</div>
            <ul>
                <li><div class="success">Return all collected attributes</div></li>
            </ul>
            <ul>
                <li class="last"><div>Connector: CPR</div>
                    <ul>
                        <li><div>Connector: KommuneDB</div>
                            <ul>
                                <li><div class="success">Return all collected attributes</div></li>
                            </ul>
                            <ul>
                                <li class="last"><div class="fail">Return an error</div></li>
                            </ul>
                        </li>
                    </ul>
                    <ul>
                        <li class="last"><div class="fail">Retrn an error</div></li>
                    </ul>
                </li>
            </ul>
        </li>
    </ul>
</div>
<hr />


<!--
<h1>Complete tree</h1>
    <ul class="tree" id="tree">
        <li class="root"><div>Root</div>
            <ul>
                <li><div>Left</div>
                    <ul>
                        <li><div>Left Left</div>
                            <ul><li><div>Left Left Left</div></li></ul>
                            <ul><li class="last"><div>Left Left Right</div></li></ul>
                        </li>
                    </ul>
                    <ul>
                        <li class="last"><div>Left Right</div>
                            <ul><li><div>Left Right Left</div></li></ul>
                            <ul><li class="last"><div>Left Right Right</div></li></ul>
                        </li>
                    </ul>
                </li>
            </ul>
            <ul>
                <li class="last"><div>Right</div>
                    <ul>
                        <li><div>Right Left</div>
                            <ul><li><div>Right Left Left</div></li></ul>
                            <ul><li class="last"><div>Right Left Right</div></li></ul>
                        </li>
                    </ul>
                    <ul>
                        <li class="last"><div>Right Right</div>
                            <ul>
                                <li>
                                    <div>Drag connector here</div>
                                </li>
                            </ul>
                            <ul>
                                <li class="last">
                                    <div>Drag connector here</div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
            </ul>
        </li>
    </ul>
</div>
-->
    </body>
</html>
