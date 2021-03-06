<?php

/*
 * bbpress starts here
 */
 
  /**
 * @package bbPress
 * @subpackage BBP_Twenty_Ten
 * @since Twenty Ten 1.1
 */

if ( ! function_exists( 'bbp_twentyten_setup' ) ):
/**
 * Sets up theme support for bbPress
 *
 * If you're looking to add bbPress support into your own custom theme, you'll
 * want to make sure to use: add_theme_support( 'bbpress' );
 *
 * @since bbPress (r2652)
 */
function bbp_twentyten_setup() {

	// This theme comes bundled with bbPress template files
	add_theme_support( 'bbpress' );
}
/** Tell WordPress to run twentyten_setup() when the 'after_setup_theme' hook is run. */
add_action( 'after_setup_theme', 'bbp_twentyten_setup' );
endif;

if ( !function_exists( 'bbp_twentyten_enqueue_styles' ) ) :
/**
 * Load the theme CSS
 *
 * @since bbPress (r2652)
 *
 * @uses is_admin() To check if it's the admin section
 * @uses wp_enqueue_style() To enqueue the styles
 */
function bbp_twentyten_enqueue_styles () {
	if ( is_admin() )
		return false;

	// Right to left
	if ( is_rtl() ) {

		// TwentyTen
		//wp_enqueue_style( 'twentyten',     get_template_directory_uri() . '/style.css', '',          20100503, 'screen' );
		wp_enqueue_style( 'twentyten-rtl', get_template_directory_uri() . '/rtl.css',   'twentyten', 20100503, 'screen' );

		wp_enqueue_style( 'bbp-twentyten-bbpress', get_stylesheet_directory_uri() . '/css/bbpress-rtl.css', 'twentyten-rtl', 20100503, 'screen' );

	// Left to right
	} else {
		wp_enqueue_style( 'bbp-twentyten-bbpress', get_stylesheet_directory_uri() . '/css/bbpress.css', 'twentyten', 20100503, 'screen' );
	}
}
add_action( 'init', 'bbp_twentyten_enqueue_styles' );
endif;

if ( !function_exists( 'bbp_twentyten_dim_favorite' ) ) :
/**
 * Add or remove a topic from a user's favorites
 *
 * @since bbPress (r2652)
 *
 * @uses bbp_get_current_user_id() To get the current user id
 * @uses current_user_can() To check if the current user can edit the user
 * @uses bbp_get_topic() To get the topic
 * @uses check_ajax_referer() To verify the nonce & check the referer
 * @uses bbp_is_user_favorite() To check if the topic is user's favorite
 * @uses bbp_remove_user_favorite() To remove the topic from user's favorites
 * @uses bbp_add_user_favorite() To add the topic from user's favorites
 */
function bbp_twentyten_dim_favorite () {
	$user_id = bbp_get_current_user_id();
	$id      = intval( $_POST['id'] );

	if ( !current_user_can( 'edit_user', $user_id ) )
		die( '-1' );

	if ( !$topic = bbp_get_topic( $id ) )
		die( '0' );

	check_ajax_referer( "toggle-favorite_$topic->ID" );

	if ( bbp_is_user_favorite( $user_id, $topic->ID ) ) {
		if ( bbp_remove_user_favorite( $user_id, $topic->ID ) )
			die( '1' );
	} else {
		if ( bbp_add_user_favorite( $user_id, $topic->ID ) )
			die( '1' );
	}

	die( '0' );
}
add_action( 'wp_ajax_dim-favorite', 'bbp_twentyten_dim_favorite' );
endif;

if ( !function_exists( 'bbp_twentyten_dim_subscription' ) ) :
/**
 * Subscribe/Unsubscribe a user from a topic
 *
 * @since bbPress (r2668)
 *
 * @uses bbp_is_subscriptions_active() To check if the subscriptions are active
 * @uses bbp_get_current_user_id() To get the current user id
 * @uses current_user_can() To check if the current user can edit the user
 * @uses bbp_get_topic() To get the topic
 * @uses check_ajax_referer() To verify the nonce & check the referer
 * @uses bbp_is_user_subscribed() To check if the topic is in user's
 *                                 subscriptions
 * @uses bbp_remove_user_subscriptions() To remove the topic from user's
 *                                        subscriptions
 * @uses bbp_add_user_subscriptions() To add the topic from user's subscriptions
 */
function bbp_twentyten_dim_subscription () {
	if ( !bbp_is_subscriptions_active() )
		return;

	$user_id = bbp_get_current_user_id();
	$id      = intval( $_POST['id'] );

	if ( !current_user_can( 'edit_user', $user_id ) )
		die( '-1' );

	if ( !$topic = bbp_get_topic( $id ) )
		die( '0' );

	check_ajax_referer( "toggle-subscription_$topic->ID" );

	if ( bbp_is_user_subscribed( $user_id, $topic->ID ) ) {
		if ( bbp_remove_user_subscription( $user_id, $topic->ID ) )
			die( '1' );
	} else {
		if ( bbp_add_user_subscription( $user_id, $topic->ID ) )
			die( '1' );
	}

	die( '0' );
}
add_action( 'wp_ajax_dim-subscription', 'bbp_twentyten_dim_subscription' );
endif;

