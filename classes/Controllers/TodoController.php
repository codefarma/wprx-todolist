<?php
/**
 * TodoController [ActiveRecordController]
 *
 * Created:   August 24, 2020
 *
 * @package:  To Do List
 * @author:   Kevin Carwile
 * @since:    {build_version}
 */
namespace WPRX\TodoList\Controllers;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied.' );
}

use MWP\Framework\Helpers\ActiveRecordController;

/**
 * TodoController Class
 */
class _TodoController extends ActiveRecordController
{
	/**
	 * Initialize
	 *
	 * @return    void
	 */
	public function init()
	{
		
	}
}
