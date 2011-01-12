<?php
/**
 * For each model having a 'foreignkey' or a 'manytomany' colum, details
 * must be added here. These details are used to generated the methods
 * to retrieve related models from each model.
 */

// using discuss author and comment aren't mandatory anymore

$m = array();
$m['Zblog_Post'] = array('relate_to_many' => array('Zblog_Tag','relate_name' => 'posts'));
return $m;
?>