<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        if(!$this->session->userdata('login'))
		{
			redirect(base_url());
		}
    }

	public function index()
	{
		$this->load->view('paginas/cabeza.php');
		$this->load->view('paginas/cuerpo.php');
		$this->load->view('paginas/pie.php');
	}
}
