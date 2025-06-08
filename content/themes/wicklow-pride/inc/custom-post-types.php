<?php
/**
 * Register a custom post type called "Event".
 *
 * @see get_post_type_labels() for label keys.
 */
function wicklowpride_post_types() {
	$labels = array(
		'name'                  => _x( 'Events', 'Post type general name', 'textdomain' ),
		'singular_name'         => _x( 'Event', 'Post type singular name', 'textdomain' ),
		'menu_name'             => _x( 'Events', 'Admin Menu text', 'textdomain' ),
		'name_admin_bar'        => _x( 'Event', 'Add New on Toolbar', 'textdomain' ),
		'add_new'               => __( 'Add New', 'textdomain' ),
		'add_new_item'          => __( 'Add New Event', 'textdomain' ),
		'new_item'              => __( 'New Event', 'textdomain' ),
		'edit_item'             => __( 'Edit Event', 'textdomain' ),
		'view_item'             => __( 'View Event', 'textdomain' ),
		'all_items'             => __( 'All Events', 'textdomain' ),
		'search_items'          => __( 'Search Events', 'textdomain' ),
		'parent_item_colon'     => __( 'Parent Events:', 'textdomain' ),
		'not_found'             => __( 'No Events found.', 'textdomain' ),
		'not_found_in_trash'    => __( 'No Events found in Trash.', 'textdomain' ),
		'featured_image'        => _x( 'Event Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'textdomain' ),
		'set_featured_image'    => _x( 'Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'textdomain' ),
		'remove_featured_image' => _x( 'Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'textdomain' ),
		'use_featured_image'    => _x( 'Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'textdomain' ),
		'archives'              => _x( 'Event archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'textdomain' ),
		'insert_into_item'      => _x( 'Insert into Event', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'textdomain' ),
		'uploaded_to_this_item' => _x( 'Uploaded to this Event', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'textdomain' ),
		'filter_items_list'     => _x( 'Filter Events list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'textdomain' ),
		'items_list_navigation' => _x( 'Events list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'textdomain' ),
		'items_list'            => _x( 'Events list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'textdomain' ),
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'events' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
        'menu_icon'          => 'dashicons-calendar',
		'supports'           => array( 'title', 'author', 'thumbnail', 'excerpt', 'comments' ),
	);

	register_post_type( 'event', $args );

	// Members

	$labels = array(
		'name'                  => _x( 'Members', 'Post type general name', 'textdomain' ),
		'singular_name'         => _x( 'Member', 'Post type singular name', 'textdomain' ),
		'menu_name'             => _x( 'Members', 'Admin Menu text', 'textdomain' ),
		'name_admin_bar'        => _x( 'Member', 'Add New on Toolbar', 'textdomain' ),
		'add_new'               => __( 'Add New', 'textdomain' ),
		'add_new_item'          => __( 'Add New Member', 'textdomain' ),
		'new_item'              => __( 'New Member', 'textdomain' ),
		'edit_item'             => __( 'Edit Member', 'textdomain' ),
		'view_item'             => __( 'View Member', 'textdomain' ),
		'all_items'             => __( 'All Members', 'textdomain' ),
		'search_items'          => __( 'Search Members', 'textdomain' ),
		'parent_item_colon'     => __( 'Parent Members:', 'textdomain' ),
		'not_found'             => __( 'No Members found.', 'textdomain' ),
		'not_found_in_trash'    => __( 'No Members found in Trash.', 'textdomain' ),
		'featured_image'        => _x( 'Member Profile Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'textdomain' ),
		'set_featured_image'    => _x( 'Set profile image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'textdomain' ),
		'remove_featured_image' => _x( 'Remove profile image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'textdomain' ),
		'use_featured_image'    => _x( 'Use as profile image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'textdomain' ),
		'archives'              => _x( 'Member archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'textdomain' ),
		'insert_into_item'      => _x( 'Insert into Member', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'textdomain' ),
		'uploaded_to_this_item' => _x( 'Uploaded to this Member', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'textdomain' ),
		'filter_items_list'     => _x( 'Filter Members list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'textdomain' ),
		'items_list_navigation' => _x( 'Members list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'textdomain' ),
		'items_list'            => _x( 'Members list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'textdomain' ),
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'member' ),
		'capability_type'    => 'post',
		'has_archive'        => 'members',
		'hierarchical'       => false,
		'menu_position'      => null,
        'menu_icon'          => 'dashicons-groups',
		'supports'           => array( 'title', 'author', 'thumbnail', 'excerpt', 'comments' ),
	);

	register_post_type( 'member', $args );
}

add_action( 'init', 'wicklowpride_post_types' );
