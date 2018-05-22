<?php

function sql_exclude_field_list($mysqli,$table,$remove){
	/* This function accepts a mysqli object and string representing the list of fields to exclude from an SQL query and returns the ammended field list. */

	/* Here field names are bookended by || to avoid partial string matches.  This will ensure only the field desired is removed, and fields which contain the desired field's string will not.  i.e. Removing 'fiction' will not also remove 'non-fiction.' */

	/* Build list of fields to remove. */

	/* Add pipes to beginning of string. */
	$remove = "||" . $remove;

	/* Iterate through string and add pipes to each side of commas if commas exist. */
	if (strpos($remove,",")){
		$remove = str_replace("," , "||,||",$remove);
	}

	/* Add last set of pipies to list fo fields to remove. */
	$remove .= "||";

	/* Build SQL for query. */
	$sqlFields = "SELECT * FROM " . $table;

	/* Run query. */
	$queryFields = $mysqli->query($sqlFields);
		if(!$queryFields){
			exit("Bad SQL for Field Name query.  Error: " . $mysqli->error);
		}

	/* Fetch result row. */
	$resultFields = $queryFields->fetch_fields();
		if(!$resultFields){
			exit("Fetch Fields failed. Error: " . $mysqli->error);
		}

	/* set variable to return from function. */
	$return = "";

	/* Iterate over result set to pull field names. */
	foreach($resultFields as $value){
		/* Bookend each field name for uniqueness. */
		$needle = "||" . $value->name . "||";

		/* Search for fields to remove. */
		if( strpos($remove,$needle) === false ){
			/* If strpos returns false we continue. */
			$return .= $value->name . ",";
		}
	/* End of foreach loop. */
	}

	/* Strip off last comma. */
	$return = rtrim($return,",");

/* Return list of fields minus those specified to remove. */
return($return);
/* End of sql_exclude_field_list function. */	
}

?>