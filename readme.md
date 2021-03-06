# WordPress Plugin Tutorial

**Project Description:**

Build a todo list plugin for WordPress which allows the user to create "To Do" lists, add tasks to their to do lists, and track the status of those tasks.

Initial Requirements:

* Users can create new To Do lists with the following attributes: (Title, Owner)
* Users can create tasks associated with To Do lists with the following attributes: (Title, Status, List Order)
* Users can update the status of tasks in the To Do list

## Scaffolding 

If you don't already have a local WP install which is already in MWP developer mode, follow the [setup instructions here](https://www.codefarma.com/docs/mwp-framework/setup/).

### Update Framework:
`$ wp plugin install https://github.com/codefarma/mwp-framework/raw/master-2.x/builds/mwp-framework-stable.zip --activate --force`

```
Downloading installation package from https://github.com/codefarma/mwp-framework/raw/master-2.x/builds/mwp-framework-stable.zip...
Unpacking the package...
Installing the plugin...
Removing the old version of the plugin...
Plugin updated successfully.
Activating 'mwp-framework'...
Warning: Plugin 'mwp-framework' is already active.
Success: Installed 1 of 1 plugins.
```

### Update Boilerplate:
`$ wp mwp update-boilerplate`

```
Downloading package...
Extracting package...
Updating boilerplate plugin...
Success: Boilerplate successfully updated.
```

### Scaffold a new plugin:
`$ wp mwp create-plugin "To Do List" --vendor="WPRX" --namespace="WPRX\TodoList" --slug="wprx-todolist" --author="Kevin Carwile" --slug="wprx-todolist" --description="A WordPress plugin that helps you keep track of the things you need to to."`

```
Creating plugin...
Success: Plugin successfully created in 'wprx-todolist'.
```

### Activate the plugin:
`$ wp plugin activate wprx-todolist`

```
Plugin 'wprx-todolist' activated.
Success: Activated 1 of 1 plugins.
```

### Make the initial commit for the plugin:
`$ cd wp-content/plugins/wprx-todolist`  
`$ git init`  
`$ git add -A && git commit -m "Inital commit"`  


Reference:  
https://github.com/codefarma/wprx-todolist/commit/9f1f67f64d331ae6af7d30e5bb4ff549506f7fc9


### Checkout a new branch to begin our development:
`$ git checkout -b "mvp-features"`

```
Switched to a new branch 'mvp-features'
```

## Building

Now that we have a new plugin scaffolded and checked in to source control, it's time to start building out our plugin.

The first thing that we should do is to think about the data that our plugin is going to need to manage, and then build out the models for that data. Data is modeled using the `ActiveRecord` class which is built into the framework. Based on the early requirements, it seems that we are going to need to have two sets of data:

* To Do Lists
* Tasks

### Create TodoList Model

Let's start by creating a new model for a To Do list.

`$ wp mwp add-class wprx-todolist "Models\TodoList" --type=model`

```
Creating new plugin class file...
Success: Class added sucessfully.
```

```php
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
	protected static $table = 'todolist_todolist';
	
	/**
	 * @var	array		Table columns
	 */
	protected static $columns = array(
		'id',
		'title' => [ 'type' => 'varchar', 'length' => 255 ],
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
	protected static $site_specific = FALSE;
	
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
	public static $lang_singular = 'Record';
	
	/**
	 * @var	string
	 */
	public static $lang_plural = 'Records';
	
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

}

```

Reference:  
https://github.com/codefarma/wprx-todolist/commit/b729d9d047865689ec81697a1fda80f0e6f6910a

### Customize TodoList Model

Now let's make some adjustments to the auto-generated model to fit our needs, and be a bit more human friendly.

#### Make database table name more human friendly:

```php
 	/**
 	 * @var	string		Table name
 	 */
-	protected static $table = 'todolist_todolist';
+	protected static $table = 'todo_lists';
 	
 	/**
 	 * @var	array		Table columns
```

Reference:  
https://github.com/codefarma/wprx-todolist/commit/4d6d0f44afe59123aaeac186ae4659b72e8e64c9

#### Add an attribute to track the list owner

```php
	protected static $columns = array(
		'id',
		'title' => [ 'type' => 'varchar', 'length' => 255 ],
+		'user_id' => [ 'type' => 'int', 'length' => 20 ],
	);
	
	/**
```

Reference:  
https://github.com/codefarma/wprx-todolist/commit/44082afcd16b6906ad128bace3602de4a4e0f9db

