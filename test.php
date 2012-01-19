<?php

// Zend library include path
//set_include_path(get_include_path() . PATH_SEPARATOR . "$_SERVER[DOCUMENT_ROOT]/ZendGdata-1.11.11/library");
// set_include_path(get_include_path() . PATH_SEPARATOR . "./ZendGdata-1.11.11/library");

include_once('DB.php');
include_once('AccountTransformer.php');
include_once('GanttProject.php');

//$ac = new AccountTransformer();
//$ac->process( 'data/source-20111123105515.xml' );

$gp = new GanttProject();
$gp->transform( 'data/source-20111123105515.xml' );