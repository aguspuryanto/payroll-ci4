<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        // return view('welcome_message');
        echo view('backend/v_header', $this->data);    
        echo view('backend/v_menu', $this->data);    
        echo view('backend/v_footer', $this->data);
    }
}
