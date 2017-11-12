<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dummy extends CI_Controller {
    
    public function index() {
        $this->load->view('dummy_view');
    }
    
    public function dummy_action() {
        $xml = new SimpleXMLElement('<response/>');
        
        $xml->div1 = null;
        $this->addCData($xml->div1, $this->load->view('fragments/dummy_fragment2_view', '', true));
        
        $xml->div2 = null;
        $this->addCData($xml->div2, $this->load->view('fragments/dummy_fragment1_view', '', true));
        
        $this->output
                ->set_content_type('application/xml')
                ->set_output($xml->asXML());
    }

    private function addCData(&$root, $cdata_text) {
        $node = dom_import_simplexml($root);
        $no = $node->ownerDocument;
        $node->appendChild($no->createCDATASection($cdata_text));
    }

}