if ( !function_exists( 'bbp_twentyten_enqueue_scripts' ) ) :
/**
 * Enqueue the required Javascript files
 *
 * @since bbPress (r2652)
 *
 * @uses bbp_is_topic() To check if it's the topic page
 * @uses get_stylesheet_directory_uri() To get the stylesheet directory uri
 * @uses bbp_is_single_user_edit() To check if it's the profile edit page
 * @uses wp_enqueue_script() To enqueue the scripts
 */
function bbp_twentyten_enqueue_scripts () {
	if ( bbp_is_topic() )
		wp_enqueue_script( 'bbp_topic', get_stylesheet_directory_uri() . '/js/topic.js', array( 'wp-lists' ), '20101202' );

	if ( bbp_is_single_user_edit() )
		wp_enqueue_script( 'user-profile' );
}
add_action( 'wp_enqueue_scripts', 'bbp_twentyten_enqueue_scripts' );
endif;

if ( !function_exists( 'bbp_twentyten_scripts' ) ) :
/**
 * Put some scripts in the header, like AJAX url for wp-lists
 *
 * @since bbPress (r2652)
 *
 * @uses bbp_is_topic() To check if it's the topic page
 * @uses admin_url() To get the admin url
 * @uses bbp_is_single_user_edit() To check if it's the profile edit page
 */
function bbp_twentyten_scripts () {
	if ( bbp_is_topic() ) : ?>

	<script type='text/javascript'>
		/* <![CDATA[ */
		var ajaxurl = '<?php echo admin_url( 'admin-ajax.php' ); ?>';
		/* ]]> */
	</script>

	<?php elseif ( bbp_is_single_user_edit() ) : ?>

	<script type="text/javascript" charset="utf-8">
		if ( window.location.hash == '#password' ) {
			document.getElementById('pass1').focus();
		}
	</script>

	<?php
	endif;
}
add_filter( 'wp_head', 'bbp_twentyten_scripts', -1 );
endif;

if ( !function_exists( 'bbp_twentyten_topic_script_localization' ) ) :
/**
 * Load localizations for topic script.
 *
 * These localizations require information that may not be loaded even by init.
 *
 * @since bbPress (r2652)
 *
 * @uses bbp_is_topic() To check if it's the topic page
 * @uses bbp_get_current_user_id() To get the current user id
 * @uses bbp_get_topic_id() To get the topic id
 * @uses bbp_get_favorites_permalink() To get the favorites permalink
 * @uses bbp_is_user_favorite() To check if the topic is in user's favorites
 * @uses bbp_is_subscriptions_active() To check if the subscriptions are active
 * @uses bbp_is_user_subscribed() To check if the user is subscribed to topic
 * @uses bbp_get_topic_permalink() To get the topic permalink
 * @uses wp_localize_script() To localize the script
 */
function bbp_twentyten_topic_script_localization () {
	if ( !bbp_is_topic() )
		return;

	$user_id = bbp_get_current_user_id();

	$localizations = array(
		'currentUserId' => $user_id,
		'topicId'       => bbp_get_topic_id(),
		'favoritesLink' => bbp_get_favorites_permalink( $user_id ),
		'isFav'         => (int) bbp_is_user_favorite( $user_id ),
		'favLinkYes'    => __( 'favorites',                                         'bbpress' ),
		'favLinkNo'     => __( '?',                                                 'bbpress' ),
		'favYes'        => __( 'This topic is one of your %favLinkYes% [%favDel%]', 'bbpress' ),
		'favNo'         => __( '%favAdd% (%favLinkNo%)',                            'bbpress' ),
		'favDel'        => __( '&times;',                                           'bbpress' ),
		'favAdd'        => __( 'Add this topic to your favorites',                  'bbpress' )
	);

	if ( bbp_is_subscriptions_active() ) {
		$localizations['subsActive']   = 1;
		$localizations['isSubscribed'] = (int) bbp_is_user_subscribed( $user_id );
		$localizations['subsSub']      = __( 'Subscribe', 'bbpress' );
		$localizations['subsUns']      = __( 'Unsubscribe', 'bbpress' );
		$localizations['subsLink']     = bbp_get_topic_permalink();
	} else {
		$localizations['subsActive'] = 0;
	}

	wp_localize_script( 'bbp_topic', 'bbpTopicJS', $localizations );
}
add_filter( 'wp_enqueue_scripts', 'bbp_twentyten_topic_script_localization' );
endif;
?>
