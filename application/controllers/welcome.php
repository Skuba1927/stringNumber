<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{

	    $this->load->model('StringLang');
	    if (isset($_POST['number']) && !empty($_POST['number']) && isset($_POST['currency'])
        && isset($_POST['language'])) {
            $numb = $this->StringLang->returnString($_POST['number'], $_POST['currency'], $_POST['language']);
            $enter_number = $_POST['number'];
            $entered_number = $_POST['number'];
        } else {
	        $numb = '';
	        $enter_number = "Введите число";
	        $entered_number = "";
        }

        $this->load->library('parser');
        $data = [
            'number_string' => $numb,
            'enter_number' => $enter_number,
            'entered_number' => $entered_number,
        ];
        $this->parser->parse('welcome_message', $data);

		//$this->load->view('welcome_message');
	}


}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */