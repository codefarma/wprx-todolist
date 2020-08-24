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

	/**
	 * Copy a record
	 *
	 */
	public function do_copy()
	{
		$controller = $this;
		$class = $this->recordClass;
		
		if ( ! $record ) {
			try	{
				$record = $class::load( isset( $_REQUEST['id'] ) ? $_REQUEST['id'] : 0 );
			}
			catch( \OutOfRangeException $e ) { 
 				echo $this->error( __( 'The record could not be loaded.', 'mwp-framework' ) . ' Class: ' . $this->recordClass . ' ' . ', ID: ' . ( (int) $_REQUEST['id'] ) );
				return;
			}
		}

		$copy = $record->copy();
		wp_redirect( $controller->getUrl([ 'do' => 'edit', 'id' => $copy->id() ]) );
		exit;
	}	
}
