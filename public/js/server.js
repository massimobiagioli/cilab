/**
 * CodeIgniter Module
 */
var CI = (function() {
    
    /**
     * Invia richiesta al server
     * @param object sender Oggetto che invia la richiesta
     */
    var sendRequest = function(sender) {
        var $sender = $(sender);
    
        // Imposta parametri chiamata
        var params = {
            update: $sender.data('update')
        };

        // Effettua chiamata al server
        $.post($sender.data('action'), params, function (data) {
            var xml = $(data);

            // Update Fragments
            updateFragments(xml.find('fragments'));

            // Messages
            parseMessages(xml.find('messages'));
        });
    };
    
    /**
     * Aggiorna tutti i frammenti html contenuti nella risposta
     * @param array fragments Frammenti da aggiornare
     */
    function updateFragments(fragments) {
        fragments.children().each(function (index, node) {
            var $node = $(node);
            var nodeName = $node.prop('tagName');
            var nodeContent = $node.text();
            $('#' + nodeName).html(nodeContent);
        });
    }
    
    /**
     * Effettua il parsing di tutti i messaggi contenuti nella risposta
     * @param array messages Messaggi da elaborare
     */
    function parseMessages(messages) {
        messages.children().each(function (index, node) {
            var $node = $(node);
            var nodeName = $node.prop('tagName');
            switch (nodeName) {
                case 'console':
                    resolveConsoleMessage($node);
                    break;
            }
        });
    }
    
    /**
     * Invia un messaggio alla console di javascript
     * @param object $node Nodo
     */
    function resolveConsoleMessage($node) {
        switch ($node.attr('level')) {
            case 'log':
                console.log($node.text());
                break;
            case 'warn':
                console.warn($node.text());
                break;
            case 'error':
                console.error($node.text());
                break;
            default:
                console.log($node.text());
                break;
        }
    }
    
    return {
        sendRequest: sendRequest
    };
    
})();
