<?php
  /**
   *  WARNING: This file is part of the core belief_theme_slug Theme Framework. DO NOT edit this file under any circumstances. Please do all modifications in the form of a child theme.
   *
   * File contains initialization of belief_theme_slug Design features created at init hook
   *
   *
   * Add Theme Admin Menu
   * @link http://codex.wordpress.org/Settings_API
   *
   * @package WordPress
   * @subpackage Belief Theme
   * @author  BeliefAgency
   * @license GPL-2.0+
   * @since Belief Theme Theme 1.1
   */
  

/**
 *  Structure for admin
 * 
 */
class Belief_Admin_Init {
  public $instance;

  public function __construct() {
    $this->instance =& $this;

    //add custom hook for image insertion
    add_filter( 'image_send_to_editor', array($this, 'html5_insert_image'), 10, 9 );

    add_action('admin_init', array($this, 'belief_capabilities_mod') );

    add_action('admin_menu', array($this, 'belief_menu_mod') );

    add_action('admin_head', array($this, 'belief_hide_add_button') );

    add_action('admin_menu', array($this, 'belief_redirect') );

    add_action('admin_init', array($this, 'belief_restrict_notice') );

  }

  
  /**
      Widget Setup

  */

  /**
   * Register widgetized area and update sidebar with default widgets
   */
  public function belief_widgets_init() {
    register_sidebar( array(
      'name' => __( 'Sidebar' ),
      'id' => 'primary-sidebar',
      'before_widget' => '<div id="%1$s" class="widget %2$s">',
      'after_widget' => "</div>",
      'before_title' => '<div class="widget-title"><h3>',
      'after_title' => '</h3></div>',
    ) );

  }


  /**
      Post Type Hooks

  */

  /*
   * Modify Insert Media into post with custom attributes
   */
  public function html5_insert_image($html, $id, $caption, $title, $align, $url, $size) {

      $image_attributes = wp_get_attachment_image_src( $id , 'full'); 
      $html5 = "<figure id='post-$id media-$id' class='figure align$align'>";
      $html5 .=  "<img id='post-$id media-$id' class='post-img-full-width align$align' src='$image_attributes[0]' width='100%'>";
      if ($caption) {
          $html5 .= "<figcaption>$caption</figcaption>";
      }
      $html5 .= "</figure>";
      return $html5;
  }

  /**
      Permissions & Redirects

  */

  /**
  * Modify Permissions
  * @link http://codex.wordpress.org/Function_Reference/add_meta_box
  * Credit to Belief Agency
  *
  */
  public function belief_capabilities_mod() {
    $editor_role = get_role('editor');
    $editor_role -> remove_cap('publish_pages');
    $editor_role -> add_cap('add_users');
    $editor_role -> add_cap('create_users');

    $author_role = get_role('author');
    $author_role -> remove_cap('publish_pages');
  }

  public function belief_menu_mod() {
    global $submenu;
    if ( count($submenu['edit.php?post_type=page']) > 10 ) {
      unset($submenu['edit.php?post_type=page'][10]);
      $submenu['edit.php?post_type=page'][10][1] = 'publish_pages';
    }
  }

  public function belief_hide_add_button() {
    global $current_screen;
    if($current_screen->post_type == 'page' && !current_user_can('publish_pages')) {
      echo '<style>.add-new-h2{display: none;}</style>';
    }
  }

  public function belief_redirect() {
    $result = stripos($_SERVER['REQUEST_URI'], 'post-new.php?post_type=page');
    if ($result!==false && !current_user_can('publish_pages')) {
      wp_redirect(get_option('siteurl') . '/wp-admin/index.php?permissions_error=true');
    }
  }

  public function belief_restrict_notice() {
    if( isset($_GET['permissions_error']) ) {
      echo "<div id='permissions-warning' class='error fade'><p><strong>".__('You do not have permission for this access.')."</strong></p></div>";
    }
  }
}

