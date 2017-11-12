<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Test Dummy Response</title>
    </head>
    <body>
        <h1>RESPONSE FROM CONTROLLER</h1>
        <div id="div1" style="border: 1px solid red; padding: 5px; margin-bottom: 10px;"></div>
        <div id="div2" style="border: 1px solid green; padding: 5px;"></div>

        <!-- JQuery -->
        <script
            src="https://code.jquery.com/jquery-3.2.1.min.js"
            integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
        crossorigin="anonymous"></script>

        <script>
            $(function () {
                $.post('dummy/dummy_action', function(data) {
                    var xml = $(data),
                        response = xml.find('response');
                    
                    response.children().each(function(index, node) {
                        var nodeObj = $(node),
                            nodeName = nodeObj[0].nodeName,
                            nodeContent = nodeObj.text();
                        $('#' + nodeName).html(nodeContent);
                    });
                });
            });
        </script>

    </body>
</html>
