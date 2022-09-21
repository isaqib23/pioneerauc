<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter ACL Class
 *
 * This class enables you to apply permissions to controllers, controller and models, as well as more fine tuned
 * permissions at code level.
 *
 * @package     CodeIgniter
 * @subpackage  Libraries
 * @category    Libraries
 * @author      David Freerksen
 * @link        https://github.com/dfreerksen/ci-acl
 */
class Acl {

	protected $CI;

	/**
	 * Constructor
	 *
	 * @param   array   $config
	 */
	public function __construct($config = array())
	{
		$this->CI = &get_instance();

		$this->CI->load->model('Acl_Model');
	}

	/**
	 * Test if user has permission (permissions set in database)
	 *
	 * @access  public
	 * @param   string
	 * @return  bool
	 */
	public function has_permission()
	{
		$user = $this->CI->session->userdata('user_id');
	
		if ($user === FALSE)
		{
			$user = 0;
		}

		//get role ID
		$role_id = 1;
		$controller = $this->CI->router->fetch_class();
		$action = $this->CI->router->fetch_method();
		
		return $this->CI->acl_model->has_permission($controller,$action,$role_id);
	}
	
	/**
	 * Test if user has permission (permissions set in database)
	 *
	 * @access  public
	 * @param   string
	 * @return  bool
	 */
	public function check_permission($controller,$action,$role_id)
	{
		return $this->CI->acl_model->has_permission($controller,$action,$role_id);
	}

}
// END Acl class

/* End of file Acl.php */
/* Location: ./application/libraries/Acl.php */