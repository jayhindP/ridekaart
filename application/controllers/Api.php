<?php
date_default_timezone_set('Asia/Calcutta');
defined('BASEPATH') OR exit('No direct script access allowed');
class Api extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->helper('url');
		$this->load->model('base');
		$this->load->database();
		$this->controller = "Api/";
		
		header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Authorization");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method == "OPTIONS") {
           die();
        }

        ini_set('memory_limit', '-1');
        error_reporting(0);
	}
	public function get_gst_details()
    {
        $gst_no = $this->input->get_post('gst_no');
        
        $gstData = $this->base->get_gst_details($gst_no);
        if(isset($gstData['taxpayerInfo']) && $gstData['taxpayerInfo'])
        {
            $taxpayerInfo  = $gstData['taxpayerInfo'];
            // echo "<pre>";print_r($taxpayerInfo);die;
            
            $data['name']=$taxpayerInfo['tradeNam'];
            $data['contact_name']=$taxpayerInfo['lgnm'];
            $data['gst_no']=$taxpayerInfo['gstin'];
            $data['detail']=$taxpayerInfo['nba'][0];
            $data['register_date']=date("Y-m-d",strtotime($taxpayerInfo['rgdt']));
            $data['state'] = $taxpayerInfo['adadr'][0]['addr']['stcd'];
            $data['city'] = $taxpayerInfo['adadr'][0]['addr']['dst'];
            $data['pincode'] = $taxpayerInfo['adadr'][0]['addr']['pncd'];
            $address[] = $taxpayerInfo['adadr'][0]['addr']['flno'];
            $address[] = $taxpayerInfo['adadr'][0]['addr']['st'];
            $address[] = $taxpayerInfo['adadr'][0]['addr']['loc'];
            $address[] = $taxpayerInfo['adadr'][0]['addr']['dst'];
            $address[] = $taxpayerInfo['adadr'][0]['addr']['stcd'];
            $data['address'] = ucwords(implode(", ",array_filter($address)));
            
            $response['result'] = $data;
            $response['message'] = 'success';
            $response['status'] = 1;
            echo json_encode($response);    
        }
        else
        {
            $response['result'] = 'Please Enter Valid GST Number.';
            $response['message'] = 'unsuccess';
            $response['status'] = 0;
            echo json_encode($response);
        }
    }
    
	public function page($page)
    {
        $page = urldecode($page);
        $res['data'] = $this->db->where('name',$page)->get("pages")->row_array();
        $this->load->view("page",$res);
    }
	
	public function get_banner()
    {
        $isExist = $this->db->where('status','1')->get('banner');
        if($isExist->num_rows())
        {
            $data = $isExist->result();
            foreach($data as $r){
                $r->banner_image = base_url($r->banner_image);
            }

            $json['result'] = $data;
            $json['message'] = "success";
            $json['status'] = 1;
            
            header('Content-Type: application/json');
            echo json_encode($json);
        }
        else
        {
            $json['result'] = "Banner not found";
            $json['message'] = "unsuccess";
            $json['status'] = 0;
            
            header('Content-Type: application/json');
            echo json_encode($json);
        }
    }
    
    public function contact_us()
    {
        $validate_data = $this->base->validate_data('contact_us',$_REQUEST);
        $this->db->insert('contact_us',$validate_data);
        $contact_us_id = $this->db->insert_id();

        $data = $this->db->where('contact_us_id',$contact_us_id)->get('contact_us')->row_array();
        
        $json['result'] = $data;
        $json['message'] = "success";
        $json['status'] = 1;
        
        header('Content-Type: application/json');
        echo json_encode($json);
    }
    
    public function update_profile()
    {
        $user_id = $this->input->get_post('user_id');
        $mobile = $this->input->get_post('mobile');
        
        $q = $this->db->where('user_id !=',$user_id)->where("mobile",$mobile)->get('user');
        if($q->num_rows() > 0 && $email != '')
        {
            $json['result'] = "Mobile No. is Already Registered";
            $json['message'] = "unsuccess";
            $json['status'] = 0;
            
            header('Content-Type: application/json');
            echo json_encode($json);
        }
        else
        {
            if(isset($_FILES['images']))
            {
                $images = array();
                $images_name = $_FILES['images']['name'];
                foreach($images_name as $k => $name)
                {
                    $img = "uploads/user/M".rand(0000,9999).time().basename($name);
                    if(move_uploaded_file($_FILES['images']['tmp_name'][$k], $img)){
                        $images[] = $img;
                    }
                }
                if(count($images) > 0){
                    $_REQUEST['images'] = implode(",",$images);
                }
            }
            
            if($_FILES['user_image']['name']){
                $user_image = "uploads/user/".rand(0000,9999).basename($_FILES["user_image"]["name"]);
                if(move_uploaded_file($_FILES['user_image']['tmp_name'], $user_image))
                {
                    $_REQUEST['user_image'] = $user_image;
                }
            }
            
            $validate_data = $this->base->validate_data('user',$_REQUEST);
            $this->db->where('user_id',$user_id)->update('user',$validate_data);

            $data = $this->db->where('user_id',$user_id)->get('user')->row_array();
            $data['user_image'] = base_url($data['user_image']);
            
            $json['result'] = $data;
            $json['message'] = "success";
            $json['status'] = 1;
            
            header('Content-Type: application/json');
            echo json_encode($json);
        }       
    }
    
    public function social_login()
    {
        $social_id = $this->input->get_post('social_id');
        
        $q = $this->db->where('social_id',$social_id)->get('user');
        if($q->num_rows() > 0)
        {
            $data = $q->row_array();
            $data['user_image'] = base_url($data['user_image']);
            
            $json['result'] = $data;
            $json['message'] = "success";
            $json['status'] = 1;
            
            header('Content-Type: application/json');
            echo json_encode($json);
        }
        else
        {
            if($_FILES['user_image']['name']){
                $user_image = "uploads/user/".rand(0000,9999).basename($_FILES["user_image"]["name"]);
                if(move_uploaded_file($_FILES['user_image']['tmp_name'], $user_image))
                {
                    $_REQUEST['user_image'] = $user_image;
                }
            }
            
            $validate_data = $this->base->validate_data('user',$_REQUEST);
            $this->db->insert('user',$validate_data);
            $user_id = $this->db->insert_id();

            $data = $this->db->where('user_id',$user_id)->get('user')->row_array();
            $data['user_image'] = base_url($data['user_image']);
            
            $json['result'] = $data;
            $json['message'] = "success";
            $json['status'] = 1;
            
            header('Content-Type: application/json');
            echo json_encode($json);
        }       
    }

    public function login()
    {
        $username = $this->input->get_post('username');
        $password = $this->input->get_post('password');
        $register_id = $this->input->get_post('register_id');

        $isValid = $this->db->where('password',$password)->where("(email='$username' OR mobile='$username')")->get('user');
        if($isValid->num_rows())
        {
            $data = $isValid->row_array();
            $data['user_image'] = base_url($data['user_image']);
            
            if($register_id != ''){
                $this->db->where('user_id',$data['user_id'])->update('user',array('register_id'=>$register_id));
                $data['register_id'] = $register_id;
            }
            
            $json['result'] = $data;
            $json['message'] = "success";
            $json['status'] = 1;
            
            header('Content-Type: application/json');
            echo json_encode($json);
        }
        else
        {
            $json['result'] = "Please enter valid credentials";
            $json['message'] = "unsuccess";
            $json['status'] = 0;
            
            header('Content-Type: application/json');
            echo json_encode($json);
        }
    }
	public function get_profile()
    {
        $user_id = $this->input->get_post('user_id');

        $isValid = $this->db->where('user_id',$user_id)->get('user');
        if($isValid->num_rows())
        {
            $data = $isValid->row_array();
            $data['user_image'] = base_url($data['user_image']);

            $json['result'] = $data;
            $json['message'] = "success";
            $json['status'] = 1;
            
            header('Content-Type: application/json');
            echo json_encode($json);
        }
        else
        {
            $json['result'] = "User not found";
            $json['message'] = "unsuccess";
            $json['status'] = 0;
            
            header('Content-Type: application/json');
            echo json_encode($json);
        }
    }
    
    public function add_user_address()
    {
        $user_id = $this->input->get_post('user_id');
        
        $validate_data = $this->base->validate_data('user_address',$_REQUEST);
        $this->db->insert('user_address',$validate_data);
        $user_address_id = $this->db->insert_id();

        $data = $this->db->where('user_address_id',$user_address_id)->get('user_address')->row_array();
        
        $json['result'] = $data;
        $json['message'] = "success";
        $json['status'] = 1;
        
        header('Content-Type: application/json');
        echo json_encode($json);
    }
    
    public function get_user_address()
    {
        $user_id = $this->input->get_post('user_id');
        $isExist = $this->db->where('user_id',$user_id)->get('user_address');
        if($isExist->num_rows())
        {
            $data = $isExist->result();
            
            $json['result'] = $data;
            $json['message'] = "success";
            $json['status'] = 1;
            
            header('Content-Type: application/json');
            echo json_encode($json);
        }
        else
        {
            $json['result'] = "Any Address Not Found";
            $json['message'] = "unsuccess";
            $json['status'] = 0;
            
            header('Content-Type: application/json');
            echo json_encode($json);
        }
    }
    
    public function change_password()
    {
        $user_id = $this->input->get_post('user_id');
        $old_password = $this->input->get_post('old_password');
        $new_password = $this->input->get_post('new_password');
        
        $isValid = $this->db->where("user_id",$user_id)->where("password",$old_password)->get('user');
        if($isValid->num_rows() > 0)
        {
            $this->db->where("user_id",$user_id)->update("user",array('password'=>$new_password));

            $json['result'] = $this->db->where("user_id",$user_id)->get('user')->row_array();
            $json['message'] = "success";
            $json['status'] = 1;
            
            header('Content-Type: application/json');
            echo json_encode($json);
        }
        else
        {
            $json['result'] = "Old password does not match";
            $json['message'] = "unsuccess";
            $json['status'] = 0;
            
            header('Content-Type: application/json');
            echo json_encode($json);
        }
    }
    
    public function forgot_password()
	{
        $this->load->helper('string');

    	$email = $this->input->get_post('email');
    	$isValid = $this->db->where("email",$email)->get('user');
    	if ($isValid->num_rows() > 0)
		{
		    $user = $isValid->row_array();
            $pass = random_string('alnum', 6);
            $from ="info@ambitious.in.net";
    		$to = $email;
    		$logo = base_url('img/company/logo.png');
    		$subject = "Forgot Password";
            $body = "<div style='max-width: 600px; width: 100%; margin-left: auto; margin-right: auto;'>
                 <header style='color: #fff; width: 100%;'>
                 <img alt='' src='$logo' width ='120' height='120'/>
                 </header>
                  <div style='margin-top: 10px; padding-right: 10px; padding-left: 125px;padding-bottom: 20px;'>
                  <hr>
                  <h3 style='color: #232F3F;'>Hello ".$user['name'].",</h3>
                  <p>You have requested a new password for your BK Enterprises.</p>
                  <p>Your new password is <span style='background:#2196F3;color:white;padding:0px 5px'>".$pass."</span></p>
                  <hr>
          
                  <p>Warm Regards<br>BK Enterprises <br>Support Team</p>
            
                    </div>
                </div>
            </div>";
            
            // To send HTML mail, the Content-type header must be set
            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
             
            // Create email headers
            $headers .= 'From: '.$from."\r\n".
                'Reply-To: '.$from."\r\n" .
                'X-Mailer: PHP/' . phpversion();
             
            mail($to, $subject, $body, $headers);

            $this->db->where('email',$email)->update('user',array('password'=>$pass));
            
    		$ressult['result'] = "Forgot password successfuly";
    		$ressult['message'] = 'successfull';
    		$ressult['status'] = '1';
    		$json = $ressult;
		}
	    else
		{
    		$ressult['result'] = 'Email not exist';
    		$ressult['message'] = 'unsuccessfull';
    		$ressult['status'] = '0';
    		$json = $ressult;
		}

	    header('Content-type: application/json');
	    echo json_encode($json);
	}
	
    public function get_contact_form()
    {
        $isCategory = $this->db->where('status','1')->order_by("category_name")->get('category');
        $isLocation = $this->db->where('status','1')->order_by("location_name")->get('location');
        if($isCategory->num_rows() || $isLocation->num_rows())
        {
            $data['location'] = $isLocation->result();
            $data['category'] = $isCategory->result();
            
            $json['result'] = $data;
            $json['message'] = "success";
            $json['status'] = 1;
            
            header('Content-Type: application/json');
            echo json_encode($json);
        }
        else
        {
            $json['result'] = "Data not found";
            $json['message'] = "unsuccess";
            $json['status'] = 0;
            
            header('Content-Type: application/json');
            echo json_encode($json);
        }
    }
    
    public function add_contact()
    {
        $validate_data = $this->base->validate_data('user',$_REQUEST);
        
        $this->db->insert('user',$validate_data);
        $validate_data['user_id'] = $this->db->insert_id();
        
        $json['result'] = $validate_data;
        $json['message'] = "success";
        $json['status'] = 1;
        
        header('Content-Type: application/json');
        echo json_encode($json);
    }
    
    
    
    
    
    public function get_shop()
    {
        $user_id = $this->input->get_post('user_id');
        $alloted_to = $this->input->get_post('alloted_to');
        $entryby = $this->input->get_post('entryby');
        $visited = $this->input->get_post('visited');
        $date = $this->input->get_post('date')?$this->input->get_post('date'):date('Y-m-d');
        $day = date("l",strtotime($date));
        
        if($alloted_to != ''){
            $this->db->where("u.user_id IN(SELECT client_id FROM allot_client WHERE day='$day' AND user_id = '$alloted_to')");
        }
        if($visited == 1){
            $this->db->where("u.user_id IN(SELECT client_id FROM client_visit WHERE date='$date' AND user_id = '$alloted_to')");
        }
        if($visited == 0){
            $this->db->where("u.user_id NOT IN(SELECT client_id FROM client_visit WHERE date='$date' AND user_id = '$alloted_to')");
        }
        if($entryby != ''){
            $this->db->where('u.entryby',$entryby);
        }
        
        $this->db->select("u.*,c.category_name,v.visited,v.payment,v.orders,v.due,v.remark,v.date");
        $this->db->select("'".base_url()."' as base_url");
        $this->db->join("category c","c.category_id=u.category_id","LEFT");
        $this->db->join("client_visit v","v.client_id=u.user_id AND v.date='$date' AND v.user_id = '$alloted_to'","LEFT");
        $isExist = $this->db->where('u.status','1')->where('u.type','client')->get('user u');
        if($isExist->num_rows())
        {
            $data = $isExist->result();
            
            $json['result'] = $data;
            $json['message'] = "success";
            $json['status'] = 1;
            
            header('Content-Type: application/json');
            echo json_encode($json);
        }
        else
        {
            $json['result'] = "Any Shop not found";
            $json['message'] = "unsuccess";
            $json['status'] = 0;
            
            header('Content-Type: application/json');
            echo json_encode($json);
        }
    }
    
    public function get_notification()
    {
        $user_id = $this->input->get_post('user_id');
        
        $this->db->where(" send_to LIKE '%$user_id%' OR send_to = '' OR send_to = 'all'");
        $isExist = $this->db->where('status','1')->order_by('entrydt','DESC')->get('notification');
        if($isExist->num_rows())
        {
            $data = $isExist->result();
            foreach($data as $k=>  $r){
                $r->time_ago = $this->base->time_elapsed_string($r->datetime, false);
                $sendTo = explode(',', $r->send_to);
                if(!in_array($user_id, $sendTo) && $r->send_to != '' && $r->send_to != 'all'){
                    unset($data[$k]);
                }
            }

            $json['result'] = $data;
            $json['message'] = "success";
            $json['status'] = 1;
            
            header('Content-Type: application/json');
            echo json_encode($json);
        }
        else
        {
            $json['result'] = "Notification not found";
            $json['message'] = "unsuccess";
            $json['status'] = 0;
            
            header('Content-Type: application/json');
            echo json_encode($json);
        }
    }
    
    public function rating()
    {
        $_REQUEST['date']=date("Y-m-d");
        $validate_data = $this->base->validate_data('rating',$_REQUEST);
        $this->db->insert('rating',$validate_data);
        $rating_id = $this->db->insert_id();
        
        $isExist = $this->db->where('rating_id',$rating_id)->get('rating');
        if($isExist->num_rows())
        {
            $data = $isExist->result();
            
            //push_firebase
            $message = array(
        		"message" => array(
        			"result" => "successful",
        			"key" => "A New Review Found",
        			"message" => 'A New Review Found',
        			"rating_id" => $rating_id,
        			"date" => date('Y-m-d h:i:s')
        		)
        	);
        	
        	$users = $this->db->select('register_id')->where("user_id",$data['user_id'])->get('user')->row_array();
    	    $registration_ids[] = $users['register_id'];
            $firebase = $this->base->push_firebase($registration_ids, $message);
            // echo "<pre>";print_r(json_decode($firebase));die;
            
            $json['result'] = $data;
            $json['message'] = "success";
            $json['status'] = 1;
            
            header('Content-Type: application/json');
            echo json_encode($json, JSON_UNESCAPED_UNICODE);
        }
        else
        {
            $json['result'] = "Review Can not be Saved";
            $json['message'] = "unsuccess";
            $json['status'] = 0;
            
            header('Content-Type: application/json');
            echo json_encode($json);
        }
    }
    
    public function review()
    {
        $user_id = $this->input->get_post('user_id');
        
        $this->db->select("r.*,u.name,u.user_image");
        $this->db->join("user u","u.user_id=r.rating_by");
        $isExist = $this->db->where('r.user_id',$user_id)->get('rating r');
        if($isExist->num_rows())
        {
            $data = $isExist->result();
            foreach($data as $r){
                $r->user_image=base_url($r->user_image);
            }
            
            $json['result'] = $data;
            $json['message'] = "success";
            $json['status'] = 1;
            
            header('Content-Type: application/json');
            echo json_encode($json, JSON_UNESCAPED_UNICODE);
        }
        else
        {
            $json['result'] = "Review Can not be Saved";
            $json['message'] = "unsuccess";
            $json['status'] = 0;
            
            header('Content-Type: application/json');
            echo json_encode($json);
        }
    }
	public function add_category()
    {
        $category_name = $this->input->get_post('category_name');
        $isExist = $this->db->where('category_name',$category_name)->get('category');
        if($isExist->num_rows() > 0)
        {
            $json['result'] = "category already Exist.";
            $json['message'] = "unsuccess";
            $json['status'] = 0;
            
            header('Content-Type: application/json');
            echo json_encode($json);
        }
        else
        {
            $validate_data = $this->base->validate_data('category',$_REQUEST);
            $this->db->insert('category',$validate_data);
            $validate_data['category_id'] = $this->db->insert_id();
            
            if(isset($validate_data['entryby']) && $validate_data['entryby'] !='')
            {
                $this->db->insert("user_activity",array(
                        'user_id'=>$validate_data['entryby'],
                        'action'=>"Add Sector - $category_name",
                        'entrydt'=>date('Y-m-d H:i:s'),
                        ));
            }
            
            $json['result'] = $validate_data;
            $json['message'] = "success";
            $json['status'] = 1;
            
            header('Content-Type: application/json');
            echo json_encode($json);
        }
    }
    public function add_location()
    {
        $location_name = $this->input->get_post('location_name');
        $isExist = $this->db->where('location_name',$location_name)->get('location');
        if($isExist->num_rows() > 0)
        {
            $json['result'] = "location already Exist.";
            $json['message'] = "unsuccess";
            $json['status'] = 0;
            
            header('Content-Type: application/json');
            echo json_encode($json);
        }
        else
        {
            $validate_data = $this->base->validate_data('location',$_REQUEST);
            $this->db->insert('location',$validate_data);
            $validate_data['location_id'] = $this->db->insert_id();
            
            if(isset($validate_data['entryby']) && $validate_data['entryby'] !='')
            {
                $this->db->insert("user_activity",array(
                        'user_id'=>$validate_data['entryby'],
                        'action'=>"Add Location - $location_name",
                        'entrydt'=>date('Y-m-d H:i:s'),
                        ));
            }
            
            $json['result'] = $validate_data;
            $json['message'] = "success";
            $json['status'] = 1;
            
            header('Content-Type: application/json');
            echo json_encode($json);
        }
    }
	
	public function add_constituency()
    {
        $constituency_name = $this->input->get_post('constituency_name');
        $isExist = $this->db->where('constituency_name',$constituency_name)->get('constituency');
        if($isExist->num_rows() > 0)
        {
            $json['result'] = "constituency already Exist.";
            $json['message'] = "unsuccess";
            $json['status'] = 0;
            
            header('Content-Type: application/json');
            echo json_encode($json);
        }
        else
        {
            $validate_data = $this->base->validate_data('constituency',$_REQUEST);
            $this->db->insert('constituency',$validate_data);
            $validate_data['constituency_id'] = $this->db->insert_id();
            
            $json['result'] = $validate_data;
            $json['message'] = "success";
            $json['status'] = 1;
            
            header('Content-Type: application/json');
            echo json_encode($json);
        }
    }
	
    
    
    
    
    
	
    
    
    
    
    
       
