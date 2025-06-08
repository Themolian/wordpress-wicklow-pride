<?php
// Positions Taxonomy

$labels = array(
		'name'                       => _x( 'Positions', 'taxonomy general name', 'textdomain' ),
		'singular_name'              => _x( 'Position', 'taxonomy singular name', 'textdomain' ),
		'search_items'               => __( 'Search Positions', 'textdomain' ),
		'popular_items'              => __( 'Popular Positions', 'textdomain' ),
		'all_items'                  => __( 'All Positions', 'textdomain' ),
		'parent_item'                => null,
		'parent_item_colon'          => null,
		'edit_item'                  => __( 'Edit Position', 'textdomain' ),
		'update_item'                => __( 'Update Position', 'textdomain' ),
		'add_new_item'               => __( 'Add New Position', 'textdomain' ),
		'new_item_name'              => __( 'New Position Name', 'textdomain' ),
		'separate_items_with_commas' => __( 'Separate positions with commas', 'textdomain' ),
		'add_or_remove_items'        => __( 'Add or remove positions', 'textdomain' ),
		'choose_from_most_used'      => __( 'Choose from the most used positions', 'textdomain' ),
		'not_found'                  => __( 'No positions found.', 'textdomain' ),
		'menu_name'                  => __( 'Positions', 'textdomain' ),
	);

	$args = array(
		'hierarchical'          => false,
		'labels'                => $labels,
		'show_ui'               => true,
		'show_admin_column'     => true,
		'update_count_callback' => '_update_post_term_count',
		'query_var'             => true,
		'rewrite'               => array( 'slug' => 'position' ),
	);

	register_taxonomy( 'position', 'member', $args );