#### Allow every site in a multisite install to have its own separate to do lists:

```php
 	/**
 	 * @var bool		Site specific table? (for multisites)
 	 */
-	protected static $site_specific = FALSE;
+	protected static $site_specific = TRUE;
 	
 	/**
 	 * @var	string
```

Reference:  
https://github.com/codefarma/wprx-todolist/commit/0d55bd3748815bf2a13addccd39c87da31a69ec5

#### Adjust the human readable name of the TodoList model

```php
 	/**
 	 * @var	string
 	 */
-	public static $lang_singular = 'Record';
+	public static $lang_singular = 'List';
 	
 	/**
 	 * @var	string
 	 */
-	public static $lang_plural = 'Records';
+	public static $lang_plural = 'Lists';
 	
 	/**
 	 * @var	string
```

Reference:  
https://github.com/codefarma/wprx-todolist/commit/3d89e83724fc61b9728c796804c44b715e023733

### Provision Database Tables for TodoList Model

Now that we have completed our adjustments to the new TodoList model, it's time to provision a table in our database to store our to do lists in.

`$ wp mwp deploy-table wprx-todolist "Models\TodoList"`

```
Created table wp_todo_lists
Success: Database table updated.
```

After we've made changes to our local database so that our plugin can save its data, we need to track those changes in our plugin so that end users installing our plugin will have the same schema created for them in their own database.

`$ wp mwp update-schema wprx-todolist`

```
Success: Plugin schema generated/updated.
```

Reference:  
https://github.com/codefarma/wprx-todolist/commit/ebd11bb33aeb500d66f80dca8acab8a9f5f3b381

At this point, we are now fully capable of using our TodoList model class to CRUD data and persist it to the database. To test it out, use your favorite PHP Console for WordPress to execute the following code:

```php
$list = new WPRX\TodoList\Models\TodoList;
$list->title = "My First List!";
$list->user_id = 1;
$list->save();

print_r("My new list has ID: " . $list->id());
```

## Generate an Admin Interface

The next thing we want to do is generate an administrative interface in the WP Admin that admin users can use to manage all to do lists using a GUI. There is a pattern for this already built into the MWP Framework.

The preferred hook to register your admin interfaces using the MWP Framework is the 'mwp_framework_init' action. Therefore, an ideal place to put the code which registers your admin pages is inside the existing callback which is already registered to the 'mwp_framework_init' action by your plugin.php file.

```php
use WPRX\TodoList\Models;

	/* Register settings to a WP Admin page */
	// $framework->attach( $settings );

	Models\TodoList::createController('admin', [
		'adminPage' => [
			'title' => 'To Do Lists',
			'menu' => 'To Do Lists',
			'type' => 'menu',
			'menu_submenu' => 'Lists',
			'icon' => 'dashicons-editor-ol',
		],
	]);
} );
```

Reference:  
https://github.com/codefarma/wprx-todolist/commit/901efbd398ed7cd92b4521cdb85ac9ad60b7fb63

Let's add a new to do list:

* WP Admin -> To Do Lists -> Create List
* Title: My First List, User Id: 1
* Click 'Save'

## Customize Your Admin Interface

We're doing great. Now lets customize the display table on the To Do Lists admin page to make some of the displayed columns more user friendly. We're going to make the User Id column show the user name instead of the user id, and we'll remove the Id column from the display.

```php
		'tableConfig' => [
			'columns' => [
				'title' => __( 'Title', 'wprx-todolist' ),
				'user_id' => __( 'Owner', 'wprx-todolist' ),
			],
			'handlers' => [
				'user_id' => function( $row ) {
					if ( $user = get_user_by('id', $row['user_id']) ) {
						return "{$user->display_name} ({$user->user_email})"; 
					}

					return "--";
				}
			],
		]
```

Reference:  
https://github.com/codefarma/wprx-todolist/commit/23e3de5cd4f4f2fe58e4138093c7b1ad2b2b4a8c

## Create TodoTask Model

So now that we are able to create todo lists for users, we now need to be able to create tasks that are associated with those to do lists to keep track of what our users need to do! We'll walk through the same process to create a new TodoTask that we did to create the TodoList. We are going to also want to associate todo tasks with a todo list, so we'll need a parent reference attribute on the model.

`$ wp mwp add-class wprx-todolist "Models\TodoTask" --type=model`

```
Success: Class added sucessfully.
```

**Customizations:**

