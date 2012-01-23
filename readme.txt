Prerequisites:
- PHP 5.3.x;
- MySQL Server 5.x;

How to import into DB:

1. Create DB, e.g., "accounts" and run cr_DB.sql to create DB objects;
2. Change configuration accordingly in DB.php:
	static private $_user = 'root';
	static private $_pass = NULL;
	static private $_connectionString = 'mysql:host=localhost;dbname=accounts';

3. Data loading into DB encapsulated in AccountTransformer.php;

Example:

include_once('DB.php');
include_once('AccountTransformer.php');

$ac = new AccountTransformer();
$ac->process( 'data/source-20111123105515.xml' );

How to transform into Gantt XML file:

Functionality encapsulated in GanttProject class.
To transform the file use method "transform":
First parameter: Account XML file;
Second parameter: directory where to store generated project files; 

NB! make sure short_open_tag option is enabled.

Example:

ini_set('short_open_tag', 1);
include_once('GanttProject.php');
$gp = new GanttProject();
$gp->transform( 'data/source-20111123105515.xml', '.' );