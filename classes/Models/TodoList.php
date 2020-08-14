<?php
/**
 * TodoList Model [ActiveRecord]
 *
 * Created:   August 10, 2020
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
 * TodoList Class
 */
class _TodoList extends ActiveRecord
{
	/**
	 * @var	array		Multitons cache (needs to be defined in subclasses also)
	 */
	protected static $multitons = array();
	
	/**
	 * @var	string		Table name
	 */
	protected static $table = 'todo_lists';
	
	/**
	 * @var	array		Table columns
	 */
	protected static $columns = array(
		'id',
		'title' => [ 'type' => 'varchar', 'length' => 255 ],
		'user_id' => [ 'type' => 'int', 'length' => 20 ],
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
	public static $sequence_col;
	
	/**
	 * @var	string
	 */
	public static $parent_col;

	/**
	 * @var	string
	 */
	public static $lang_singular = 'List';
	
	/**
	 * @var	string
	 */
	public static $lang_plural = 'Lists';
	
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

		$form->addTab( 'details_tab', array(
			'title' => __( 'Details', 'wprx-todolist' ),
		));
		
		$form->addField( 'title', 'text', array(
			'label' => __( 'Title', 'wprx-todolist' ),
			'data' => $this->title,
			'required' => true,
		));

		$form->addField( 'user_id', 'text', array(
			'label' => __( 'List Owner (user id)', 'wprx-todolist' ),
			'data' => $this->user_id,
			'required' => true,
		));

		// Add a tab to manage the tasks associated with this list. But ONLY if this list has been saved 
		// previously and has an ID. Otherwise, there is no point in trying to associate tasks with it
		if ( $this->id() ) {

			// Add a tab to contain our task list
			$form->addTab( 'tasks_tab', array(
				'title' => __( 'Tasks', 'wprx-todolist' ),
			));

			// Embed a TodoTask management table into our form
			$form->embedRecords( 'tasks_table', TodoTask::getController('admin'), array(
				// customize a few aspects of our embedded display table
				'tableConfig' => [ 'bulkActions' => [], 'perPage' => 10000 ],
				
				// specify criteria to select the records which are shown on this table
				'itemsWhere' => [ 'list_id=%d', $this->id() ],
				
				// specify url parameters to add to management links (add, edit, etc) in this table
				'actionParams' => [ 'list_id' => $this->id() ],
			));

		}

		$form->addField( 'submit', 'submit', [ 
			'row_attr' => [ 'class' => 'text-center' ],
			'label' => 'Save', 
		], '');

		if ( ! $this->id() ) {
			// Redirect to the tasks tab as the next step after creating a new list
			$form->onComplete( function() {
				$controller = static::getController( 'admin' );
				wp_redirect( $controller->getUrl( [ 'do' => 'edit', 'id' => $this->id(), '_tab' => 'tasks_tab' ] ) );
				exit;
			});
		}
		
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