/*/////////////////////////start chating module/////////////////////////////////////////*/
    public function insert_chat()
	{
	    //if chat image
	    if($_FILES['chat_image']['name']){
            $chat_image = "uploads/chat_image/".rand(0000,9999).basename($_FILES["chat_image"]["name"]);
            if(move_uploaded_file($_FILES['chat_image']['tmp_name'], $chat_image))
            {
                $_REQUEST['chat_image'] = $chat_image;
            }
        }
        
        $_REQUEST['datetime'] = date('Y-m-d H:i:s');
        $validate_data = $this->base->validate_data('chat',$_REQUEST);
        $this->db->insert('chat',$validate_data);
        $chat_id = $this->db->insert_id();
        if($chat_id)
        {
            $chat = $this->db->where('chat_id',$chat_id)->get('chat')->row_array();
            $sender = $this->db->where('user_id',$chat['sender_id'])->get('user')->row_array();
            $receiver = $this->db->where('user_id',$chat['receiver_id'])->get('user')->row_array();
    		
    	    //push_firebase
            $firebaseMsg = array(
        		"message" => array(
        			"result" => "successful",
        			'key' => "You have a new message",
        			'title' => "You have a new message",
        			"message" => $chat['chat_message'],
        			"chat_image" => $chat['chat_image']?base_url($chat['chat_image']):'',
        			"user_id" => $sender['user_id'],
        			"name" => $sender['name'] ,
        			"user_image" => $sender['user_image']?base_url($sender['user_image']):'',
        			"date" => date('Y-m-d h:i:s')
        		)
        	);
        	$firebaseIds[] = $receiver['register_id'];
            $firebase = $this->base->push_firebase($firebaseIds, $firebaseMsg);
            
            $json['result'] = $chat;
            $json['message'] = "success";
            $json['status'] = 1;
        }
        else
        {
            $json['result'] = "Something went wrong.";
            $json['message'] = "unsuccess";
            $json['status'] = 0;
        }
        
	    header('Content-type: application/json');
	    echo json_encode($json);
	}
	
	public function get_conversation()
	{
        $user_id = $this->input->get_post('user_id');
        $this->db->select('product_id');//
        $this->db->select('receiver_id,sender_id')->where("(receiver_id = '$user_id' OR sender_id = '$user_id') ");
        $this->db->order_by('datetime','DESC');
        $this->db->group_by('product_id');//,receiver_id,sender_id,
        $isExist = $this->db->get('chat');
        if($isExist->num_rows())
        {
            $chat = $isExist->result();
            $data = array();
            foreach($chat as $r)
            {
                $other_id = ($r->sender_id == $user_id) ? $r->receiver_id : $r->sender_id;
                $clear_chat_ids = explode(',', $r->clear_chat);
                if(!in_array($user_id,$clear_chat_ids))
                {
					$user  = $this->db->select('name,user_image')->where('user_id',$other_id)->get('user')->row_array();
					$product  = $this->db->select('title')->where('post_id',$r->product_id)->get('post')->row_array();
					$last = $this->db->select('chat_message,chat_image,datetime')->where("(product_id='$r->product_id' AND sender_id='$other_id' AND receiver_id='$user_id') OR (product_id='$r->product_id' AND sender_id='$user_id' AND receiver_id='$other_id') ")->order_by('chat_id','DESC')->get('chat')->row_array();
            		$r->sender_name = $user['name'];
            		$r->sender_image = $user['user_image']?base_url($user['user_image']):'';
            		$r->last_message = $last['chat_message'];
            		$r->last_image = $last['chat_image']?base_url($last['chat_image']):'';
            		$r->time_ago = $this->base->time_elapsed_string($last['datetime']);
            		$r->no_of_message = $this->db->where('sender_id',$other_id)->where('receiver_id',$user_id)->where('status','NOTSEEN')->get('chat')->num_rows();
            		$r->product_name = $product['title'];
            		$r->sender_id = $other_id;
            		$r->receiver_id = $user_id;
            		
            		$data[] = $r;
                }
            }
            
            if($data)
            {
                // echo "<pre>";print_r($data);die;
                $json['result'] = $data;
                $json['message'] = "success";
                $json['status'] = 1;
            }else{
                $json['result'] = "Chat History Not found";
                $json['message'] = "unsuccess";
                $json['status'] = 0;
            }
        }
        else
        {
            $json['result'] = "Chat History Not found";
            $json['message'] = "unsuccess";
            $json['status'] = 0;
        }
        
        header('Content-type: application/json');
	    echo json_encode($json);
    }
    
    public function get_chat()
    {
        $sender_id = $this->input->get_post('sender_id');
        $receiver_id = $this->input->get_post('receiver_id');
        $product_id = $this->input->get_post('product_id');
  
        $this->db->where("(sender_id= '$sender_id' OR receiver_id='$sender_id')");
        $this->db->where("(sender_id= '$receiver_id' OR receiver_id='$receiver_id')");
        $this->db->where("clear_chat!='$receiver_id' AND clear_chat NOT LIKE '%,$receiver_id' AND clear_chat NOT LIKE '$receiver_id,%' AND clear_chat NOT LIKE '%,$receiver_id,%'");
        $this->db->where('product_id', $product_id);
        $this->db->order_by('datetime');
        $isExist = $this->db->get('chat');
        if($isExist->num_rows())
        {
            $data = $isExist->result();
            foreach($data as $r){
                $r->time_ago = $this->base->time_elapsed_string($r->datetime);
                $r->chat_image = $r->chat_image?base_url($r->chat_image):'';
            }
            
            $this->db->where('product_id', $product_id);
            $this->db->where('sender_id',$sender_id)->where('receiver_id',$receiver_id)->update('chat', array('status' => 'SEEN'));
            
            // echo "<pre>";print_r($data);die;
            $json['result'] = $data;
            $json['message'] = "success";
            $json['status'] = 1;
        }else{
            $json['result'] = "Chat History Not found";
            $json['message'] = "unsuccess";
            $json['status'] = 0;
        }
        
        header('Content-type: application/json');
	    echo json_encode($json);
    }
	
    public function clear_conversation()
	{
    	$sender_id = $this->input->get_post('sender_id');
    	$receiver_id = $this->input->get_post('receiver_id');
    	$product_id = $this->input->get_post('product_id');
    	
    	$success = $this->db->query("UPDATE chat SET clear_chat=concat(ifnull(clear_chat,''), ',$receiver_id') WHERE (product_id='$product_id' AND  sender_id = '$sender_id' AND receiver_id = '$receiver_id') OR (product_id='$product_id' AND sender_id = '$receiver_id' AND receiver_id = '$sender_id')");
        
        $json['result'] = $success;
        $json['message'] = "success";
        $json['status'] = 1;
        
	    header('Content-type: application/json');
	    echo json_encode($json);
	}
/*////////////////////////////end chating module///////////////////////////////*/

    
    
    
    
}	
?>