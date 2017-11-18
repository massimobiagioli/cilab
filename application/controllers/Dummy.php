<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dummy extends CI_Controller {

    public function index() {
        $this->load->view('dummy_view');
    }

    public function dummy_action() {
        $this->load->library('client');
        
        $this->client->addMessage([
            'type' => 'console',
            'metadata' => [
                'message' => 'Messaggio di esempio da inviare alla console',
                'level' => 'log'
            ]
        ]);
        
        $this->client->addMessage([
            'type' => 'console',
            'metadata' => [
                'message' => 'Secondo Messaggio di esempio da inviare alla console',
                'level' => 'warn'
            ]
        ]);
        
        $this->client->xmlResponse();
    }

}
