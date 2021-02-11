<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        $this->load->model('LoginModel');
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->library('facebook');
    }

	public function index()
	{
		//$this->load->view('welcome_message');
		$this->load->view('login.php');
	}

	public function autentification()
	{
		$nickname = $this->input->post('nickname');
		$password = $this->input->post('password');
		$remember = $this->input->post('remember_me');
		
		$result = $this->LoginModel->autentification($nickname,$password);

		if($result){
			$data = [
				"nombres" => $result->Nombre,
				"apellidos" => $result->Apellidos,
				"tipo" => $result->Tipo,
				"imagen_usuario" => $result->Imagen,
				"login" => true,
				"remember_me" => $remember
			];

			$this->session->set_userdata($data);
		}
		else{
			echo "error";
		}			
	}

	public function autentificationFacebook()
	{
		if($this->facebook->is_authenticated())
		{		
			$userProfile = $this->facebook->request('get', '/me?fields=id,first_name,last_name,email,gender,locale,picture');

			$data = [
				"nombres" => $userProfile['first_name'],
				"apellidos" => $userProfile['last_name'],
				"login" => true
			];

			$this->session->set_userdata($data);

			$authUrl = site_url("welcome");
			echo $authUrl;
		}
		else
		{
			$authUrl = $this->facebook->login_url();
			echo $authUrl;
		}
	}

	public function cerrar()
	{
		$this->facebook->destroy_session();
		$this->session->sess_destroy();
	}
}
