<?php
/* 
 * Plugin Name:   NPS Monitoring
 * Version:       2.1.2
 * Plugin URI:    http://www.npsmonitoring.com/
 * Description:   Free Net Promoter Score (NPS) Monitoring for your blog or website.  Simply add the included widget to the your theme and we do the rest.
 * Author:        Dimbal Software
 * Author URI:    http://www.dimbal.com/
 */
if ( ! defined( 'ABSPATH' ) ) exit();	// sanity check

//error_reporting(E_ALL);
//ini_set('display_errors','On');

//Common Variables
$npsm_property_id_name = 'npsm_property_id';
$npsm_active_name = 'npsm_active';
$npms_plugin_folder = 'nps-monitoring';

register_activation_hook(__FILE__, 'npsm_activate');

//Function that is run when the plugin is activated
function npsm_activate(){
	
}

/****

Disbaling the widget for now... may use it in the future if there are problems with wp_footer


//Widget class for determining when to display the Glossary Term
class NPSM_Widget extends WP_Widget {

	public function __construct() {
		// widget actual processes
		parent::__construct(
	 		'npsm', // Base ID
			'NPS Monitoring Widget', // Name
			array( 'description' => __( 'NPS Monitoring alternate display widget.  Use this widget to display the NPS Survey if your theme does not support the wp_footer function.  Visit the NPS Monitoring Settings page to enable this widget.', 'text_domain' ), ) // Args
		);
	}

 	public function form( $instance ) {
		// outputs the options form on admin
		if ( $instance ) {
			$title = esc_attr( $instance['title'] );
		}
		else {
			$title = __( 'NPS Monitoring' );
		}
?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
		</p>

<?php 
	}

	public function update( $new_instance, $old_instance ) {
		// processes widget options to be saved
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['npsm_property_id'] = strip_tags( $new_instance['npsm_property_id'] );
		return $instance;
	}

	public function widget( $args, $instance ) {
		// outputs the content of the widget
		global $npsm_property_id_name;
		global $npsm_active_name;
		global $npsm_display_method_name;
		
		// Read in existing option value from database
		$npsm_property_id = get_option( $npsm_property_id_name );
		$npsm_active = get_option( $npsm_active_name );
		$npsm_display_method = get_option( $npsm_display_method_name );
		
		//Validate the feature is active.		
		if(isset($npsm_active) && $npsm_active=="1"){
		
			//Validate the widget method is being used
			if(isset($npsm_display_method) && $npsm_display_method=="widget"){
		
				//Validate the stored value in the DB :: Must be an INT and greater the 0
				if(isset($npsm_property_id) && is_numeric($npsm_property_id) && $npsm_property_id>0){
				
					//Title information
					echo $args['before_widget'];
					if ( ! empty( $instance['title'] ) ) {
						echo $args['before_title'];
						echo esc_html( $instance['title'] );
						echo $args['after_title'];
					}
					
					//Enque the JS
					echo '<div class="npsWrapper" npsPropertyId="'.$npsm_property_id.'"></div>';
					echo '<script src="http://www.npsmonitoring.com/v1/nps.js" type="text/javascript"></script>';
					echo '<a href="http://www.npsmonitoring.com" title="Net Promoter Score Monitoring by NPSMonitoring.com">Net Promoter Score Monitoring by NPSMonitoring.com</a>';
		
				}
			}
		}
	}

}

//Now register the widgets into the system
function npsm_register_widgets() {
	register_widget( 'NPSM_Widget' );
}
add_action( 'widgets_init', 'npsm_register_widgets' );

*/


