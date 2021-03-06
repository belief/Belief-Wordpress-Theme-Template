<?php 
  /**
   *  WARNING: This file is part of the core belief_theme_slug Theme Framework. DO NOT edit this file under any circumstances. Please do all modifications in the form of a child theme.
   *
   * File contains initialization of belief_theme_slug Design features created at init hook
   *
   *
   *
   * @package WordPress
   * @subpackage Belief Theme
   * @author  BeliefAgency
   * @license GPL-2.0+
   * @since Belief Theme Theme 1.1
   */
class Belief_Metaboxes_Controller {

	public function __construct() {

		//create metaboxes using module addon
		add_filter( 'cmb_meta_boxes' , array($this, 'belief_metaboxes') );

		//create metaboxes using custom template
		add_action('add_meta_boxes', array($this, 'belief_form_pages_metabox_create') );

		//save metaboxes using custom template
		add_action('save_post', array($this, 'belief_form_pages_metabox_save') );
	}

	


	/**
	* Create Metaboxes
	* @link http://www.billerickson.net/wordpress-metaboxes/
	*
	*/
	public function belief_metaboxes( $meta_boxes ) {
		$meta_boxes['read_more'] = array(
		  'id' => 'custom-read-more',
		  'title' => __(BELIEF_THEME_TITLE.' Custom Read More...'),
		  'pages' => array('post','kerf_sliders'),
		  'context' => 'normal',
		  'priority' => 'high',
		  'show_names' => true,
		  'fields' => array(
		    array(
		      'name' => 'Read more...',
		      'desc' => 'Replaces the default template trailing link to the above custom text.',
		      'id' => 'custom_read_more',
		      'type' => 'text'
		    )
		  )
		);

		return $meta_boxes;
	}

	/**
	* Custom built metabox for adding user-specific amount of form elements
	* @link http://www.billerickson.net/wordpress-metaboxes/
	*
	*/
	public function belief_form_pages_metabox_create() {
		add_meta_box( BELIEF_THEME_SLUG.'_form_pages_meta', 
		              BELIEF_THEME_TITLE.' Form Elements', 
		              'belief_form_pages_metabox_function', 
		              BELIEF_THEME_SLUG.'_form_pages', 'normal', 'high' );
	}

	function belief_form_pages_metabox_function( $post ) {
		include( dirname( __FILE__ ) . '../views/form_metabox_view.php');
	}

	/**
	* Saving for kerf_form_pages custom post type
	* @link http://codex.wordpress.org/Plugin_API/Action_Reference/save_post
	*
	*/
	function belief_form_pages_metabox_save( $post_id ) {
		//expected post type slug
		$slug = BELIEF_THEME_SLUG.'_form_pages';

		if ( $slug != $_POST['post_type']) {
		  return;
		}

		// update the post's metadata (transfer to array of variables!)
		add_post_meta($post_id, '_'.BELIEF_THEME_SLUG.'_options_meta', strip_tags($_POST[BELIEF_THEME_SLUG.'_options_meta']) , true )
		          or update_post_meta( $post_id, '_'.BELIEF_THEME_SLUG.'_options_meta', strip_tags($_POST[BELIEF_THEME_SLUG.'_options_meta']) );
	}
}