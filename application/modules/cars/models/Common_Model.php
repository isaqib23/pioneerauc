<?php

class Common_Model extends CI_Model
{

  	function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->load->library('image_lib');
        $this->load->library('encryption');
    }
    function getFormatedDate($date){
		
		$date = date('m/d/Y', strtotime($date));
		return $date;
	}
	
	function getDate($date){
		
		$date = date('m/d/Y', strtotime($date));
	
		if($date == date('m/d/Y')) {
			$date = 'Today';
		} 
		else if($date == date('m/d/Y',time() - (24 * 60 * 60))) {
			$date = 'Yesterday';
		}
		return $date;
	}
	
	
	
	function uploadImage($picname,$path){
    	

    	$config['upload_path']   = $path; //'uploads/data/';
		$config['allowed_types'] = 'jpeg|jpg|png|JPG|JPEG|PNG';
		$config['max_size'] 	 = '1000000';
		$config['file_name']     = $picname;  // 'sliderimage_' . time() 

		$this->load->library('upload', $config);

		if ($this->upload->do_upload('file')){
			
			$image_details  = $this->upload->data();
			$picture_name 	= $image_details['file_name'];
					
			return $picture_name;
		}else{
			return false;
		}
    }
	public function decryptIt( $q ) {
	    $cryptKey  = 'qJB0rGtIn5UB1xG03efyCp';
	    $qDecoded  = rtrim( mcrypt_decrypt( MCRYPT_RIJNDAEL_256, md5( $cryptKey ), base64_decode( $q ), MCRYPT_MODE_CBC, md5( md5( $cryptKey ) ) ), "\0");
	    return( $qDecoded );
	}

	public function encryptIt( $q ) {
	    $cryptKey  = 'qJB0rGtIn5UB1xG03efyCp';
	    $qEncoded  = base64_encode( mcrypt_encrypt( MCRYPT_RIJNDAEL_256, md5( $cryptKey ), $q, MCRYPT_MODE_CBC, md5( md5( $cryptKey ) ) ) );
	    return( $qEncoded );
	}

	public function convertJqueryDateToSqlDate($date){
		$time_array = array(
						'00:00'=> 'AM 0:00',
						'00:10'=> 'AM 0:10',
						'00:20'=> 'AM 0:20',
						'00:30'=> 'AM 0:30',
						'00:40'=> 'AM 0:40',
						'00:50'=> 'AM 0:50',
						'01:00'=> 'AM 1:00',
						'01:10'=> 'AM 1:10',
						'01:20'=> 'AM 1:20',
						'01:30'=> 'AM 1:30',
						'01:40'=> 'AM 1:40',
						'01:50'=> 'AM 1:50',
						'02:00'=> 'AM 2:00',
						'02:10'=> 'AM 2:10',
						'02:20'=> 'AM 2:20',
						'02:30'=> 'AM 2:30',
						'02:40'=> 'AM 2:40',
						'02:50'=> 'AM 2:50',
						'03:00'=> 'AM 3:00',
						'03:10'=> 'AM 3:10',
						'03:20'=> 'AM 3:20',
						'03:30'=> 'AM 3:30',
						'03:40'=> 'AM 3:40',
						'03:50'=> 'AM 3:50',
						'04:00'=> 'AM 4:00',
						'04:10'=> 'AM 4:10',
						'04:20'=> 'AM 4:20',
						'04:30'=> 'AM 4:30',
						'04:40'=> 'AM 4:40',
						'04:50'=> 'AM 4:50',
						'05:00'=> 'AM 5:00',
						'05:10'=> 'AM 5:10',
						'05:20'=> 'AM 5:20',
						'05:30'=> 'AM 5:30',
						'05:40'=> 'AM 5:40',
						'05:50'=> 'AM 5:50',
						'06:00'=> 'AM 6:00',
						'06:10'=> 'AM 6:10',
						'06:20'=> 'AM 6:20',
						'06:30'=> 'AM 6:30',
						'06:40'=> 'AM 6:40',
						'06:50'=> 'AM 6:50',
						'07:00'=> 'AM 7:00',
						'07:10'=> 'AM 7:10',
						'07:20'=> 'AM 7:20',
						'07:30'=> 'AM 7:30',
						'07:40'=> 'AM 7:40',
						'07:50'=> 'AM 7:50',
						'08:00'=> 'AM 8:00',
						'08:10'=> 'AM 8:10',
						'08:20'=> 'AM 8:20',
						'08:30'=> 'AM 8:30',
						'08:40'=> 'AM 8:40',
						'08:50'=> 'AM 8:50',
						'09:00'=> 'AM 9:00',
						'09:10'=> 'AM 9:10',
						'09:20'=> 'AM 9:20',
						'09:30'=> 'AM 9:30',
						'09:40'=> 'AM 9:40',
						'09:50'=> 'AM 9:50',
						'10:00'=> 'AM 10:00',
						'10:10'=> 'AM 10:10',
						'10:20'=> 'AM 10:20',
						'10:30'=> 'AM 10:30',
						'10:40'=> 'AM 10:40',
						'10:50'=> 'AM 10:50',
						'11:00'=> 'AM 11:00',
						'11:10'=> 'AM 11:10',
						'11:20'=> 'AM 11:20',
						'11:30'=> 'AM 11:30',
						'11:40'=> 'AM 11:40',
						'11:50'=> 'AM 11:50',
						'12:00'=> 'PM 12:00',
						'12:10'=> 'PM 12:10',
						'12:20'=> 'PM 12:20',
						'12:30'=> 'PM 12:30',
						'12:40'=> 'PM 12:40',
						'12:50'=> 'PM 12:50',
						'13:00'=> 'PM 1:00',
						'13:10'=> 'PM 1:10',
						'13:20'=> 'PM 1:20',
						'13:30'=> 'PM 1:30',
						'13:40'=> 'PM 1:40',
						'13:50'=> 'PM 1:50',
						'14:00'=> 'PM 2:00',
						'14:10'=> 'PM 2:10',
						'14:20'=> 'PM 2:20',
						'14:30'=> 'PM 2:30',
						'14:40'=> 'PM 2:40',
						'14:50'=> 'PM 2:50',
						'15:00'=> 'PM 3:00',
						'15:10'=> 'PM 3:10',
						'15:20'=> 'PM 3:20',
						'15:30'=> 'PM 3:30',
						'15:40'=> 'PM 3:40',
						'15:50'=> 'PM 3:50',
						'16:00'=> 'PM 4:00',
						'16:10'=> 'PM 4:10',
						'16:20'=> 'PM 4:20',
						'16:30'=> 'PM 4:30',
						'16:40'=> 'PM 4:40',
						'16:50'=> 'PM 4:50',
						'17:00'=> 'PM 5:00',
						'17:10'=> 'PM 5:10',
						'17:20'=> 'PM 5:20',
						'17:30'=> 'PM 5:30',
						'17:40'=> 'PM 5:40',
						'17:50'=> 'PM 5:50',
						'18:00'=> 'PM 6:00',
						'18:10'=> 'PM 6:10',
						'18:20'=> 'PM 6:20',
						'18:30'=> 'PM 6:30',
						'18:40'=> 'PM 6:40',
						'18:50'=> 'PM 6:50',
						'19:00'=> 'PM 7:00',
						'19:10'=> 'PM 7:10',
						'19:20'=> 'PM 7:20',
						'19:30'=> 'PM 7:30',
						'19:40'=> 'PM 7:40',
						'19:50'=> 'PM 7:50',
						'20:00'=> 'PM 8:00',
						'20:10'=> 'PM 8:10',
						'20:20'=> 'PM 8:20',
						'20:30'=> 'PM 8:30',
						'20:40'=> 'PM 8:40',
						'20:50'=> 'PM 8:50',
						'21:00'=> 'PM 9:00',
						'21:10'=> 'PM 9:10',
						'21:20'=> 'PM 9:20',
						'21:30'=> 'PM 9:30',
						'21:40'=> 'PM 9:40',
						'21:50'=> 'PM 9:50',
						'22:00'=> 'PM 10:00',
						'22:10'=> 'PM 10:10',
						'22:20'=> 'PM 10:20',
						'22:30'=> 'PM 10:30',
						'22:40'=> 'PM 10:40',
						'22:50'=> 'PM 10:50',
						'23:00'=> 'PM 11:00',
						'23:10'=> 'PM 11:10',
						'23:20'=> 'PM 11:20',
						'23:30'=> 'PM 11:30',
						'23:40'=> 'PM 11:40',
						'23:50'=> 'PM 11:50',
					);
		$date_array = explode(" ",$date);
		$key = array_search($date_array[1]." ".$date_array[2],$time_array);
		return $date_array[0]." ".$key;
	}
	
	function getStatesArray(){
		$states=array(
			'AL'=>'Alabama',
			'AK'=>'Alaska',
			'AZ'=>'Arizona',
			'AR'=>'Arkansas',
			'CA'=>'California',
			'CO'=>'Colorado',
			'CT'=>'Connecticut',
			'DE'=>'Delaware',
			'DC'=>'District of Columbia',
			'FL'=>'Florida',
			'GA'=>'Georgia',
			'HI'=>'Hawaii',
			'ID'=>'Idaho',
			'IL'=>'Illinois',
			'IN'=>'Indiana',
			'IA'=>'Iowa',
			'KS'=>'Kansas',
			'KY'=>'Kentucky',
			'LA'=>'Louisiana',
			'ME'=>'Maine',
			'MD'=>'Maryland',
			'MA'=>'Massachusetts',
			'MI'=>'Michigan',
			'MN'=>'Minnesota',
			'MS'=>'Mississippi',
			'MO'=>'Missouri',
			'MT'=>'Montana',
			'NE'=>'Nebraska',
			'NV'=>'Nevada',
			'NH'=>'New Hampshire',
			'NJ'=>'New Jersey',
			'NM'=>'New Mexico',
			'NY'=>'New York',
			'NC'=>'North Carolina',
			'ND'=>'North Dakota',
			'OH'=>'Ohio',
			'OK'=>'Oklahoma',
			'OR'=>'Oregon',
			'PA'=>'Pennsylvania',
			'RI'=>'Rhode Island',
			'SC'=>'South Carolina',
			'SD'=>'South Dakota',
			'TN'=>'Tennessee',
			'TX'=>'Texas',
			'UT'=>'Utah',
			'VT'=>'Vermont',
			'VA'=>'Virginia',
			'WA'=>'Washington',
			'WV'=>'West Virginia',
			'WI'=>'Wisconsin',
			'WY'=>'Wyoming'
		);
		return $states;
	}
	//start invoice
	function changeDateToMysql($date){
	
		$d_array = explode('/',$date);
		return $d_array[2]."-".$d_array[0]."-".$d_array[1];
	}
	
	public function sendMail($template_name,$arr,$from_name,$from_email,$subject,$message_user)
	{
		$from_name   = $from_name;
		$from_email  = $from_email;
		$subject     = $subject;

		foreach($arr as $key=>$val){
			if(preg_match("/\[(".$key.")]/",$from_name,$m)){
			//if(!strpos($subject,'['.$key.']')===false){
				$from_name=str_replace('['.$key.']',$val,$from_name);
			}
		}
		foreach($arr as $key=>$val){
			if(preg_match("/\[(".$key.")]/",$from_email,$m)){
			//if(!strpos($subject,'['.$key.']')===false){
				$from_email=str_replace('['.$key.']',$val,$from_email);
			}
		}
		
		foreach($arr as $key=>$val){
			if(preg_match("/\[(".$key.")]/",$subject,$m)){
			//if(!strpos($subject,'['.$key.']')===false){
				$subject=str_replace('['.$key.']',$val,$subject);
			}
		}
		$message= $message_user;
		$header="From: $from_name <$from_email>\r\n";
		$header.="Content-type:text/html";
	    $this->load->library('email');
		$ok=true;
		$this->email->to($arr['email']);
		$this->email->from($from_email, $from_name);
		$this->email->subject($subject);
		$this->email->message($message);
		$this->email->reply_to($from_email, $from_name);
		$this->email->set_mailtype("html");
		$result = $this->email->send();
		$ok=1;
		if($result){
			return true;
		}
		else{
			return false;
		}
	}

	public function createqrcode($id)
	{
	 	$PNG_TEMP_DIR = $_SERVER["DOCUMENT_ROOT"].'/upload/qr_code/';
	    require_once($_SERVER["DOCUMENT_ROOT"]."/phpqrcode/qrlib.php");    
		$filename = $PNG_TEMP_DIR.md5($id).'.png';
	    QRcode::png($id, $filename, 'L', 8, 2);
	}



	public function for_api_upload_files()
	{ 
		$image=$_FILES['userfile']['name'];
		$userid=$_GET['id'];
		$path = './upload/profile_picture';
		$this->load->library('image_lib');
		$ext = strrchr($image, ".");
		if($ext=='')
		$ext = '.jpg';
		$fileName = 'customer_profile_'.$userid.''.$ext;
		$data_info = array( 
		        'profile_picture' 	=> $fileName,
		    );
		$check_user = $this->patient_model->update_customer($_GET['id'], $data_info);
		$filePath = $path.'/'.$fileName;
		$config = array(
			'upload_path' => $path,
			'allowed_types' => '*',
			'max_size' => 50000,
			'max_width' => 50000,
			'max_height' => 50000,
			'file_name' => $fileName,
			'overwrite' => true 
			);
		$this->load->library('upload', $config);
		$this->upload->initialize($config);
		if ($this->upload->do_upload('userfile')) 
		{
			$return_upload_data = $this->upload->data();
			$config2['image_library'] = 'gd2';
			$config2['new_image'] = $config2['source_image'] = $filePath;
			$config2['create_thumb'] = TRUE;
			$config2['maintain_ratio'] = TRUE; 
			list($width, $height) = getimagesize($config2['source_image']);
			if ($width < $height)
			{
				$config2['width'] = 268;
			}
			else
			{
				$config2['height'] = 268;
			}
			$config2['thumb_marker'] = false;
			$this->image_lib->clear();
			$this->image_lib->initialize($config2);
			$this->image_lib->resize();
			$config=array();
			$config['image_library'] = 'gd2';
			$config['source_image'] = $config2['source_image'];
			$this->image_lib->initialize($config); 
			$this->image_lib->rotate();
			$data['success']   = true;
			$data['error']   = $return_upload_data;
			echo json_encode($data);
			exit();
		}
		else 
		{
			$data['success']   = false;
			$data['error']   = $this->upload->display_errors();
			echo json_encode($data);
			exit();
		} 
	}

	public function upload_files_venue_picture_with_extra($newname,$path,$image,$name,$thumb_width_set,$thumb_height_set) 
	{
		list($width, $height, $type, $attr) = @getimagesize($_FILES[$name]['tmp_name']);
		$this->load->library('image_lib');


		$ext = strrchr($image, ".");
		$fileName = $newname.''. $ext;
		$config = array(
			'upload_path' => $path,
			'allowed_types' => 'jpg|gif|png|jpeg',
			'max_size' => 500000,
			'max_width' => 500000,
			'max_height' => 500000,
			'file_name' => $fileName,
			'overwrite' => 1,
		);
		$this->load->library('upload', $config);
		$this->upload->initialize($config);

		if ($this->upload->do_upload($name)) 
		{
			$uploadreturn = $this->upload->data();
			$config2 = array();
			$config2['image_library'] = 'gd2';
			$config2['source_image'] = $path . '' . $fileName;
			$config2['new_image'] = $path.'' . $fileName;
			$config2['create_thumb'] = TRUE;
			$config2['maintain_ratio'] = TRUE;
			$config2['thumb_marker'] = false;
			$config2['height'] = $thumb_height_set;
			$config2['master_dim'] = 'height';
			$ratio  = $width/$height;
			$calculated_width = $ratio * $thumb_height_set;
			if($calculated_width>= $thumb_width_set)
			{
				
			}
			else
			{
				$corping_height = ($width/$thumb_width_set) * $thumb_height_set;
				$config_crop = array(
					'image_library' => 'gd2',
					'maintain_ratio' => false, 
				);
				$config_crop['source_image'] = $path . '' . $fileName;
				$config_crop['new_image']= $path . '' . $fileName;
				$config_crop['x_axis'] = 0;
				$config_crop['width']= $width;
				$config_crop['height']= (int)($corping_height);
				$config_crop['y_axis'] = (int)(($height - $corping_height)/2);
				$this->image_lib->initialize($config_crop);
				if ( ! $this->image_lib->crop())
				{
					echo $this->image_lib->display_errors();die;
				}
				list($width, $height, $type, $attr) = @getimagesize($path . '' . $fileName);
			}
			$this->image_lib->clear();
			$this->image_lib->initialize($config2);
			$this->image_lib->resize();
			$this->image_lib = new CI_Image_Lib();
			unset($this->image_lib);
			return $uploadreturn;
		}
		else 
		{
			return false;
		}
	}
}