//ADMIN MENU PAGE
add_action( 'admin_menu', 'npsm_plugin_menu' );
function npsm_plugin_menu() {
	add_options_page( 'NPS Monitoring Settings and Options', 'NPS Monitoring', 'manage_options', 'npsm-plugin-options', 'npsm_plugin_options' );
}
function npsm_plugin_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	
	global $npsm_property_id_name;
	global $npsm_active_name;
	global $npms_plugin_folder;
	
	
	// variables for the field and option names 
    $npsm_form_submitted = 'npsm_form_submitted';

    // Read in existing option value from database
    $npsm_property_id = get_option( $npsm_property_id_name );
    $npsm_active = get_option( $npsm_active_name );

    // See if the user has posted us some information
    // If they did, this hidden field will be set to 'Y'
    if( isset($_POST[ $npsm_form_submitted ]) && $_POST[ $npsm_form_submitted ] == 'Y' ) {
        // Read their posted value
        $npsm_property_id = $_POST[ $npsm_property_id_name ];
        $npsm_active = $_POST[ $npsm_active_name ];

        // Save the posted value in the database
        update_option( $npsm_property_id_name, $npsm_property_id );
        update_option( $npsm_active_name, $npsm_active );

        // Put an settings updated message on the screen

		?>
		<div class="updated"><p><strong><?php _e('NPS Monitoring settings saved.', 'npsm-menu' ); ?></strong></p></div>
		<?php

    }

	//Admin Page HTML
    ?>
    <div class="wrap">
  	    
  	    <div style="float:right; margin: 10px 10px 0 0"><a href="http://www.dimbal.com"><img src="http://www.dimbal.com/images/logo_300.png" alt="Dimbal Software" /></a></div>
	    <h2><a href="http://www.npsmonitoring.com" target="_blank"><img src="<?=(WP_PLUGIN_URL)?>/<?=($npms_plugin_folder)?>/guage_180.png" style="padding-right:25px; border:none; width:80px; vertical-align:middle;" />NPS Website Monitoring</a></h2>
	    <hr />
		<div style="display:table; width:100%;">
			<div style="display:table-cell; width:auto; vertical-align:top;">
				<form name="npsm_form" method="post" action="">
					<input type="hidden" name="<?php echo $npsm_form_submitted; ?>" value="Y">
					<h3>Free NPS Monitoring Accounts at <a href="http://www.npsmonitoring.com" target="_blank">http://www.npsmonitoring.com</a></h3>
					<p>NPS is a powerful customer loyalty metric. An NPS survey alone is not enough. It takes cutting edge analysis tools and industry insight to understand what the scores are saying.</p>
					<p>Please enter your Property ID below.  Don't have one?  No Problem - signup for a free NPS Monitoring account from <a href="http://www.npsmonitoring.com" target="_blank">http://www.npsmonitoring.com</a></p>
					
					<hr />
					<h3>Settings</h3>
					<p><?php _e("NPS Monitoring Enabled:", 'npsm-menu' ); ?> 
					<select name="<?php echo $npsm_active_name; ?>">
						<option value="0"<?php echo ($npsm_active=="0")?" selected":""; ?>>False</option>
						<option value="1"<?php echo ($npsm_active=="1")?" selected":""; ?>>True</option>
					</select>
					</p>
					<p><?php _e("Property ID:", 'npsm-menu' ); ?> 
					<input type="text" name="<?php echo $npsm_property_id_name; ?>" value="<?php echo $npsm_property_id; ?>" size="20">
					</p>
					<p>
					<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
					</p>
				</form>
				
				<hr />
				<h3>Popular NPS Blog Posts</h3>
				<ul>
					<li><a href="http://www.npsmonitoring.com/blog/2013/08/top-10-articles-net-promoter-score-nps/" target="_blank">Top 10 articles on Net Promoter Score</a></li>
					<li><a href="http://www.npsmonitoring.com/blog/2013/08/what-is-nps-and-why-should-you-care/" target="_blank">What is NPS and why should you care about it?</a></li>
					<li><a href="http://www.npsmonitoring.com/blog/2013/08/analyze-nps-scores-prioritize-repairs/" target="_blank">How to analyze NPS Scores and Prioritize Repairs</a></li>
					<li><a href="http://www.npsmonitoring.com/blog/2013/07/5-big-nps-mistakes-afford/" target="_blank">5 Big NPS mistakes you can't afford to make</a></li>
				</ul>
				
			</div>
			<div style="display:table-cell; width:300px; padding-left:20px; vertical-align:top;">
				<!-- RIGHT SIDE CONTENT -->
				<h4>Did you like this Plugin?  Please help it grow.</h4>
				<div style="text-align:center;"><a href="http://wordpress.org/support/view/plugin-reviews/nps-monitoring">Rate this Plugin on Wordpress</a></div>
				<br />
				<div style="text-align:center;">
					<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
					<input type="hidden" name="cmd" value="_s-xclick">
					<input type="hidden" name="hosted_button_id" value="5GMXFKZ79EJFA">
					<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
					<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
					</form>
				</div>
				<hr />
				<h4>Follow us for Free Giveaways and more...</h4>
				<div id="fb-root"></div>
				<script type="text/javascript">
				  // Additional JS functions here
				  window.fbAsyncInit = function() {
				    FB.init({
				      appId      : '539348092746687', // App ID
				      //channelUrl : '//<?=(URL_ROOT)?>channel.html', // Channel File
				      status     : true, // check login status
				      cookie     : true, // enable cookies to allow the server to access the session
				      xfbml      : true,  // parse XFBML
				      frictionlessRequests: true,  //Enable Frictionless requests
				    });
				  };
	
				  // Load the SDK Asynchronously
				  (function(d){
				     var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
				     if (d.getElementById(id)) {return;}
				     js = d.createElement('script'); js.id = id; js.async = true;
				     js.src = "//connect.facebook.net/en_US/all.js";
				     ref.parentNode.insertBefore(js, ref);
				   }(document));
				</script>
				<div style="text-align:center;"><div class="fb-like" data-href="https://www.facebook.com/dimbalsoftware" data-send="false" data-layout="standard" data-show-faces="false" data-width="200"></div></div>
				<hr />
				<h4>Questions?  Support?  Record a Bug?</h4>
				<p>Need help with this plugin? Visit...</p>
				<p><a href="http://www.dimbal.com/support">http://www.dimbal.com/support</a></p>
				<hr />
				<h4>Other great Dimbal Products</h4>
				<!--<div class="dbmWidgetWrapper" dbmZone="18"></div>-->
				<div class="dbmWidgetWrapper" dbmZone="19"></div>
				<div class="dbmWidgetWrapper" dbmZone="20"></div>
				<a href="http://www.dimbal.com">Powered by the Dimbal Banner Manager</a>
			</div>
		</div>
	</div>
	<?php
	wp_enqueue_script('dbmScript','http://www.dimbal.com/dbm/banner/dbm.js', false);
}


//This is the hook to display the actual NPS Monitoring Widget
add_action ( 'wp_footer', 'npsm_display' );
function npsm_display() {
	// outputs the content of the NPS Widget
	global $npsm_property_id_name;
	global $npsm_active_name;
	
	// Read in values from the database
	$npsm_property_id = get_option( $npsm_property_id_name );
	$npsm_active = get_option( $npsm_active_name );
	
	//Validate the feature is active.
	if(isset($npsm_active) && $npsm_active=="1"){
		//Validate the stored value in the DB :: Must be an INT and greater the 0
		if(isset($npsm_property_id) && is_numeric($npsm_property_id) && $npsm_property_id>0){
					
			//Enque the JS
			echo '<!-- BEGIN NPS MONITORING CODE :: http://www.npsmonitoring.com -->';
			echo '<div class="npsWrapper" npsPropertyId="'.$npsm_property_id.'"><a href="http://www.npsmonitoring.com" title="Net Promoter Score NPS Monitoring">Net Promoter Score (NPS) Monitoring</a></div>';
			echo '<script src="http://www.npsmonitoring.com/v1/nps.js" type="text/javascript"></script>';
			echo '<!-- END NPS MONITORING CODE :: http://www.npsmonitoring.com -->';
		}
	}
}