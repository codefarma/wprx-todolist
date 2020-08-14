<?php
/**
 * TodoTask Model [ActiveRecord]
 *
 * Created:   August 13, 2020
 *
 * @package:  To Do List
 * @author:   Kevin Carwile
 * @since:    {build_version}
 */
namespace WPRX\TodoList\Models;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied.' );
}

use MWP\Framework\Pattern\ActiveRecord;

/**
 * TodoTask Class
 */
class _TodoTask extends ActiveRecord
{
	/**
	 * @var	array		Multitons cache (needs to be defined in subclasses also)
	 */
	protected static $multitons = array();
	
	/**
	 * @var	string		Table name
	 */
	protected static $table = 'todolist_tasks';
	
	/**
	 * @var	array		Table columns
	 */
	protected static $columns = array(
		'id',
		'title' => [ 'type' => 'varchar', 'length' => 255 ],
		'list_id' => [ 'type' => 'int', 'length' => 20 ],
		'status' => [ 'type' => 'varchar', 'length' => 32 ],
		'priority' => [ 'type' => 'int', 'length' => 5 ],
	);
	
	/**
	 * @var	string		Table primary key
	 */
	protected static $key = 'id';
	
	/**
	 * @var	string		Table column prefix
	 */
	protected static $prefix = '';
	
	/**
	 * @var bool		Site specific table? (for multisites)
	 */
	protected static $site_specific = TRUE;
	
	/**
	 * @var	string
	 */
	protected static $plugin_class = 'WPRX\TodoList\Plugin';
	
	/**
	 * @var	string
	 */
	public static $sequence_col = 'priority';
	
	/**
	 * @var	string
	 */
	public static $parent_col;

	/**
	 * @var	string
	 */
	public static $lang_singular = 'Task';
	
	/**
	 * @var	string
	 */
	public static $lang_plural = 'Tasks';
	
	/**
	 * @var	string
	 */
	public static $lang_view = 'View';

	/**
	 * @var	string
	 */
	public static $lang_create = 'Create';

	/**
	 * @var	string
	 */
	public static $lang_edit = 'Edit';
	
	/**
	 * @var	string
	 */
	public static $lang_delete = 'Delete';

	/**
	 * Get controller actions
	 *
	 * @return	array
	 */
	public function getControllerActions()
	{
		$actions = parent::getControllerActions();
		unset( $actions['view'] );
		return $actions;
	}

	/**
	 * Get editing form
	 *
	 * @return	MWP\Framework\Helpers\Form
	 */
	protected function buildEditForm()
	{
		$form = static::createForm( 'edit' );

		// Validate the list we are associating this task with
		try {
			$list_id = $this->list_id ?: (int) $_REQUEST['list_id'];
			$list = TodoList::load( $list_id );
		}
		catch( \OutOfRangeException $e ) {
			$form->addHtml( 'error', 'Invalid todo list id supplied in request url.' );
			return $form;
		}

		$form->addTab( 'details_tab', array(
			'title' => 'Details',
		));

		$form->addField( 'list_id', 'hidden', [
			'data' => $list_id 
		]);

		$form->addField( 'title', 'text', array(
			'label' => __( 'Title', 'wprx-todolist' ),
			'data' => $this->title,
			'required' => true,
		));

		$form->addField( 'status', 'choice', array(
			'label' => __( 'Task Status', 'wprx-todolist' ),
			'data' => $this->status ?: 'todo',
			'choices' => array(
				'To Do' => 'todo',
				'In Progress' => 'in_progress',
				'Completed' => 'completed',
			),
			'required' => true,
			'expanded' => true,
		));

		$form->addField( 'submit', 'submit', [ 
			'row_attr' => [ 'class' => 'text-center' ],
			'label' => 'Save', 
		], '');

		// Return to the parent screen after saving
		$form->onComplete( function() {
			$controller = TodoList::getController( 'admin' );
			wp_redirect( $controller->getUrl( [ 'do' => 'edit', 'id' => $this->list_id, '_tab' => 'tasks_tab' ] ) );
			exit;
		});		
		
		return $form;		
	}

	/**
	 * Process Form values
	 *
	 * @param   array   $values   form data
	 * @param   string  $type     the type of form
	 * @return  void
	 */
	public function processEditForm( $values )
	{
		parent::processEditForm( $values['details_tab'] );
	}	

}
