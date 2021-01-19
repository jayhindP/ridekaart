<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Base extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
	}
	public function get_gst_details($gst_no)
    {
        $url = "https://cleartax.in/f/compliance-report/$gst_no/";
        $cURLConnection = curl_init();
        curl_setopt($cURLConnection, CURLOPT_URL, $url);
        curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($cURLConnection);
        curl_close($cURLConnection);
        $gstData = json_decode($result,TRUE);
        return $gstData;
    }
	public function category_name($category_id)
	{
		$category = $this->db->select('category_name')->where('category_id',$category_id)->get('category')->row_array();
		return $category['category_name'];
	}

	public function sub_category_name($product_sub_category_id)
	{
		$product_category = $this->db->select('product_sub_category_name')->where('product_sub_category_id',$product_sub_category_id)->get('product_sub_category')->row_array();
		return $product_category['product_sub_category_name'];
	}

	public function generate_code($name)
	{
		$db_name = $this->db->database;
        $row = $this->db->query("SELECT `AUTO_INCREMENT` FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '$db_name' AND TABLE_NAME = 'user'")->row_array();
        $code = strtoupper(substr($name, 0, 3)).$row['AUTO_INCREMENT'].rand(00,99);
        $code = preg_replace('/[^A-Z0-9\-]/', '', $code);
        return $code;
	}

	public function color_name($color_id)
	{
		$product_category = $this->db->select('color_name')->where('color_id',$color_id)->get('color')->row_array();
		return $product_category['color_name'];
	}

	public function product_name($product_id)
	{
		$product_category = $this->db->select('product_name')->where('product_id',$product_id)->get('product')->row_array();
		return $product_category['product_name'];
	}
	
	public function product_size($product_id)
	{
		$product = $this->db->select('qty')->where('product_id',$product_id)->get('product')->row_array();
		return $product['qty'];
	}
	
	public function user_name($user_id)
	{
		$data = $this->db->select('first_name,last_name')->where('user_id',$user_id)->get('user')->row_array();
		return $data['first_name']." ".$data['last_name'];
	}
	public function admin_name($admin_id)
	{
		$data = $this->db->select('admin_name')->where('admin_id',$admin_id)->get('admin')->row_array();
		return $data['admin_name'];
	}
	public function user_address($user_address_id)
	{
		$data = $this->db->select('address_name')->where('user_address_id',$user_address_id)->get('user_address')->row_array();
		return $data['address_name'];
	}
	
	public function validate_data($table_name,$data)
	{
		$cols = $this->db->select("GROUP_CONCAT(column_name)AS columns")->where('table_schema',$this->db->database)->where('table_name',$table_name)->get('information_schema.columns')->row_array();
		$colExist = explode(",",$cols['columns']);
		
		foreach($data as $colName => $value)
		{
		    if(!in_array($colName, $colExist)){
		      //  $this->db->query("ALTER TABLE $table_name ADD $colName TEXT NULL");
		      unset($data[$colName]);
		    }
		}
		
		return $data;
		
	}
	
	function push_firebase($registration_ids, $message)
	{
    	$url = 'https://fcm.googleapis.com/fcm/send';
    	$fields = array(
    		'registration_ids' => $registration_ids,
    		'data' => $message,
    	);
    	$headers = array(
    		'Authorization: key='."AAAAQCCC1U8:APA91bGNxrfh-Rnv7VcnLgyhJ9TNuZG2zWNWvgcwG7XjnL_mqCwTAbPLAlHrNelUdSqYz5w2eVHLOLdBiqoD_Eb5noImu3NFYpVcOWfcCwfa19wPpU76PwJOS_gglvcU54abkxAUvOTQ",
    		'Content-Type: application/json'
    	);

    	$ch = curl_init();
    	curl_setopt($ch, CURLOPT_URL, $url);
    	curl_setopt($ch, CURLOPT_POST, true);
    	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    	// Disabling SSL Certificate support temporarly
    	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
	    $result = curl_exec($ch);
	    curl_close($ch);
        return $result;
	}
	
	public function time_elapsed_string($datetime, $full = false) 
    {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);
    
        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;
    
        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }
    
        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }
	
	public function rating($user_id)
	{
		$data = $this->db->select_avg('rating')->where('user_id',$user_id)->get('rating')->row_array();
		return bcdiv($data['rating'],1,1);
	}
	
	
	#--------------------------Base Model END------------------------------
}
