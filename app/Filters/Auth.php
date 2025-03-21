<?php namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class Auth implements FilterInterface {
	public function before(RequestInterface $request, $arguments = null) {
		if(is_null(session()->get('username_citra'))) {
       return redirect()->to(base_url('login'));
    }
	}

	public function after(RequestInterface $request, ResponseInterface $response, $arguments=null) {

	}
}