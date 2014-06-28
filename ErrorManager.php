<?php
class ErrorManager {
	private $_handler = false;

	public function __construct() {
		$this->enableErrorHandler();
		$this->enableAllErrors();
	}

	/**
	 *	A easy way to show all the content of one var styled properly
	 * 	@param $var the var that you whant to explore
	 */
	public static function showVar($var){
		echo '<pre>', var_dump($var), '</pre>';
	}

	/**
	 *	Add a specific point in the page that kill all the rendering and show a speficig message
	 * 	@param $msg Its the message to show when the page die
	 *	@param $active defines if this breakpoint its active or just zombie. If false the break
	 *	will not ocure.
	 */
	public static function breakPoint($msg = '', $active = true) {
		if($active === true) die($msg);
	}

	/**	Internal function that enable ErrorHandling if its not already change'd */
	private function enableErrorHandler() {
		if($this->_handler === false){
			set_error_handler(array($this, 'ErrorHandler'));
			$this->_handler = true;
		}
	}

	/**
	 *	Change the gobal variable in php to show all the errors or advises
	 *	@param value set on or of that property
	 */
	public static function enableAllErrors($value = true){
		if($value) {
			error_reporting(E_ALL);
		}else {
			error_reporting(E_ERROR | E_WARNING | E_PARSE);
		}
	}

	/**
	 *	This function will trigger an error
	 *	@param $msg text that will show on the error handler
	 *	@param $type the type of the error that you whant to trigger
	 */
	public function showError($msg, $type = E_USER_ERROR){
		$this->enableErrorHandler();
		trigger_error($msg, $type);
	}

	// try to change to private -> its works :D
	private static function ErrorHandler($errno, $errstr, $errfile, $errline) {
	    if (!(error_reporting() & $errno)) {
	        echo "<p>The Errorhandler dont understand the error code.</p>";
	    }

        $errfile = end(explode("/", $errfile));
	    switch ($errno) {
		    case E_USER_ERROR:
		    	echo '<h1>Error</h1>';
		        echo "<p><strong>Error[line $errline, file $errfile]:</strong> $errstr, PHP " . PHP_VERSION . " (" . PHP_OS . ")</p>\n";
		        exit(1);
		        break;

		    case E_USER_WARNING:
		        echo "<p><Warning[$errline, $errfile]: <strong>$errstr</strong></p>\n";
		        break;

		    case E_USER_NOTICE:
		        echo "<p>Notice[$errline, $errfile]: <strong>$errstr</strong></p>\n";
		        break;

		    default:
		        echo "<p>Unknown error type [$errline, $errfile]: <strong>$errstr</strong></p>\n";
		        break;
	    }

	    /* Don't execute PHP internal error handler */
	    return true;
	}
}