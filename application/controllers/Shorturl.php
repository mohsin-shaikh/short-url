<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shorturl extends CI_Controller 
{

    public $dictionary = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";  
    
    public function __construct()
    {
        parent::__construct();
        //Do your magic here
        $this->dictionary = str_split($this->dictionary);
        $this->load->model('Model_shorturl');
    }

    public function index($short_link = '')
    {
        if (!empty($short_link)) 
        {
            $url_id = $this->decode($short_link); 
            $row = $this->Model_shorturl->get_single(['url_id' => $url_id]);
            
            if (!empty($row)) 
            {
                $counter     = $row->counter;
                $update_data = ['counter' => $counter+1];
                $update_url  = $this->Model_shorturl->change($url_id , $update_data);
                $addhttp     = $this->addhttp($row->link);
                redirect($addhttp ,'refresh');
            }else{
                redirect('','refresh');
            }
        }else{
            $data['urls'] = $this->Model_shorturl->get_all_data();

            $this->load->view('home', $data);
        }
    }

    public function createCode()
    {
        $this->form_validation->set_rules('url', 'URL', 'trim|required|callback_check_url');

        if ($this->form_validation->run()) {

            $url = $this->input->post('url');

            if ( !$this->checkURL($url) )
            {
                $save_data = [
                    'link'      => $url,
                    'short_link'=>'0',
                ];

                $save_url = $this->Model_shorturl->store($save_data);
               
                if ($save_url) {
                    $update_data = [
                        'short_link' => $this->encode($save_url),
                    ];

                    $update_url = $this->Model_shorturl->change($save_url , $update_data);
                    
                    if ($update_url) {
                        $new_data = $this->Model_shorturl->get_single(['url_id' => $save_url]);
                        $short_link                 = $new_data->short_link;
                        $this->data['short_link']   = $short_link;
                        $this->data['success']      = TRUE;
                        $this->data['message']      = 'Copied to Clipboard ! Simply Paste it (Ctrl + V).';
                    }else{
                        $this->data['success']  = FALSE;
                        $this->data['message']  = 'Sorry BOSS ! Something went wrong with our Code.';
                    }
                } else {
                    $this->data['success'] = FALSE;
                    $this->data['message'] = 'Data not change';
                }

            }else{
                //URL Found in DB.
                $check = $this->checkURL($url);
                $new_data                   = $this->Model_shorturl->get_single(['url_id' => $check]);
                $short_link                 = $new_data->short_link;
                $this->data['short_link']   = $short_link;
                $this->data['success']      = TRUE;
                $this->data['message']      = 'Copied to Clipboard ! Simply Paste it (Ctrl + V).';
            }
        } else {
            $this->data['success']      = FALSE;
            $this->data['message']      = validation_errors();
        }
        echo json_encode($this->data);
    }

    function checkURL($url)
    {

        $check = $this->Model_shorturl->get_single( ['link' => $url] );

        if (!empty($check)) 
        {
            //URL Found in DB
            return $check->url_id;
        }else{
            //URL Not Found in DB 
            return FALSE;
        }
    }

    function addhttp($url) 
    {
        if (!preg_match("~^(?:f|ht)tps?://~i", $url)) 
        {
            $url = "http://" . $url;
        }
        return $url;
    }

    function check_url($url)
    {
        $url = $this->addhttp($url);
        $file_headers = @get_headers($url);
        if(!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found') 
        {
            $this->form_validation->set_message('check_url', 'The {field} field is not valid.');
            return FALSE;
        }else {
            return TRUE;
        }
    }

    function encode($i)
    {
        if ($i == 0)
        return $this->dictionary[0];

        $result = '';
        $base = count($this->dictionary);

        while ($i > 0)
        {
            $result[] = $this->dictionary[($i % $base)];
            $i = floor($i / $base);
        }

        $result = array_reverse($result);

        return join("", $result);
    }

    function decode($input)
    {
        $i = 0;
        $base = count($this->dictionary);

        $input = str_split($input);

        foreach($input as $char)
        {
            $pos = array_search($char, $this->dictionary);

            $i = $i * $base + $pos;
        }

        return $i;
    }

}

/* End of file Shorturl.php */
/* Location: ./application/controllers/Shorturl.php */