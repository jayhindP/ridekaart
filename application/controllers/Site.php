<?php
date_default_timezone_set('Asia/Calcutta');
defined('BASEPATH') OR exit('No direct script access allowed');
class Site extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->model('base');
		$this->load->database();
		$this->controller = "site/";
		$this->load->library('session');
		$this->load->library('excel');
        ini_set('memory_limit', '-1');
        error_reporting(0);
	}
	
    public function page_not_found()
	{
	    $this->load->view('page_not_found');
	}
	
	
    public function index()
    {
        if($this->session->userdata('username'))
        {
            redirect($this->controller.'dashboard');
        }
        $this->load->view('index');
    }
    
    public function login_submit()
    {
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        $oldURL = $this->session->userdata('old_url');
        
        $this->session->set_userdata('auth_type','admin');
        $this->session->set_userdata('auth_id',1);
        $this->session->set_userdata('auth_name',"Admin");
        $this->session->set_userdata('auth_image',base_url('img/company/logo.png'));
        $this->session->set_userdata('username',$username);
        $this->session->set_userdata('company',"Ridekaart");
        
        if($oldURL != ''){
            $this->session->unset_userdata('old_url');
            echo "<script>window.location.href='$oldURL';</script>";
        }else{
            redirect($this->controller."dashboard");
        }   
    }
    public function login_submit1()
    {
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        $oldURL = $this->session->userdata('old_url');
        
        $isAdmin = $this->db->where('type','admin')->where('email',$username)->where('password',$password)->get('user');
        if($isAdmin->num_rows() > 0)
        {
            $auth = $isAdmin->row_array();
            
            $this->session->set_userdata('auth_type','admin');
            $this->session->set_userdata('auth_id',$auth['user_id']);
            $this->session->set_userdata('auth_name',$auth['name']);
            $this->session->set_userdata('auth_image',base_url('img/company/logo.png'));
            $this->session->set_userdata('username',$username);
            $this->session->set_userdata('company',"Ridekaart");
            
            if($oldURL != ''){
                $this->session->unset_userdata('old_url');
                echo "<script>window.location.href='$oldURL';</script>";
            }else{
                redirect($this->controller."dashboard");
            }
        }
        else
        {
            $url = site_url();
            echo "<script>alert('Username and Pasword Does Not Match'); window.location.href='$url'</script>";
        }
    }
    public function change_password()
    {        
        $auth_type = $this->session->userdata('auth_type');
        $auth_id = $this->session->userdata('auth_id');
        
        if(isset($_POST['change_password'])){
            $isValid = $this->db->where('user_id',$auth_id)->where('password',$_POST['old_password'])->get('user');
            if($isValid->num_rows())
            {
                $this->db->where('user_id',$auth_id)->update('user',array('password'=>$_POST['password']));
                session_destroy();
                $this->session->set_flashdata('err_msg', 'Password has been Changed');
                redirect($this->controller."index");
            }else{
                $this->session->set_flashdata('err_msg', 'Old Password does not Match.');
                redirect($this->controller."change_password");
            }
        }
        
        $res['auth_type'] = $auth_type;
        $res['form'] = ($auth_type == 'admin')?1:0;
        $res['list'] = ($auth_type == 'admin')?1:0;
        $this->load->view('include/header');
        $this->load->view('include/sidebar');
        $this->load->view('change_password',$res);
        $this->load->view('include/footer');
    }
    
    
    public function logout()
    {      
        session_destroy();
        redirect($this->controller."index");
    }

    public function dashboard()
    {        
        $auth_type = $this->session->userdata('auth_type');
        $auth_id = $this->session->userdata('auth_id');
        
        $res['user'] = 0;
        $res['category'] = $this->db->get('category')->num_rows();
        $res['location'] = 0;
        $res['auth_type'] = $auth_type;
        
        $this->load->view('include/header');
        $this->load->view('include/sidebar');
        $this->load->view('dashboard',$res);
        $this->load->view('include/footer');
    }
    

    public function user()
    {
        $auth_type = $this->session->userdata('auth_type');
        $user_id = $this->input->get('q');
        $res['data'] = $this->db->where('user_id',$user_id)->where('type','user')->get('user')->row_array();
        $res['members'] = $this->db->where('parent_id',$user_id)->where('type','user')->order_by('user_id','ASC')->get('user')->result();

        $res['user'] = $this->db->where('type','user')->where('parent_id',0)->order_by('entrydt','DESC')->get('user')->result();
        
        $res['category_list'] = $this->db->where('status',1)->order_by('category_name')->get('category')->result();
        $res['location_list'] = $this->db->where('status',1)->order_by('location_name')->get('location')->result();
        $res['form'] = ($auth_type == 'admin')?1:0;
        $res['list'] = ($auth_type == 'admin')?1:0;
        $this->load->view('include/header');
        $this->load->view('include/sidebar');
        $this->load->view('user',$res);
        $this->load->view('include/footer');
    }

    public function user_submit()
    {
        $auth_type = $this->session->userdata('auth_type');
        $auth_id = $this->session->userdata('auth_id');
        
        $user_id = $this->input->post('user_id');
        $mobile = $this->input->post('mobile');
        $q = $this->db->where('user_id !=',$user_id)->where("mobile",$mobile)->get('user');
        if($q->num_rows() > 0)
        {
            $this->session->set_flashdata('err_msg', 'Mobile Number Already Exist');
            redirect($this->controller."user");
        }
        else
        {
            if($_FILES['user_image']['name']){
                $user_image = "uploads/user/".basename($_FILES["user_image"]["name"]);
                if(move_uploaded_file($_FILES['user_image']['tmp_name'], $user_image))
                {
                    $_POST['user_image'] = $user_image;
                }
            }
            
            $_POST['type']='user';
            $_POST['entryby']=$auth_id;
            
            $msg = $_POST['first_name']." ".$_POST['first_name'];
            if($_POST['company_name'] !=''){
                $msg .= "(".$_POST['company_name'].")";
            }
            
            $validate_data = $this->base->validate_data('user',$_POST);
            if($user_id != ''){
                $this->db->where('user_id',$user_id)->update('user',$validate_data);
                
                $this->db->insert("user_activity",array(
                    'user_id'=>$this->session->userdata('auth_id'),
                    'action'=>"Update Contact of $msg",
                    'entrydt'=>date('Y-m-d H:i:s'),
                    ));
            }else{
                $validate_data['code'] = $this->base->generate_code($validate_data['first_name']);
                $this->db->insert('user',$validate_data);
                $user_id = $this->db->insert_id();
                
                $this->db->insert("user_activity",array(
                    'user_id'=>$this->session->userdata('auth_id'),
                    'action'=>"Add Contact Of $msg",
                    'entrydt'=>date('Y-m-d H:i:s'),
                    ));
            }
            
            //members contacts
            $this->db->where('parent_id',$user_id)->delete('user');//old delete
            if($_POST['company_type']=='Group' && $_POST['no_of_member'] > 0)
            {
                $validate_data['parent_id'] = $user_id;
                unset($validate_data['user_id']);
                unset($validate_data['code']);
                
                //multiple
                $user_idM = $this->input->post("user_idM");
                $first_nameM = $this->input->post("first_nameM");
                $last_nameM = $this->input->post("last_nameM");
                $mobileM = $this->input->post("mobileM");
                $mobile2M = $this->input->post("mobile2M");
                foreach($user_idM as $k=> $v)
                {
                    $members =  $validate_data;
                    $members['code'] = $this->base->generate_code($first_nameM[$k]);
                    $members['type'] = 'user'; 
                    $members['first_name'] = $first_nameM[$k]; 
                    $members['last_name'] = $last_nameM[$k]; 
                    $members['mobile'] = $mobileM[$k]; 
                    $members['mobile2'] = $mobile2M[$k]; 
                    
                    // if($this->db->where('user_id',$user_idM[$k])->get('user')->num_rows()){
                    //     $this->db->where('user_id',$user_idM[$k])->update('user',$members);
                    // }else{
                        $this->db->insert('user',$members);
                    // }
                }
            }
            
            $this->session->set_flashdata('err_msg', 'User Saved Successfully');
            redirect($this->controller."user");
        }       
    }

    public function change_user_status()
    {
        $auth_type = $this->session->userdata('auth_type');
        if($auth_type == 'admin')
        {
            $user_id = $this->input->get('q');
            $q = $this->db->where('user_id',$user_id)->get('user');
            if($q->num_rows() > 0)
            {
                $oldData = $q->row_array();
                $status = $oldData['status'] ? 0 : 1;
                $this->db->where('user_id',$user_id)->update('user',array('status'=>$status));
                $this->session->set_flashdata('err_msg', 'Status Has been Changed Successfully');
                redirect($this->controller."user"); 
            }
            else
            {
                $this->session->set_flashdata('err_msg', 'User No Found');
                redirect($this->controller."user");
            }
        }
        else
        {
            echo "<script>alert('You are not Authorized to Access this Link');window.location.href='".site_url()."'</script>";
        }
    }
    
    public function user_delete()
    {
        $auth_type = $this->session->userdata('auth_type');
        $user_id = $this->input->get('q');
        $q = $this->db->where('user_id',$user_id)->get('user');
        if($q->num_rows() > 0)
        {
            $oldData = $q->row_array();
            $this->db->where('user_id',$user_id)->delete('user');
            unlink($oldData['user_image']);
            $this->session->set_flashdata('err_msg', 'User Deleted Successfully');
            redirect($this->controller."user"); 
        }
        else
        {
            $this->session->set_flashdata('err_msg', 'Image No Found');
            redirect($this->controller."user");
        }
        
    }
    
    public function client()
    {
        $auth_type = $this->session->userdata('auth_type');
        $user_id = $this->input->get('q');
        $res['data'] = $this->db->where('user_id',$user_id)->get('user')->row_array();

        $res['user'] = $this->db->where('type','client')->order_by('entrydt','DESC')->get('user')->result();
        $res['category_list'] = $this->db->where('status','1')->order_by('category_name')->get('category')->result();
        $res['form'] = ($auth_type == 'admin')?1:0;
        $res['list'] = ($auth_type == 'admin')?1:0;
        $this->load->view('include/header');
        $this->load->view('include/sidebar');
        $this->load->view('client',$res);
        $this->load->view('include/footer');
    }

    public function client_submit()
    {
        $user_id = $this->input->post('user_id');
        $mobile = $this->input->post('mobile');
        $q = $this->db->where('user_id !=',$user_id)->where('type','client')->where("mobile",$mobile)->get('user');
        if($q->num_rows() > 0)
        {
            $this->session->set_flashdata('err_msg', 'Mobile Already Exist');
            redirect($this->controller."client");
        }
        else
        {
            if($_FILES['user_image']['name']){
                $user_image = "uploads/user/".basename($_FILES["user_image"]["name"]);
                if(move_uploaded_file($_FILES['user_image']['tmp_name'], $user_image))
                {
                    $_POST['user_image'] = $user_image;
                }
            }
            
            $images = array();
            $files = $_FILES['images']['name'];
            foreach($files as $k => $v)
            {
                if($_FILES['images']['name'][$k]){
                    $image = "uploads/user/M".rand(0000,9999).basename($_FILES["images"]["name"][$k]);
                    if(move_uploaded_file($_FILES['images']['tmp_name'][$k], $image)){
                        $images[] = $image;
                    }
                }
            }
            
            $_POST['images'] = $images?implode(",",$images):'';
            $_POST['code'] = $this->base->generate_code($_POST['name']);
            $_POST['type']='client';
            if($user_id != ''){
                $this->db->where('user_id',$user_id)->update('user',$_POST);
            }else{
                $_POST['code'] = $this->base->generate_code($_POST['name']);
                $this->db->insert('user',$_POST);
            }

            $this->session->set_flashdata('err_msg', 'Shop Saved Successfully');
            redirect($this->controller."client");
        }       
    }

    public function change_client_status()
    {
        $auth_type = $this->session->userdata('auth_type');
        
        $user_id = $this->input->get('q');
        $q = $this->db->where('user_id',$user_id)->get('user');
        if($q->num_rows() > 0)
        {
            $oldData = $q->row_array();
            $status = $oldData['status'] ? 0 : 1;
            $this->db->where('user_id',$user_id)->update('user',array('status'=>$status));
            $this->session->set_flashdata('err_msg', 'Status Has been Changed Successfully');
            redirect($this->controller."client"); 
        }
        else
        {
            $this->session->set_flashdata('err_msg', 'Shop No Found');
            redirect($this->controller."client");
        }   
    }
    
    public function client_delete()
    {
        $auth_type = $this->session->userdata('auth_type');
        $user_id = $this->input->get('q');
        $q = $this->db->where('user_id',$user_id)->get('user');
        if($q->num_rows() > 0)
        {
            $oldData = $q->row_array();
            $this->db->where('user_id',$user_id)->delete('user');
            unlink($oldData['user_image']);
            $this->session->set_flashdata('err_msg', 'Shop Deleted Successfully');
            redirect($this->controller."client"); 
        }
        else
        {
            $this->session->set_flashdata('err_msg', 'Shop No Found');
            redirect($this->controller."client");
        }
        
    }
    
    public function category()
    {
        $auth_type = $this->session->userdata('auth_type');
        $category_id = $this->input->get('q');
        $res['data'] = $this->db->where('category_id',$category_id)->get('category')->row_array();

        $res['category'] = $this->db->order_by('entrydt','DESC')->get('category')->result();
        $res['form'] = ($auth_type == 'admin')?1:0;
        $res['list'] = ($auth_type == 'admin')?1:0;
        $this->load->view('include/header');
        $this->load->view('include/sidebar');
        $this->load->view('category',$res);
        $this->load->view('include/footer');
    }

    public function category_submit()
    {
        $auth_id = $this->session->userdata('auth_id');
        $category_id = $this->input->post('category_id');
        $category_name = $this->input->post('category_name');
        $q = $this->db->where('category_id !=',$category_id)->where('category_name',$category_name)->get('category');
        if($q->num_rows() > 0)
        {
            $this->session->set_flashdata('err_msg', 'category Name Already Exist');
            redirect($this->controller."category");
        }
        else
        {
            if($_FILES['category_image']['name'])
            {
                $category_image = "uploads/category/".rand(0000,9999).basename($_FILES["category_image"]["name"]);
                if(move_uploaded_file($_FILES['category_image']['tmp_name'], $category_image))
                {
                    $_POST['category_image'] = $category_image;
                }
            }
            
            $_POST['entryby']=$auth_id;
            $_POST['entrydt']=date('Y-m-d H:i:s');
            if($category_id != ''){
                $this->db->where('category_id',$category_id)->update('category',$_POST);
                
                $this->db->insert("user_activity",array(
                    'user_id'=>$this->session->userdata('auth_id'),
                    'action'=>"Update Sector - $category_name",
                    'entrydt'=>date('Y-m-d H:i:s'),
                    ));
            }else{                    
                $this->db->insert('category',$_POST);
                
                $this->db->insert("user_activity",array(
                    'user_id'=>$this->session->userdata('auth_id'),
                    'action'=>"Add Sector - $category_name",
                    'entrydt'=>date('Y-m-d H:i:s'),
                    ));
            }

            $this->session->set_flashdata('err_msg', 'category Saved Successfully');
            redirect($this->controller."category");   
        }
    }
    
    public function change_category_status()
    {
        $auth_type = $this->session->userdata('auth_type');
        $category_id = $this->input->get('q');
        $q = $this->db->where('category_id',$category_id)->get('category');
        if($q->num_rows() > 0)
        {
            $oldData = $q->row_array();
            $status = $oldData['status'] ? 0 : 1;
            $this->db->where('category_id',$category_id)->update('category',array('status'=>$status));
            $this->session->set_flashdata('err_msg', 'Status Has been Changed Successfully');
            redirect($this->controller."category"); 
        }
        else
        {
            $this->session->set_flashdata('err_msg', 'category No Found');
            redirect($this->controller."category");
        }
    }
    
    
    public function category_delete()
    {
        $auth_type = $this->session->userdata('auth_type');
        $category_id = $this->input->get('q');
        $q = $this->db->where('category_id',$category_id)->get('category');
        if($q->num_rows() > 0)
        {
            $oldData = $q->row_array();
            $this->db->where('category_id',$category_id)->delete('category');
            $this->session->set_flashdata('err_msg', 'category Deleted Successfully');
            redirect($this->controller."category"); 
        }
        else
        {
            $this->session->set_flashdata('err_msg', 'category No Found');
            redirect($this->controller."category");
        }
        
    }
    
    
    // public function sub_category()
    // {
    //     $auth_type = $this->session->userdata('auth_type');
    //     $sub_category_id = $this->input->get('q');
    //     $res['data'] = $this->db->where('sub_category_id',$sub_category_id)->get('sub_category')->row_array();

    //     $res['sub_category'] = $this->db->select('s.*,c.category_name')->join("category c","c.category_id=s.category_id")->order_by('s.entrydt','DESC')->get('sub_category s')->result();
    //     $res['category_list'] = $this->db->where('status','1')->order_by('entrydt','DESC')->get('category')->result();
    //     $res['form'] = ($auth_type == 'admin')?1:0;
    //     $res['list'] = ($auth_type == 'admin')?1:0;
    //     $this->load->view('include/header');
    //     $this->load->view('include/sidebar');
    //     $this->load->view('sub_category',$res);
    //     $this->load->view('include/footer');
    // }

    // public function sub_category_submit()
    // {
    //     $sub_category_id = $this->input->post('sub_category_id');
    //     $category_id = $this->input->post('category_id');
    //     $sub_category_name = $this->input->post('sub_category_name');
    //     $q = $this->db->where('sub_category_id !=',$sub_category_id)->where('category_id',$category_id)->where('sub_category_name',$sub_category_name)->get('sub_category');
    //     if($q->num_rows() > 0)
    //     {
    //         $this->session->set_flashdata('err_msg', 'Sub category Name Already Exist');
    //         redirect($this->controller."sub_category");
    //     }
    //     else
    //     {
    //         if($sub_category_id != ''){
    //             $this->db->where('sub_category_id',$sub_category_id)->update('sub_category',$_POST);
    //         }else{                    
    //             $this->db->insert('sub_category',$_POST);
    //         }

    //         $this->session->set_flashdata('err_msg', 'sub_category Saved Successfully');
    //         redirect($this->controller."sub_category");   
    //     }
    // }
    
    // public function sub_category_delete()
    // {
    //     $auth_type = $this->session->userdata('auth_type');
    //     $sub_category_id = $this->input->get('q');
    //     $q = $this->db->where('sub_category_id',$sub_category_id)->get('sub_category');
    //     if($q->num_rows() > 0)
    //     {
    //         $oldData = $q->row_array();
    //         $this->db->where('sub_category_id',$sub_category_id)->delete('sub_category');
    //         $this->session->set_flashdata('err_msg', 'sub category Deleted Successfully');
    //         redirect($this->controller."sub_category"); 
    //     }
    //     else
    //     {
    //         $this->session->set_flashdata('err_msg', 'sub category No Found');
    //         redirect($this->controller."sub_category");
    //     }
        
    // }
    
    public function location()
    {
        $auth_type = $this->session->userdata('auth_type');
        $location_id = $this->input->get('q');
        $res['data'] = $this->db->where('location_id',$location_id)->get('location')->row_array();

        $res['location'] = $this->db->order_by('entrydt','DESC')->get('location')->result();
        $res['form'] = ($auth_type == 'admin')?1:0;
        $res['list'] = ($auth_type == 'admin')?1:0;
        $this->load->view('include/header');
        $this->load->view('include/sidebar');
        $this->load->view('location',$res);
        $this->load->view('include/footer');
    }

    public function location_submit()
    {
        $auth_id = $this->session->userdata('auth_id');
        $location_id = $this->input->post('location_id');
        $location_name = $this->input->post('location_name');
        $q = $this->db->where('location_id !=',$location_id)->where('location_name',$location_name)->get('location');
        if($q->num_rows() > 0)
        {
            $this->session->set_flashdata('err_msg', 'location Name Already Exist');
            redirect($this->controller."location");
        }
        else
        {
            if($_FILES['location_image']['name'])
            {
                $location_image = "uploads/location/".rand(0000,9999).basename($_FILES["location_image"]["name"]);
                if(move_uploaded_file($_FILES['location_image']['tmp_name'], $location_image))
                {
                    $_POST['location_image'] = $location_image;
                }
            }
            
            $_POST['entryby']=$auth_id;
            $_POST['entrydt']=date('Y-m-d H:i:s');
            if($location_id != ''){
                $this->db->where('location_id',$location_id)->update('location',$_POST);
                
                $this->db->insert("user_activity",array(
                    'user_id'=>$this->session->userdata('auth_id'),
                    'action'=>"Update Location - $location_name",
                    'entrydt'=>date('Y-m-d H:i:s'),
                    ));
            }else{                    
                $this->db->insert('location',$_POST);
                
                $this->db->insert("user_activity",array(
                    'user_id'=>$this->session->userdata('auth_id'),
                    'action'=>"Add Location - $location_name",
                    'entrydt'=>date('Y-m-d H:i:s'),
                    ));
            }

            $this->session->set_flashdata('err_msg', 'location Saved Successfully');
            redirect($this->controller."location");   
        }
    }
    
    public function change_location_status()
    {
        $auth_type = $this->session->userdata('auth_type');
        $location_id = $this->input->get('q');
        $q = $this->db->where('location_id',$location_id)->get('location');
        if($q->num_rows() > 0)
        {
            $oldData = $q->row_array();
            $status = $oldData['status'] ? 0 : 1;
            $this->db->where('location_id',$location_id)->update('location',array('status'=>$status));
            $this->session->set_flashdata('err_msg', 'Status Has been Changed Successfully');
            redirect($this->controller."location"); 
        }
        else
        {
            $this->session->set_flashdata('err_msg', 'location No Found');
            redirect($this->controller."location");
        }
    }
    
    
    public function location_delete()
    {
        $auth_type = $this->session->userdata('auth_type');
        $location_id = $this->input->get('q');
        $q = $this->db->where('location_id',$location_id)->get('location');
        if($q->num_rows() > 0)
        {
            $oldData = $q->row_array();
            $this->db->where('location_id',$location_id)->delete('location');
            $this->session->set_flashdata('err_msg', 'location Deleted Successfully');
            redirect($this->controller."location"); 
        }
        else
        {
            $this->session->set_flashdata('err_msg', 'location No Found');
            redirect($this->controller."location");
        }
        
    }
    
    
    public function banner()
    {
        $auth_id = $this->session->userdata('auth_id');
        $auth_type = $this->session->userdata('auth_type');
        $subadmin = $this->session->userdata('subadmin');
        
        $banner_id = $this->input->get('q');
        $res['data'] = $this->db->where('banner_id',$banner_id)->get('banner')->row_array();

        $res['banner'] = $this->db->order_by('entrydt','DESC')->get('banner')->result();
        $res['form'] = ($auth_type == 'admin' && $subadmin==0)?1:0;
        $res['list'] = ($auth_type == 'admin' && $subadmin==0)?1:0;
        $this->load->view('include/header');
        $this->load->view('include/sidebar');
        $this->load->view('banner',$res);
        $this->load->view('include/footer');
    }

    public function banner_submit()
    {
        $auth_id = $this->session->userdata('auth_id');
        $banner_id = $this->input->post('banner_id');
        $banner_name = $this->input->post('banner_name');
        $q = $this->db->where('banner_id !=',$banner_id)->where('banner_name',$banner_name)->get('banner');
        if($q->num_rows() > 0)
        {
            $this->session->set_flashdata('err_msg', 'Banner Name Already Exist');
            redirect($this->controller."banner");
        }
        else
        {
           
            if($_FILES['banner_image']['name']){
                $banner_image = "uploads/banner/".basename($_FILES["banner_image"]["name"]);
                if(move_uploaded_file($_FILES['banner_image']['tmp_name'], $banner_image))
                {
                    $_POST['banner_image'] = $banner_image;
                }
            }

            $_POST['entryby']=$auth_id;
            $_POST['entrydt']=date('Y-m-d H:i:s');
            if($banner_id != ''){
                $this->db->where('banner_id',$banner_id)->update('banner',$_POST);
                
                $this->db->insert("user_activity",array(
                    'user_id'=>$this->session->userdata('auth_id'),
                    'action'=>"Update Banner - $banner_name",
                    'entrydt'=>date('Y-m-d H:i:s'),
                    ));
            }else{                    
                $this->db->insert('banner',$_POST);
                
                $this->db->insert("user_activity",array(
                    'user_id'=>$this->session->userdata('auth_id'),
                    'action'=>"Add Banner - $banner_name",
                    'entrydt'=>date('Y-m-d H:i:s'),
                    ));
            }

            $this->session->set_flashdata('err_msg', 'banner Saved Successfully');
            redirect($this->controller."banner");   
        }
    }

    public function change_banner_status()
    {
        $auth_type = $this->session->userdata('auth_type');
        if($auth_type == 'admin')
        {
            $banner_id = $this->input->get('q');
            $q = $this->db->where('banner_id',$banner_id)->get('banner');
            if($q->num_rows() > 0)
            {
                $oldData = $q->row_array();
                $status = $oldData['status'] ? 0 : 1;
                $this->db->where('banner_id',$banner_id)->update('banner',array('status'=>$status));
                $this->session->set_flashdata('err_msg', 'Status Has been Changed Successfully');
                redirect($this->controller."banner"); 
            }
            else
            {
                $this->session->set_flashdata('err_msg', 'banner No Found');
                redirect($this->controller."banner");
            }
        }
        else
        {
            echo "<script>alert('You are not Authorized to Access this Link');window.location.href='".site_url()."'</script>";
        }
    }
    
    public function banner_delete()
    {
        $auth_type = $this->session->userdata('auth_type');
        $banner_id = $this->input->get('q');
        $q = $this->db->where('banner_id',$banner_id)->get('banner');
        if($q->num_rows() > 0)
        {
            $oldData = $q->row_array();
            $this->db->where('banner_id',$banner_id)->delete('banner');
            unlink($oldData['banner_image']);
            $this->session->set_flashdata('err_msg', 'banner Deleted Successfully');
            redirect($this->controller."banner"); 
        }
        else
        {
            $this->session->set_flashdata('err_msg', 'Image No Found');
            redirect($this->controller."banner");
        }
        
    }
    
    public function pages()
    {
        $auth_type = $this->session->userdata('auth_type');
        $pages_id = $this->input->get('q');
        $res['data'] = $this->db->where('pages_id',$pages_id)->get('pages')->row_array();

        $res['pages'] = $this->db->order_by('pages_id','DESC')->get('pages')->result();
        $res['form'] = ($auth_type == 'admin')?1:0;
        $res['list'] = ($auth_type == 'admin')?1:0;
        $this->load->view('include/header');
        $this->load->view('include/sidebar');
        $this->load->view('pages',$res);
        $this->load->view('include/footer');
    }

    public function pages_submit()
    {
        $pages_id = $this->input->post('pages_id');
        $name = $this->input->post('name');
        $q = $this->db->where('pages_id !=',$pages_id)->where('name',$name)->get('pages');
        if($q->num_rows() > 0)
        {
            $this->session->set_flashdata('err_msg', 'Page Name Already Exist');
            redirect($this->controller."pages");
        }
        else
        {
            if($pages_id != ''){
                $this->db->where('pages_id',$pages_id)->update('pages',$_POST);
            }else{                    
                $this->db->insert('pages',$_POST);
            }

            $this->session->set_flashdata('err_msg', 'pages Saved Successfully');
            redirect($this->controller."pages");   
        }
    }
    
    public function page($page)
    {
        $page = urldecode($page);
        $res['data'] = $this->db->where('name',$page)->get("pages")->row_array();
        $this->load->view("page",$res);
    }
    
    public function contact_us()
    {
        $auth_type = $this->session->userdata('auth_type');

        $this->db->select("c.*,u.name,u.user_image");
        $this->db->join("user u","u.user_id=c.user_id");
        $this->db->order_by('c.contact_us_id','DESC');
        $res['contact_us'] = $this->db->get('contact_us c')->result();
        
        $res['form'] = ($auth_type == 'admin')?1:0;
        $res['list'] = ($auth_type == 'admin')?1:0;
        $this->load->view('include/header');
        $this->load->view('include/sidebar');
        $this->load->view('contact_us',$res);
        $this->load->view('include/footer');
    }
    
    public function get_sub_category()
    {
        $category_id = $this->input->get_post('category_id');
        $sub_category = $this->db->where('category_id',$category_id)->get('sub_category')->result();
        echo "<option value=''>--Select--</option>";
        foreach($sub_category as $r){
            echo "<option value='$r->sub_category_id'>$r->sub_category_name</option>";
        }
    } 

    public function change_product_status()
    {
        $auth_type = $this->session->userdata('auth_type');
        if($auth_type == 'admin')
        {
            $product_id = $this->input->get('q');
            $q = $this->db->where('product_id',$product_id)->get('product');
            if($q->num_rows() > 0)
            {
                $oldData = $q->row_array();
                $status = $oldData['status'] ? 0 : 1;
                $this->db->where('product_id',$product_id)->update('product',array('status'=>$status));
                $this->session->set_flashdata('err_msg', 'Status Has been Changed Successfully');
                redirect($this->controller."product"); 
            }
            else
            {
                $this->session->set_flashdata('err_msg', 'product No Found');
                redirect($this->controller."product");
            }
        }
        else
        {
            echo "<script>alert('You are not Authorized to Access this Link');window.location.href='".site_url()."'</script>";
        }
    }    
    public function product_delete()
    {
        $auth_type = $this->session->userdata('auth_type');
        $product_id = $this->input->get('q');
        $q = $this->db->where('product_id',$product_id)->get('product');
        if($q->num_rows() > 0)
        {
            $oldData = $q->row_array();
            $this->db->where('product_id',$product_id)->delete('product');
            unlink($oldData['image']);
            $this->session->set_flashdata('err_msg', 'product Deleted Successfully');
            redirect($this->controller."product"); 
        }
        else
        {
            $this->session->set_flashdata('err_msg', 'Image No Found');
            redirect($this->controller."product");
        }
    }
    public function purchase()
    {
        $auth_type = $this->session->userdata('auth_type');
        $purchase_id = $this->input->get('q');
        $res['data'] = $this->db->where('purchase_id',$purchase_id)->get('purchase')->row_array();

        $res['purchase'] = $this->db->order_by('purchase_id','DESC')->get('purchase')->result();
        $res['product_list'] = $this->db->order_by('product_name')->get('product')->result();
        $res['form'] = ($auth_type == 'admin')?1:0;
        $res['list'] = ($auth_type == 'admin')?1:0;
        $this->load->view('include/header');
        $this->load->view('include/sidebar');
        $this->load->view('purchase',$res);
        $this->load->view('include/footer');
    }

    public function purchase_submit()
    {
        $purchase_id = $this->input->post('purchase_id');
        $product_id = $this->input->post('product_id');
        $qty = $this->input->post('qty');
        
        $product = $this->db->where('product_id',$product_id)->get('product')->row_array();
        $stock = $product['stock']+$qty;
        
        $isExist = $this->db->where('purchase_id',$purchase_id)->get('purchase');
        if($isExist->num_rows()){
            $oldData = $isExist->row_array();
            $this->db->where('purchase_id',$purchase_id)->update('purchase',$_POST);
            $stock = $stock - $oldData['qty'];
        }else{
            $this->db->insert('purchase',$_POST);
        }
        
        $this->db->where('product_id',$product_id)->update('product',array('stock'=>$stock));
        $this->session->set_flashdata('err_msg', 'Purchase Saved Successfully');
        redirect($this->controller."purchase");
    }
    
    public function offer()
    {
        $auth_type = $this->session->userdata('auth_type');
        $offer_id = $this->input->get('q');
        $res['data'] = $this->db->where('offer_id',$offer_id)->get('offer')->row_array();

        $res['offer'] = $this->db->order_by('entrydt','DESC')->get('offer')->result();
        $res['form'] = ($auth_type == 'admin')?1:0;
        $res['list'] = ($auth_type == 'admin')?1:0;
        
        $this->load->view('include/header');
        $this->load->view('include/sidebar');
        $this->load->view('offer',$res);
        $this->load->view('include/footer');
    }

    public function offer_submit()
    {
        $offer_id = $this->input->post('offer_id');
        $offer_title = $this->input->post('offer_title');
        $q = $this->db->where('offer_id !=',$offer_id)->where('offer_title',$offer_title)->get('offer');
        if($q->num_rows() > 0)
        {
            $this->session->set_flashdata('err_msg', 'Offer Title Already Exist');
            redirect($this->controller."offer");
        }
        else
        {
           
            if($_FILES['offer_image']['name']){
                $offer_image = "uploads/offer/".basename($_FILES["offer_image"]["name"]);
                if(move_uploaded_file($_FILES['offer_image']['tmp_name'], $offer_image))
                {
                    $_POST['offer_image'] = $offer_image;
                }
            }

            if($offer_id != ''){
                $this->db->where('offer_id',$offer_id)->update('offer',$_POST);
            }else{                    
                $this->db->insert('offer',$_POST);
            }

            $this->session->set_flashdata('err_msg', 'offer Saved Successfully');
            redirect($this->controller."offer");   
        }
    }

    public function offer_delete()
    {
        $auth_type = $this->session->userdata('auth_type');
        $offer_id = $this->input->get('q');
        $q = $this->db->where('offer_id',$offer_id)->get('offer');
        if($q->num_rows() > 0)
        {
            $oldData = $q->row_array();
            $this->db->where('offer_id',$offer_id)->delete('offer');
            unlink($oldData['offer_image']);
            $this->session->set_flashdata('err_msg', 'offer Deleted Successfully');
            redirect($this->controller."offer"); 
        }
        else
        {
            $this->session->set_flashdata('err_msg', 'Offer No Found');
            redirect($this->controller."offer");
        }
        
    }
    
    public function notification()
    {
        $auth_type = $this->session->userdata('auth_type');
        $notification_id = $this->input->get('q');
        $res['data'] = $this->db->where('notification_id',$notification_id)->get('notification')->row_array();

        $res['notification'] = $this->db->order_by('entrydt','DESC')->get('notification')->result();
        $res['user_list'] = $this->db->order_by('entrydt','DESC')->get('user')->result();
        $res['form'] = ($auth_type == 'admin')?1:0;
        $res['list'] = ($auth_type == 'admin')?1:0;
        
        $this->load->view('include/header');
        $this->load->view('include/sidebar');
        $this->load->view('notification',$res);
        $this->load->view('include/footer');
    }

    public function notification_submit()
    {
        $notification_id = $this->input->post('notification_id');
        $notification_title = $this->input->post('notification_title');
        $notification = $this->input->post('notification');
        
        $_POST['send_to'] = $_POST['send_to'] ? implode(",", $_POST['send_to']) : '';
        
        //push_firebase
        $message = array(
    		"message" => array(
    			"result" => "successful",
    			"key" => $notification_title,
    			"message" => $notification,
    			"date" => date('Y-m-d h:i:s')
    		)
    	);
    	
    	$registration_ids = array();
    	if(count($_POST['send_to']) > 0 && $_POST['send_to'] != ''){
    	    $this->db->where_in("user_id",$_POST['send_to']);
    	}
    	$users = $this->db->select('register_id')->get('user')->result();
    	foreach($users as $u){
    	    $registration_ids[] = $u->register_id;
    	}
	    
        $firebase = $this->base->push_firebase($registration_ids, $message);
        // echo "<pre>";print_r(json_decode($firebase));die;
        
        
        if($notification_id != ''){
            $this->db->where('notification_id',$notification_id)->update('notification',$_POST);
        }else{                    
            $this->db->insert('notification',$_POST);
        }

        $this->session->set_flashdata('err_msg', 'notification Saved Successfully');
        redirect($this->controller."notification");   
    }

    public function notification_delete()
    {
        $auth_type = $this->session->userdata('auth_type');
        $notification_id = $this->input->get('q');
        $q = $this->db->where('notification_id',$notification_id)->get('notification');
        if($q->num_rows() > 0)
        {
            $oldData = $q->row_array();
            $this->db->where('notification_id',$notification_id)->delete('notification');
            $this->session->set_flashdata('err_msg', 'notification Deleted Successfully');
            redirect($this->controller."notification"); 
        }
        else
        {
            $this->session->set_flashdata('err_msg', 'notification Not Found');
            redirect($this->controller."notification");
        }
        
    }
    
    public function promocode()
    {
        $auth_type = $this->session->userdata('auth_type');
        $promocode_id = $this->input->get('q');
        $res['data'] = $this->db->where('promocode_id',$promocode_id)->get('promocode')->row_array();

        $res['promocode'] = $this->db->order_by('entrydt','DESC')->get('promocode')->result();
        $res['form'] = ($auth_type == 'admin')?1:0;
        $res['list'] = ($auth_type == 'admin')?1:0;
        
        $this->load->view('include/header');
        $this->load->view('include/sidebar');
        $this->load->view('promocode',$res);
        $this->load->view('include/footer');
    }

    public function promocode_submit()
    {
        $promocode_id = $this->input->post('promocode_id');
        $promocode_title = $this->input->post('promocode_title');
        $q = $this->db->where('promocode_id !=',$promocode_id)->where('promocode_title',$promocode_title)->get('promocode');
        if($q->num_rows() > 0)
        {
            $this->session->set_flashdata('err_msg', 'promocode Title Already Exist');
            redirect($this->controller."promocode");
        }
        else
        {
           
            if($_FILES['promocode_image']['name']){
                $promocode_image = "uploads/promocode/".basename($_FILES["promocode_image"]["name"]);
                if(move_uploaded_file($_FILES['promocode_image']['tmp_name'], $promocode_image))
                {
                    $_POST['promocode_image'] = $promocode_image;
                }
            }

            if($promocode_id != ''){
                $this->db->where('promocode_id',$promocode_id)->update('promocode',$_POST);
            }else{                    
                $this->db->insert('promocode',$_POST);
            }

            $this->session->set_flashdata('err_msg', 'promocode Saved Successfully');
            redirect($this->controller."promocode");   
        }
    }

    public function promocode_delete()
    {
        $auth_type = $this->session->userdata('auth_type');
        $promocode_id = $this->input->get('q');
        $q = $this->db->where('promocode_id',$promocode_id)->get('promocode');
        if($q->num_rows() > 0)
        {
            $oldData = $q->row_array();
            $this->db->where('promocode_id',$promocode_id)->delete('promocode');
            unlink($oldData['promocode_image']);
            $this->session->set_flashdata('err_msg', 'promocode Deleted Successfully');
            redirect($this->controller."promocode"); 
        }
        else
        {
            $this->session->set_flashdata('err_msg', 'promocode No Found');
            redirect($this->controller."promocode");
        }
        
    }
    
    
    public function post()
    {
        $auth_type = $this->session->userdata('auth_type');

        $this->db->select("p.*,u.name");
        $this->db->join("user u","u.user_id=p.user_id");
        $res['post'] = $this->db->order_by('p.post_id','DESC')->get('post p')->result();
        $res['form'] = ($auth_type == 'admin')?1:0;
        $res['list'] = ($auth_type == 'admin')?1:0;
        $this->load->view('include/header');
        $this->load->view('include/sidebar');
        $this->load->view('post',$res);
        $this->load->view('include/footer');
    }
    
    public function setting()
    {
        $auth_type = $this->session->userdata('auth_type');
        $auth_id = $this->session->userdata('auth_id');
        
        if(isset($_POST['save_setting']))
        {
            $validate_data = $this->base->validate_data('admin',$_POST);
            $this->db->where('user_id',$auth_id)->update('admin',$validate_data);
            $this->session->set_flashdata('err_msg', 'Setting Saved Successfully');
            redirect($this->controller."setting");
        }
        
        $res['data'] = $this->db->where('user_id',$auth_id)->get('admin')->row_array();
        $res['form'] = ($auth_type == 'admin')?1:0;
        $res['list'] = ($auth_type == 'admin')?1:0;
        $this->load->view('include/header');
        $this->load->view('include/sidebar');
        $this->load->view('setting',$res);
        $this->load->view('include/footer');
    }
    
    public function allot_client()
    {
        $auth_type = $this->session->userdata('auth_type');
        $res['user_id'] = $this->input->get('q');
        
        $this->db->select("u.*,GROUP_CONCAT(DISTINCT a.day)as days");
        $this->db->join("user u","u.user_id=a.user_id");
        $res['allot_client'] = $this->db->order_by('a.allot_client_id','DESC')->group_by('u.user_id')->get('allot_client a')->result();
        $res['user_list'] = $this->db->where('status',1)->where('type','user')->order_by('name')->get('user')->result();
        $res['form'] = ($auth_type == 'admin')?1:0;
        $res['list'] = ($auth_type == 'admin')?1:0;
        $this->load->view('include/header');
        $this->load->view('include/sidebar');
        $this->load->view('allot_client',$res);
        $this->load->view('include/footer');
    }

    public function allot_client_submit()
    {
        $user_id = $this->input->post('user_id');
        $days = $this->input->post('days');
        $this->db->where('user_id',$user_id)->delete('allot_client');//delete old data
        
        foreach($days as $k => $day){
            $client_ids = $this->input->post("client_id$k");
            foreach($client_ids as $client_id){
                $this->db->insert("allot_client", array(
                    'user_id'=> $user_id,
                    'client_id'=> $client_id,
                    'day'=> $day,
                    ));
            }
        }
        
        $this->session->set_flashdata('err_msg', 'Client Allotment Saved Successfully');
        redirect($this->controller."allot_client");
    }

    public function view_allot_client()
    {
        $auth_type = $this->session->userdata('auth_type');
        $user_id = $this->input->get('q');
        $day = $this->input->get('day');
        if($day != ''){
            $this->db->where('a.day',$day);
        }
        
        $this->db->where('a.user_id',$user_id);
        $this->db->select("c.*,a.day");
        $this->db->join("user c","c.user_id=a.client_id");
        $res['allot_client'] = $this->db->order_by('a.allot_client_id','DESC')->get('allot_client a')->result();
        
        $res['form'] = ($auth_type == 'admin')?1:0;
        $res['list'] = ($auth_type == 'admin')?1:0;
        $this->load->view('include/header');
        $this->load->view('include/sidebar');
        $this->load->view('view_allot_client',$res);
        $this->load->view('include/footer');
    }
    
    
    
    public function client_visit()
    {
        $auth_type = $this->session->userdata('auth_type');
        
        $res['user_id'] = $this->input->get_post('user_id');
        $res['client_id'] = $this->input->get_post('client_id');
        $res['from'] = $this->input->get_post('from');
        $res['to'] = $this->input->get_post('to');
        
        if($res['user_id'] != ''){
            $this->db->where("v.user_id",$res['user_id']);
        }
        if($res['client_id'] != ''){
            $this->db->where("v.client_id",$res['client_id']);
        }
        if($res['from'] != ''){
            $this->db->where("v.date >=",$res['from']);
        }
        if($res['to'] != ''){
            $this->db->where("v.date <=",$res['to']);
        }

        $this->db->select("v.*,u.name,c.name as client_name,c.mobile as client_mobile,c.email as client_email,c.address as client_address");
        $this->db->join("user u","u.user_id=v.user_id","LEFT");
        $this->db->join("user c","c.user_id=v.client_id","LEFT");
        $res['client_visit'] = $this->db->get('client_visit v')->result();
        $res['user_list'] = $this->db->where('status','1')->where('type','user')->order_by('name')->get('user')->result();
        $res['client_list'] = $this->db->where('status','1')->where('type','client')->order_by('name')->get('user')->result();
        $res['form'] = ($auth_type == 'admin')?1:0;
        $res['list'] = ($auth_type == 'admin')?1:0;
        $this->load->view('include/header');
        $this->load->view('include/sidebar');
        $this->load->view('client_visit',$res);
        $this->load->view('include/footer');
    }
    
    
    public function admin()
    {
        $auth_type = $this->session->userdata('auth_type');
        $user_id = $this->input->get('q');
        $res['data'] = $this->db->where('user_id',$user_id)->where('subadmin',1)->get('user')->row_array();

        $res['user'] = $this->db->where('type','admin')->where('subadmin',1)->order_by('entrydt','DESC')->get('user')->result();
        
        $res['location_list'] = $this->db->where('status',1)->order_by('location_name')->get('location')->result();
        $res['constituency_list'] = $this->db->where('status',1)->order_by('constituency_name')->get('constituency')->result();
        $res['form'] = ($auth_type == 'admin')?1:01;
        $res['list'] = ($auth_type == 'admin')?1:01;
        $this->load->view('include/header');
        $this->load->view('include/sidebar');
        $this->load->view('admin',$res);
        $this->load->view('include/footer');
    }

    public function admin_submit()
    {
        $auth_type = $this->session->userdata('auth_type');
        $auth_id = $this->session->userdata('auth_id');
        
        $user_id = $this->input->post('user_id');
        $mobile = $this->input->post('mobile');
        $q = $this->db->where('user_id !=',$user_id)->where("mobile",$mobile)->get('user');
        if($q->num_rows() > 0)
        {
            $this->session->set_flashdata('err_msg', 'Mobile Number Already Exist');
            redirect($this->controller."admin");
        }
        else
        {
            if($_FILES['user_image']['name']){
                $user_image = "uploads/user/".basename($_FILES["user_image"]["name"]);
                if(move_uploaded_file($_FILES['user_image']['tmp_name'], $user_image))
                {
                    $_POST['user_image'] = $user_image;
                }
            }
            
            $_POST['code'] = $this->base->generate_code($_POST['name']);
            $_POST['type']='admin';
            $_POST['subadmin']='1';
            $_POST['entryby']=$auth_id;
            $_POST['entrydt']=date('Y-m-d H:i:s');
            if($user_id != ''){
                $this->db->where('user_id',$user_id)->update('user',$_POST);
            }else{
                $_POST['code'] = $this->base->generate_code($_POST['name']);
                $this->db->insert('user',$_POST);
                
                $pass = $_POST['password'];
                $from ="info@ambitious.in.net";
        		$to = $_POST['email'];
        		$logo = base_url('img/company/logo.png');
        		$subject = "Your Registration has Successfull";
                $body = "<div style='max-width: 600px; width: 100%; margin-left: auto; margin-right: auto;'>
                     <header style='color: #fff; width: 100%;'>
                     <img alt='' src='$logo' width ='120' height='120'/>
                     </header>
                      <div style='margin-top: 10px; padding-right: 10px; padding-left: 125px;padding-bottom: 20px;'>
                      <hr>
                      <h3 style='color: #232F3F;'>Hello ".$_POST['name'].",</h3>
                      <p>Your Registration of  Poverty Eradication Directory Account has Successfull. Your Login Credentials are:</p>
                      <p>Username: <span style='background:#2196F3;color:white;padding:0px 5px'>".$to."</span></p>
                      <p>Password: <span style='background:#2196F3;color:white;padding:0px 5px'>".$pass."</span></p>
                      <hr>
              
                      <p>Warm Regards<br>Poverty Eradication Directory <br>Support Team</p>
                
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
            }

            $this->session->set_flashdata('err_msg', 'user Saved Successfully');
            redirect($this->controller."admin");
        }       
    }

    public function change_admin_status()
    {
        $auth_type = $this->session->userdata('auth_type');
        $user_id = $this->input->get('q');
        $q = $this->db->where('user_id',$user_id)->get('user');
        if($q->num_rows() > 0)
        {
            $oldData = $q->row_array();
            $status = $oldData['status'] ? 0 : 1;
            $this->db->where('user_id',$user_id)->update('user',array('status'=>$status));
            $this->session->set_flashdata('err_msg', 'Status Has been Changed Successfully');
            redirect($this->controller."admin"); 
        }
        else
        {
            $this->session->set_flashdata('err_msg', 'admin No Found');
            redirect($this->controller."admin");
        }
    }
    
    public function admin_delete()
    {
        $auth_type = $this->session->userdata('auth_type');
        $user_id = $this->input->get('q');
        $q = $this->db->where('user_id',$user_id)->get('user');
        if($q->num_rows() > 0)
        {
            $oldData = $q->row_array();
            $this->db->where('user_id',$user_id)->delete('user');
            unlink($oldData['user_image']);
            $this->session->set_flashdata('err_msg', 'user Deleted Successfully');
            redirect($this->controller."admin"); 
        }
        else
        {
            $this->session->set_flashdata('err_msg', 'Image No Found');
            redirect($this->controller."admin");
        }
    }
    
    public function user_activity()
    {
        $auth_type = $this->session->userdata('auth_type');
        $subadmin = $this->session->userdata('subadmin');
        
        $res['user_id'] = $this->input->get_post('user_id');
        $res['from'] = $this->input->get_post('from');
        $res['to'] = $this->input->get_post('to');
        
        if($res['user_id'] != ''){
            $this->db->where("a.user_id",$res['user_id']);
        }
        if($res['from'] != ''){
            $this->db->where("a.entrydt >=",$res['from']." 00:00:00");
        }
        if($res['to'] != ''){
            $this->db->where("a.entrydt <=",$res['to']." 23:59:59");
        }
        
        $this->db->where("u.subadmin",1);
        $this->db->select("a.*,u.first_name,u.last_name");
        $this->db->join("user u","u.user_id=a.user_id");
        $res['user_activity'] = $this->db->order_by('a.user_activity_id','DESC')->get('user_activity a')->result();
        
        $res['user_list'] = $this->db->where('type','admin')->where('subadmin','1')->order_by('first_name')->get('user')->result();
        $res['form'] = ($auth_type == 'admin' && $subadmin==0)?1:0;
        $res['list'] = ($auth_type == 'admin' && $subadmin==0)?1:0;
        $this->load->view('include/header');
        $this->load->view('include/sidebar');
        $this->load->view('user_activity',$res);
        $this->load->view('include/footer');
    }
    
    
    
}	
?>