```php
 	/**
 	 * @var	string		Table name
 	 */
-	protected static $table = 'todolist_todotask';
+	protected static $table = 'todolist_tasks';
 	

	protected static $columns = array(
		'id',
		'title' => [ 'type' => 'varchar', 'length' => 255 ],
+		'list_id' => [ 'type' => 'int', 'length' => 20 ],
+		'status' => [ 'type' => 'varchar', 'length' => 32 ],
	);

 	/**
 	 * @var bool		Site specific table? (for multisites)
 	 */
-	protected static $site_specific = FALSE;
+	protected static $site_specific = TRUE;
```

References:  
https://github.com/codefarma/wprx-todolist/commit/e49ad7cd52cbd3229ee943e6dd56dee5a9fff0d4  
https://github.com/codefarma/wprx-todolist/commit/cbe6cb46d7fe46e977e1cf03e1aba3b884465ed0  

`$ wp mwp deploy-table wprx-todolist "Models\TodoTask"`

```
Created table wp_todolist_tasks
Success: Database table updated.
```

`$ wp mwp update-schema wprx-todolist`

```
Success: Plugin schema generated/updated.
```

Reference:  
https://github.com/codefarma/wprx-todolist/commit/01deb7c62a2376af39e2a836c0a2b41765aa7d45

We'll also want to create an admin interface that is used for CRUD'ing the task data as well.

```php
	Models\TodoTask::createController('admin', [
		'adminPage' => [
			'title' => 'To Do Tasks',
			'menu' => 'Tasks',
			'type' => 'submenu',
			'parent' => Models\TodoList::getController('admin')->adminPage->slug,
		],
		'tableConfig' => [
			'columns' => [
				'title' => __( 'Title', 'wprx-todolist' ),
				'status' => __( 'Task Status', 'wprx-todolist' ),
			],
		]
	]);	
```

Reference:  
https://github.com/codefarma/wprx-todolist/commit/38870e6162ed9a12bd7a333a8945598035fcd45e

Things are moving along nicely. But it's become obvious that todo tasks would be better managed through the associated todo list so we can see which tasks are associated with any given list. There is a common pattern that we can use to accomplish this, which is to embed a management table into a tab on the edit form for a `TodoList`, so that the tasks for that list can be managed from the edit screen of the list itself. We will also limit the tasks shown on that management table to only tasks that belong to the list being edited.

To pull this off, we need to override the form builder for the `TodoList` model and generate our own customized form with the necessary elements.

```php
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
		
		return $form;		
	}	
```

Reference:  
https://github.com/codefarma/wprx-todolist/commit/4788270c539eddb5c65d085532f32456889b1f47

And since we are modifying the structure of the edit form slightly by embedding our basic TodoList details into their own tab, we must also account for that in the form processing callback so that the TodoList details are saved correctly when the form is submitted.

```php
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
```

Reference:  
https://github.com/codefarma/wprx-todolist/commit/fcbc48eaf0925ad42758a7ca29994d54eee6fe05

Now you can create a new task for your todo list that you created earlier by visiting the edit screen of the todo list and going to the "Tasks" tab. Click the "Create Task" button and fill in the details and then click 'Save'.

* Title: Complete the tutorial
* List Id: 1
* Status: todo

We have now successfully created a TodoList and assigned a TodoTask to it!  

But you'll notice that after saving the new task, we were redirected back to the main tasks management screen that shows ALL system tasks, and not the "Tasks" tab of the TodoList that we were on previously. We also had to manually enter the list id when we created the task, which is not an ideal user experience. So the next thing we should do is to tap into the edit form for the TodoTask the same as we did with the TodoList and make some customizations.

### Automatically assign the list id

```php
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
```

Reference:  
https://github.com/codefarma/wprx-todolist/commit/dc11e7b10f26196a40215c334a7b486a2f1a1082

### Redirect upon successful form submission

There are two redirects that we can do to improve the workflow and UX for our admin users. One would be to redirect back to the "Tasks" tab on the list edit screen after creating/editing a task item.

```php
		// Return to the parent screen after saving
		$form->onComplete( function() {
			$controller = TodoList::getController( 'admin' );
			wp_redirect( $controller->getUrl( [ 'do' => 'edit', 'id' => $this->list_id, '_tab' => 'tasks_tab' ] ) );
			exit;
		});		
```

Reference:  
https://github.com/codefarma/wprx-todolist/commit/a1a9e679a23780d635448549b5fe4701241f72be

The other improvement we can make is to redirect to the "Tasks" tab automatically after creating a new TodoList record, since the logical next step to creating a todo list will be to add some tasks to it.

