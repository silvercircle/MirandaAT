<?php
/**
 * Template Name: Vanilla Forum Template
 *
 * A custom page template for a Vanilla Forum.
 *
 * The "Template Name:" bit above allows this to be selectable
 * from a dropdown menu on the edit page screen.
 *
 */
    global $sidebar_style, $display_sidebar;
    
    if(get_post_meta($post->ID, 'no_sidebar', true) == 1) {
        $sidebar_style = "display:none;";
        $display_sidebar = "0";
    } else {
        $sidebar_style = "display:table-cell;";
        $display_sidebar = "1";
    }
    $template = get_post_meta($post->ID, 'custom_template', true);
    if($template != '') {
	include dirname(__FILE__) . '/' . $template . '.php';
	die;
    }
get_header();
the_content();
get_footer();