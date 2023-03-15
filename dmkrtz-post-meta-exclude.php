<?php
/*
Plugin Name: dmkrtz Post Meta Exclude
Plugin URI: https://github.com/dmkrtz/dmkrtz-post-meta-exclude
GitHub Plugin URI: dmkrtz/dmkrtz-post-meta-exclude
Description: Excludes categories or tags from post meta "the_category", "the_tags" actions.
Version: 1.0.0
*/

function the_category_filter($thelist, $separator=' ') {
	// check for ACF field in options-reading
	$acf_exclude = get_field('exclude_categories_from_post_meta', 'options-reading');

	// list the IDs of the categories to exclude
	$exclude = $acf_exclude ?? array(/* your ID's */);
	// create an empty array
	$exclude2 = array();

	// custom category separator
	$acf_separator = get_field('custom_post_meta_separator', 'options-reading');
	
	$frontend_separator = " $acf_separator " ?? $separator;

	if(is_array($exclude) && !empty($exclude)) {
		// loop through the excluded IDs and get their actual names
		foreach($exclude as $c) {
			// store the names in the second array
			$exclude2[] = get_cat_name($c);
		}

		// get the list of categories for the current post     
		$cats = explode($separator, $thelist);
		// clear list    
		$thelist = array();

		foreach($cats as $cat) {
			// remove the tags from each category
			$catname = trim(strip_tags($cat));

			// check against the excluded categories
			if(!in_array($catname, $exclude2))

			// if not in that list, add to the new array
			$thelist[] = $cat;
		}
		 // return the new, shortened list
		return implode($frontend_separator, $thelist);
	} else {
		// or else return the old list without any filtering
		return $thelist;
	}
}
// add the filter to 'the_category' tag
add_filter('the_category','the_category_filter', 10, 2);