```php
		if ( ! $this->id() ) {
			// Redirect to the tasks tab as the next step after creating a new list
			$form->onComplete( function() {
				$controller = static::getController( 'admin' );
				wp_redirect( $controller->getUrl( [ 'do' => 'edit', 'id' => $this->id(), '_tab' => 'tasks_tab' ] ) );
				exit;
			});
		}
```

Reference:  
https://github.com/codefarma/wprx-todolist/commit/f9101bef748a5438c712cc27249af95883956a86

### Customize the action links in the management table

So now that we're on a roll, let's make some more UX adjustments to our management tables to improve things a bit further. Another thing we can do is to adjust the action links that display for each record in a management table. By default, the MWP framework adds an action link to "View", "Edit", and "Delete" records. The default "View" screen for a record just shows a table with the values for all columns on the record. In many cases, the view screen for a record is not really needed as the edit screen also serves as a view screen for the most part, so we can make life easier by just removing the "View" action link altogether.

```php
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
```

Reference:
https://github.com/codefarma/wprx-todolist/commit/c4c4b89c2d6d31d11a07e03641d153398995adca

### Add Behavior To Delete Children Nodes

It may now occur to you that if we delete a todo list from the system, any todo tasks that have been created for it will become orphaned. Knowing this, let's take a moment and delete any todo tasks associated with a todo list when the todo list is deleted. We'll use the built in `delete()` method which is part of the `ActiveRecord` design pattern and delete any child tasks on the fly.

Reference:  
https://github.com/codefarma/wprx-todolist/commit/a330199d5a42e6b02cdcb2108d40fde7d55d7cb4

### Make the tasks in a list re-orderable

Another quick improvement that we can make is to allow the end user to re-order their tasks within a list. When records need to be sequenced, you can use the MWP Framework to help out. By adding a new column to save the task priority level to, and specifying that column as the 'sequence' column on the `TodoTask` model, we can quickly gain re-ordering capabilities.

* When specifying a sequence column on a model, records loaded with the `loadWhere()` class method will automatically be ordered by the sequence column as default ordering.
* When viewing a record management table, you'll automatically be able to drag and drop records into different positions to adjust their sequence.

```php
	/**
	 * @var	array		Table columns
	 */
	protected static $columns = array(
		'id',
		'title' => [ 'type' => 'varchar', 'length' => 255 ],
		'list_id' => [ 'type' => 'int', 'length' => 20 ],
		'status' => [ 'type' => 'varchar', 'length' => 32 ],
+		'priority' => [ 'type' => 'int', 'length' => 5 ],
	);

 	/**
 	 * @var	string
 	 */
-	public static $sequence_col;
+	public static $sequence_col = 'priority';

```

Reference:  
https://github.com/codefarma/wprx-todolist/commit/6d2f5ee70fed4ecbd15478bcd7f1060f812c83a5

And after adding a new column to a model, we will need to deploy that column to our database, and then update our plugin schema with the details of that column definition.

`$ wp mwp deploy-table wprx-todolist all`

```
Added column wp_todolist_tasks.priority
```

`$ wp mwp update-schema wprx-todolist`

```
Success: Plugin schema generated/updated.
```

Reference:  
https://github.com/codefarma/wprx-todolist/commit/a38c5039ddccb59612aace603a8dba7612a34630

### Add additional todo list details on management table

The management table that we configured to allow us to CRUD the todo lists in the system is not limited to showing just data points that exist as columns on the model. We can add our own additional data points to the display as well. Let's add a column that shows the total number of tasks that are associated with any given todo list on its management table.

```php
		'tableConfig' => [
			'columns' => [
				'title' => __( 'Title', 'wprx-todolist' ),
				'user_id' => __( 'Owner', 'wprx-todolist' ),
+				'task_count' => __( 'Task Count', 'wprx-todolist' ),
			],
			'handlers' => [
				'user_id' => function( $row ) {
					if ( $user = get_user_by('id', $row['user_id']) ) {
						return "{$user->display_name} ({$user->user_email})"; 
					}

					return "--";
				},
+				'task_count' => function( $row ) {
+					return Models\TodoTask::countWhere([ 'list_id=%d', $row['id'] ]);
+				},
			],
		]
```

Reference:  
https://github.com/codefarma/wprx-todolist/commit/2560725356dc6d56ca57efd61fff798db76eb3c1

### Add a new bulk action for todo lists

