<?php
/**
 * @copyright	WebInteractiv
 * @author		Cotin Urse <costin.urse@gmail.com>
 * @desc		View Class
 * @package		core
 * since		April 16, 2010
 *
 */

namespace Core;

use Core\Qexception;

class View {
	/**
	 * Templates disk location
	 * 
	 * @var string
	 */
	protected $templates_dir;
	
	/**
	 * Array of variables assigned to template
	 * 
	 * @var array
	 */
	private $assigns = array ( );
	
	/**
	 * Constructor. Assigns default template dir.
	 */
	public function __construct() {
		$this->templates_dir = CONFIG_DIR_APPLICATION . DIRECTORY_SEPARATOR . CONFIG_DIR_VIEWS;
	}
	
	/**
	 * Assigns value to a template variable
	 * 
	 * @param string $var
	 * @param mixed $value
	 */
	public function assign($var, $value = NULL) {
		if (is_array ( $var )) {
			$this->assigns [] = $var;
		} else {
			$arr [$var] = $value;
			$this->assigns [] = $arr;
		}
	}
	
	/**
	 * Displays a template
	 * 
	 * @param string $template
	 */
	public function display($template = '') {
		$template = $this->templates_dir . DIRECTORY_SEPARATOR . $template;
		
		if (! is_file ( $template )) {
			//debug_print_backtrace();
			//$debug = debug_backtrace();
			//print_r($debug[1]);
			throw new Qexception ( "VIEW: Template $template doesn't exist." );
		}
		
		for($i = 0; $i < count ( $this->assigns ); $i ++)
			extract ( $this->assigns [$i] );
		return require ($template);
	}
	
	/**
	 * Fetches compiled content of a template. Same thing as function display, but content is returned in variables, instead of stream. 
	 * 
	 * @param string $template
	 * @return string
	 */
	public function fetch($template) {
		ob_start();
		if(CONFIG_DISPLAY_TEMPLATE_DEBUG == true) {
			echo "\n<!-- ".$template." BEGIN -->\n";
		}
		$this->display($template);
		if(CONFIG_DISPLAY_TEMPLATE_DEBUG == true) {
			echo "\n<!-- ".$template." END -->\n";
		}
		
		$data = ob_get_clean();
		
		if (CONFIG_MINIFY_HTML == true) {
			$data = htmlPacker_Util::minify($data);
		}	
		return $data; //ob_get_clean();
	}
	
	/**
	 * Assigns variables and parses template. Output is returned.
	 * 
	 * @param string $template
	 * @param array $assigns
	 * 
	 * @return string
	 */
	public static function DisplayTemplate($template, $assigns = NULL) {
		$view = new self ( );
		if ($assigns != NULL) {
			$view->assign ( $assigns );
		}
		return $view->fetch ( $template );
	}
}