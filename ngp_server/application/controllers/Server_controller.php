<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
class Server_controller extends REST_Controller {

    function __construct($config = 'rest')
    {
        parent::__construct($config);
        $this->load->model('Server_model','server');
        $this->session->set_userdata(array('userid'=>1));
    }

    public function index_get(){
        $answers = $this->server->get_answers(0);
        $this->response($answers, REST_Controller::HTTP_OK);                           
    }

    public function post_question()
    {
        $input = $this->input->post();
        $input['userid']=$this->session->userdata('userid');
        $result = $this->server->post_question($input);
        if($result){
            //$this->response(['Answered successfully.'], REST_Controller::HTTP_OK);
            $answers = $this->server->get_answers($result);
            if($answers){
                $this->response($answers, REST_Controller::HTTP_OK);
            }
           else{
                $this->response([
                    'status' => FALSE,
                    'message' => 'something went wrong..!'
                ], REST_Controller::HTTP_NOT_FOUND);
           }
        }
        else{
            $this->response([
                'status' => FALSE,
                'message' => 'something went wrong..!'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    } 

    public function vote_answer(){
		$data = $this->input->post();
        $data['userid']=$this->session->userdata('userid');
        $result = $this->server->post_vote($data);
        if($result){
            $this->response($result, REST_Controller::HTTP_OK);
        }
       else{
            $this->response([
                'status' => FALSE,
                'message' => 'something went wrong..!'
            ], REST_Controller::HTTP_NOT_FOUND);
       }
    }

    public function bookmark_answer(){
        $data = $this->input->post();
        $data['userid']=$this->session->userdata('userid');
        
        $result = $this->server->book_mark_answer($data);
        if($result){
            $this->response($result, REST_Controller::HTTP_OK);
        }
       else{
            $this->response([
                'status' => FALSE,
                'message' => 'something went wrong..!'
            ], REST_Controller::HTTP_NOT_FOUND);
       }
    }

    public function post_answer(){
        $data = $this->input->post();
        $data['userid']=$this->session->userdata('userid');
        
        $result = $this->server->post_answer($data);
        if($result){
            $this->response($result, REST_Controller::HTTP_OK);
        }
       else{
            $this->response([
                'status' => FALSE,
                'message' => 'something went wrong..!'
            ], REST_Controller::HTTP_NOT_FOUND);
       }
    }

    public function create_filter(){
        $data = $this->input->post();
        $data['userid']=$this->session->userdata('userid');
        if($data['filter']!='clear'){ $result = $this->server->create_filter($data); }
		else{$result = $this->server->get_answers(0);$result['message']=$result;}
		
        //var_dump($result);return;
        if($result){
            $res_row='';
            if(!empty($result['message'])){
                foreach($result['message'] as $row){
                    if($row->answered==1){
                        $answered=' answered';
                        $loadbtn='';$readed='readonly';
                    }else{
                        $answered=$readed='';
                        $loadbtn='<div class="submit-answer form-inline">
                                    <button class="answer-btn" onClick="submit_answer('.$row->questionid.',this)" id="submitanswer">SUBMIT</button>
                                </div>';
                    }
                    $res_row.= '<div class="br-4 mt-5 row bg-gray">
                        <div class="col-1 vote-container form-inline">
                            <p class="w-100 vote-value vote_'.$row->questionid.'">'.$row->vote.'</p>
                            <span onclick="vote_answer('.$row->questionid.','.$row->answerid.','.$row->vote.',1)" class="w-50 vote-plus vote_plus_'.$row->questionid.'"></span><span onclick="vote_answer('.$row->questionid.','.$row->answerid.','.$row->vote.',0)" class="w-50 vote-minus  vote_minus_'.$row->questionid.'"></span>
                        </div>
                        <div class="col-11  form-inline">
                        <div class="'.$row->bookmarkclass.' bookmark_'.$row->questionid.'_'.$row->answerid.'" id="bookmark_tick_'.$row->questionid.'" bookmark-data="'.$row->bookmarked.'" onclick="bookmark_question('.$row->questionid.','.$row->answerid.','.$row->bookmarked.')"></div>
                            <div class="col-11 form-inline">
                                <label for="answer_for_'.$row->questionid.'" class="view-question">'.$row->question.'</label>
                                <textarea id="answer_for_'.$row->questionid.'" class="answer-box'.$answered.' answer_for_'.$row->questionid.'" rows="2" '.$readed.'>'.$row->answer.'</textarea>
                            </div>
                            '.$loadbtn.'
                            <div class="col-12 jc-fd form-inline">
                                <p class="ques_time">'.$row->creationtime.'</p>
                            </div>
                        </div>
                    </div>';
                }
            }
            //var_dump($res_row);
            $this->response($res_row, REST_Controller::HTTP_OK);
        }
       else{
            $this->response([
                'status' => FALSE,
                'message' => 'something went wrong..!'
            ], REST_Controller::HTTP_NOT_FOUND);
       }
    }
}
?>