Let's add a new bulk action to our todo lists that we can use to complete all tasks associated with that todo list. This can be done in two steps. First step is to create a method on the `TodoList` model which performs our action, and then the second step is to add that method to the list of bulk actions available in the management table.

```php
	// TodoList.php
	/**
	 * Mark all tasks on this todo list as completed
	 *
	 * @return void
	 */
	public function markAllTasksCompleted()
	{
		foreach( $this->getTasks() as $todoTask ) {
			$todoTask->status = 'completed';
			$todoTask->save();
		}
	}


	// plugins.php
	'bulkActions' => [
		'delete' => __( 'Delete', 'wprx-todolist' ),
		'markAllTasksCompleted' => __( 'Mark All Tasks Complete', 'wprx-todolist' ),
	],
```

Reference:  
https://github.com/codefarma/wprx-todolist/commit/5abd10bb0dc92ec2dd7d6c3892e597cfc2df7d57

### Add Ability To Copy A TodoList

It would be nice to have the ability to copy existing todo lists, including the tasks that are assigned to them. The `ActiveRecord` model already has a `copy()` method that you can use to copy the record, but that will not automatically create copies of all the tasks that exist for the todo list being copied. For this exercise, we'll override the `copy()` method on our model to add the additional task copying functionality, and then we'll create a nice action dropdown in our admin interface that we can use to initiate the "copy" workflow.

**Modify copy() behavior:**

```php
	/**
	 * [ActiveRecord] Copy this record
	 *
	 * @return	ActiveRecord
	 */
	public function copy()
	{
		$copy = parent::copy();
		$copy->title = 'Copy of ' . $this->title;
		$copy->save();

		// Copy each of the todo tasks
		foreach( $this->getTasks() as $task ) {
			$task_copy = $task->copy();
			$task_copy->list_id = $copy->id();
			$task_copy->status = 'todo';
			$task_copy->save();
		}

		return $copy;
	}
```

Reference:  
https://github.com/codefarma/wprx-todolist/commit/b05d928783a4139bd3de7bd508b3337112274b87

**Add a custom "Copy" action to the TodoList model:**

The next thing we need to do is add a new action ability for TodoList records in the management table which we can use to initiate the "copy" workflow. In this workflow, we will select the "Copy" action link associated with the record in the management table, which will copy the record and redirect the user to the edit screen of the copied record so that the user can change its attributes as needed.

To implement this, we must first customize the default controller model for the TodoList, and add a new `do_copy()` method to process our copy requests.

Start by scaffolding a new class to use as a controller for our `TodoList` model.

`$ wp mwp add-class wprx-todolist "Controllers\TodoController" --type=model_controller`

```
Creating new plugin class file...
Success: Class added sucessfully.
```

```php
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
```

Reference:  
https://github.com/codefarma/wprx-todolist/commit/4e0f22e7184f5b139229914ec8dbd4ae17145568

We'll also need to set our `TodoList` class to use our new custom controller class when creating a new controller.

```php
	use WPRX\TodoList\Models;
+	use WPRX\TodoList\Controllers;

	// Set custom controller classes
+	Models\TodoList::setControllerClass( Controllers\TodoController::class );
```

Reference:  
https://github.com/codefarma/wprx-todolist/commit/2c8e7e4a7235b02e035f5fa0b539b5ede59967d5

Next, we'll add a `do_copy()` method to our custom controller that we can use to handle the record copy workflow in our management table.

```php
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
```

Reference:  
https://github.com/codefarma/wprx-todolist/commit/b5e5ccebbd82ed003c09caf968d2d9a1c60f7989

Finally, we'll add an action link to to our TodoList records that we can use to trigger the "Copy" workflow.

```php
	/**
	 * Get controller actions
	 *
	 * @return	array
	 */
	public function getControllerActions()
	{
		$actions = parent::getControllerActions();
		unset( $actions['view'] );
+
+		$actions = array_replace( array(
+			'edit' => array(),
+			'copy' => array(
+				'title' => __( 'Copy ' . $this->_getSingularName(), 'wprx-todolist' ),
+				'icon' => 'glyphicon glyphicon-copy',
+				'params' => array(
+					'do' => 'copy',
+					'id' => $this->id(),
+				),
+			),
+			'delete' => array(),
+		), $actions );

		return $actions;
	}
```

Reference:  
https://github.com/codefarma/wprx-todolist/commit/f8dbfb410a26656251b771dff14d113c1de06b3e

Tutorial roadmap:

* User select autocomplete on list edit form
* List management search/sort/filter controls
* Front end list view
* Front end list management
