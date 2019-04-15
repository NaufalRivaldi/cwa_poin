<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setting extends CI_Controller {

	public function index(){
		$this->def->page_validator();
		$this->load->model("mdsetting");
		$data['title'] = "General Setting";
		$data['menu'] = 3;
		$data['submenu'] = 31;

		$this->load->view("header",$data);

		$this->load->view("footer");
	}

	public function user(){
		$this->def->page_validator();
		$this->load->model("mdsetting");
		$data['title'] = "User Setting";
		$data['menu'] = 3;
		$data['submenu'] = 32;

		$data['user_list'] = $this->mdsetting->listAll();

		$this->load->view("header",$data);
		$this->load->view("setting");
		$this->load->view("footer");
	}

}