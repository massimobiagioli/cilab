<?php

/**
 * Comunicazione con il client
 */
class Client {

    private $CI;
    private $messages;

    public function __construct() {
        $this->CI = & get_instance();
        $this->clearMessages();
    }
    
    /**
     * Pulisce messaggi
     */
    public function clearMessages() {
        $this->messages = [];
    }
    
    /**
     * Aggiunge un messaggio all'elenco dei messaggi da inviare al client
     * @param array $message Messaggio da inserire
     *              Il messaggio è composto in questo modo:
     *              - type => Tipo del messaggio
     *                          - console
     *              - metadata => Metadati specifici
     *                              - console:
     *                                  - level (log, warn, error)
     *                                  - message
     */
    public function addMessage($message) {
        $this->messages[] = $message;
    }
    
    /**
     * Restituisce risposta in XML al client js
     */
    public function xmlResponse() {
        $xml = new SimpleXMLElement('<response/>');

        // Fragments
        $this->addUpdateFragmentsToXml($xml);
        
        // Messages
        $this->addMessagesToXml($xml);
        foreach ($this->messages as $message) {
            switch ($message['type']) {
                case 'console':
                    $this->addConsoleOutputToXml($xml, $message);
                    break;
            }
        }
        
        $this->CI->output
                ->set_content_type('application/xml')
                ->set_output($xml->asXML());
    }
    
    private function addUpdateFragmentsToXml(&$xml) {
        $update = $this->CI->input->post('update');
        if ($update == null) {
            return;
        }
        $xml->fragments = new SimpleXMLElement('<fragments/>');
        $fragments = explode(' ', $update);
        foreach ($fragments as $fragment) {
            $xml->fragments->$fragment = null;
            $this->addCData($xml->fragments->$fragment, $this->CI->load->view('fragments/' . $fragment . '_view', '', true));
        }
    }
    
    private function addMessagesToXml(&$xml) {
        $xml->messages = new SimpleXMLElement('<messages/>');
    }
    
    private function addConsoleOutputToXml(&$xml, $message) {
        if (!isset($message['metadata']['message'])) {
            return;
        }
        $console = $xml->messages->addChild('console');
        $this->addCData($console, $message['metadata']['message']);
        $level = (isset($message['metadata']['level'])) ? $message['metadata']['level'] : 'log';
        if (!in_array($level, ['log', 'warn', 'error'])) {
            $level = 'log';
        }
        $console['level'] = $level;
    }
    
    private function addCData(&$root, $cdataText) {
        $node = dom_import_simplexml($root);
        $no = $node->ownerDocument;
        $node->appendChild($no->createCDATASection($cdataText));
    }
}
