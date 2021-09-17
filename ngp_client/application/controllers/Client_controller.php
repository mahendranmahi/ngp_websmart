<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Client_controller extends CI_Controller {

    var $api ="";
    function __construct() 
    {
        parent::__construct();
        $this->api="http://localhost/ngp_websmart/ngp_server/";
        $this->load->model('Client_model','home');
    }

	public function call_api_server($url,$data=''){
		$curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
		isset($data) ? curl_setopt($curl, CURLOPT_POSTFIELDS, $data) : '';
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($curl);
		curl_close($curl);
		return $result;
	}
	
    public function ask_question(){
		$this->api.='get-answer';
		$data['answer']=$this->call_api_server($this->api);
        return $this->load->view('index',$data);
    }

	public function bookmark_question(){
		$this->api.='bookmark-answer';
		$data = [
            'questionid'=>$this->input->post('questionid'), 
            'answerid'=>$this->input->post('answerid'),
			'bookmarked'=>$this->input->post('bookmarked')
        ];
       $result=$this->call_api_server($this->api,$data);
       echo $result;
    }
	
		
	public function vote_answer(){
		$this->api.='vote-answer';
		$data = [
            'questionid'=>$this->input->post('questionid'), 
            'answerid'=>$this->input->post('answerid'),
			'mode'=>$this->input->post('mode'),
			'vote'=>$this->input->post('vote')
        ];
       $result=$this->call_api_server($this->api,$data);
       echo $result;
    }
	
	public function post_question(){
		$this->api.='post-question';
		$data = ['question'=>$this->input->post('question')];
		$result=$this->call_api_server($this->api,$data);
		echo $result;
    }
	
	public function post_answer(){
		$this->api.='post-answer';
		$data = ['questionid'=>$this->input->post('questionid'), 'answer'=>$this->input->post('answer')];
		$result=$this->call_api_server($this->api,$data);
		echo $result;
    }
	
	public function create_filter(){
		$this->api.='create-filter';
		$data = ['filter'=>$this->input->post('filter')];
		$result=$this->call_api_server($this->api,$data);
		echo $result;
    }
}