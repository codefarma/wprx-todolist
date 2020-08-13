Project Description:

Build a todo list plugin for WordPress which allows the user to create "To Do" lists, add tasks to their to do lists, and track the status of those tasks.

Initial Requirements:

* Admin users can create new To Do lists with the following attributes: (Title, Owner)
* Admin users can create tasks associated with To Do lists with the following attributes: (Title, Status, List Order)
* Admin users can update the status of tasks in the To Do list

## Scaffolding 

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

```

Reference:
https://github.com/codefarma/wprx-todolist/commit/9f1f67f64d331ae6af7d30e5bb4ff549506f7fc9


### Checkout a new branch to begin our development:
`$ git checkout -b "mvp-features"`

```
Switched to a new branch 'mvp-features'
```

## Building

Now that we have a new plugin scaffolded and checked in to source control, it's time to start building out our plugin.

The first thing that we should do is to think about the data that our plugin is going to need to manage, and then build out the models for that data. Data is modeled easily using the `ActiveRecord` class which is built into the framework. Based on the early requirements, it seems that we are going to need to have two sets of data:

* To Do Lists
* Tasks

### Create TodoList Model

Let's start by creating a new model for a To Do list.

`$ wp mwp add-class wprx-todolist "Models\TodoList" --type=model`

```
Creating new plugin class file...
Success: Class added sucessfully.
```

Yields:
```
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

```
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

```
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

```
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

```
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

```
$list = new WPRX\TodoList\Models\TodoList;
$list->title = "My First List!";
$list->user_id = 1;
$list->save();

print_r("My new list has ID: " . $list->id());
```

## Generate an Admin Interface

The next thing we want to do is generate an administrative interface in the WP Admin that admin users can use to manage all to do lists using a GUI. This is a fairly trivial task using the MWP Framework.

The preferred hook to register your admin interfaces using the MWP Framework is the 'mwp_framework_init' action. Therefore, an ideal place to put the code which registers your admin pages is inside the existing callback which is already registered to the 'mwp_framework_init' action by your plugin.php file.

```
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

```
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

```
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

```
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


