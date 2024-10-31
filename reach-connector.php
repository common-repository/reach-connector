<?php

/**
 * @package Reach_Connector
*/
/*
Plugin Name: REACH Connector
Plugin URI: http://wordpress.org/plugins/reach-connector/
Description: This plugin enables you to easily integrate your REACH&#174; campaign and sponsorships with your WordPress site. For more information on REACH&#174; visit http://www.reachapp.co.
Author: Sugar Maple Interactive, LLC
Version: 3.2
Author URI: http://sugarmapleinteractive.com/code/wordpress/plugins/reach-connector
Text Domain: reach
License: GPLv2
*/
/*
Copyright 2018  Sugar Maple Interactive, LLC  (email : support@reachapp.co)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


if(!class_exists('Reach_Connector_Plugin')) {
  class Reach_Connector_Plugin
  {
    public function __construct() {
      // Register WordPress actions for custom plugin
      add_action('admin_init', array($this, 'admin_init'));
      add_action('admin_menu', array($this, 'admin_menu'));
    }

    // Activates WordPress plugin
    public static function activate() {
      // does nothing custom
    }

    // Deactivate the plugin
    public static function deactivate() {
      // does nothing custom
    }

    // Initialize Custom Plugin Settings
    public function init_settings() {
      // backend options (invisible to the user) [only creates if non-existent]
      add_option( 'reach_root_page_id' );
      add_option( 'reach_campaign_page_id' );
      // Setting "Keys"
      register_setting( 'reach-connector', 'reach_api_host' );
      register_setting( 'reach-connector', 'reach_account_guid' );
      register_setting( 'reach-connector', 'reach_sponsorship_class' );
      register_setting( 'reach-connector', 'reach_campaign_class' );
      register_setting( 'reach-connector', 'reach_disable_jquery' );
      // Setting Sections : Section ID, Section Title, Callback, Page ID (Menu Slug)
      add_settings_section('section-one', 'REACH&#174; Account Information', array($this, 'text_for_section_one'), 'reach-connector-options' );
    	// Setting Fields : Filed ID, Field Title, Callback, Page ID (Menu Slug), Section ID
      add_settings_field( 'reach_api_host', 'REACH&#174; Account URL', array($this, 'field_for_api_host'), 'reach-connector-options', 'section-one' );
      add_settings_field( 'reach_account_guid', 'Account GUID', array($this, 'field_for_account_guid'), 'reach-connector-options', 'section-one' );
      add_settings_section('style-section', 'Options', array($this, 'text_for_style_section'), 'reach-connector-options' );
      add_settings_field( 'reach_disable_jquery', 'Disable Included jQuery', array($this, 'field_for_disable_jquery'), 'reach-connector-options', 'style-section' );
      add_settings_field( 'reach_sponsorship_class', 'Sponsorship CSS Class', array($this, 'field_for_sponsorship_classes'), 'reach-connector-options', 'style-section' );
      add_settings_field( 'reach_campaign_class', 'Campaign CSS Class', array($this, 'field_for_campaign_classes'), 'reach-connector-options', 'style-section' );
      add_settings_section('section-two', 'Sponsorship Shortcode Setup', array($this, 'text_for_section_two'), 'reach-connector-options' );
      add_settings_section('section-three', 'Campaigns Shortcode Setup', array($this, 'text_for_section_three'), 'reach-connector-options' );
      add_settings_section('section-four', 'Campaign Page Shortcode Setup', array($this, 'text_for_section_four'), 'reach-connector-options' );
      add_settings_section('section-five', 'Donation Shortcode Setup', array($this, 'text_for_section_five'), 'reach-connector-options' );
      add_settings_section('section-six', 'Projects Shortcode Setup', array($this, 'text_for_section_six'), 'reach-connector-options' );
      add_settings_section('section-seven', 'Project Page Shortcode Setup', array($this, 'text_for_section_seven'), 'reach-connector-options' );
      add_settings_section('section-eight', 'Places Shortcode Setup', array($this, 'text_for_section_eight'), 'reach-connector-options' );
      add_settings_section('section-nine', 'Place Page Shortcode Setup', array($this, 'text_for_section_nine'), 'reach-connector-options' );
      add_settings_section('section-ten', 'Custom Form Shortcode Setup', array($this, 'text_for_section_ten'), 'reach-connector-options' );
      add_settings_section('section-eleven', 'Products Shortcode Setup', array($this, 'text_for_section_eleven'), 'reach-connector-options' );
      add_settings_section('section-twelve', 'Events Shortcode Setup', array($this, 'text_for_section_twelve'), 'reach-connector-options' );
      add_settings_section('section-thirteen', 'Calendar Shortcode Setup', array($this, 'text_for_section_thirteen'), 'reach-connector-options' );
      add_settings_section('section-fourteen', 'Event Page Shortcode Setup', array($this, 'text_for_section_fourteen'), 'reach-connector-options' );
      add_settings_section('section-fifteen', 'Supporter Login Shortcode Setup', array($this, 'text_for_section_fifteen'), 'reach-connector-options' );
    }

    // Hook for WordPress admin_init action
    public function admin_init() {
      // Set up the settings for this plugin
      $this->init_settings();
    }

    // Hook for WordPress admin_menu action
    public function admin_menu() {
      # Page Title, Menu Item, User Capability, Menu Slug (Page ID), Callback
      add_options_page('REACH&#174; Connector Options', 'REACH&#174; Connector',
        'manage_options', 'reach-connector-options',
        array($this, 'plugin_settings'));
    }

    public function plugin_settings() {
      if(!current_user_can('manage_options'))
      {
          wp_die(__('Your account does not have sufficient permissions to manage plugin settings.'));
      }

      // Render the settings template
      include(sprintf("%s/templates/settings.php", dirname(__FILE__)));
    }

    public function setting_section_title() {
      echo "<p>REACH&#174; Connector Settings Intro</p>";
    }

    public function field_for_account_guid() {
      $setting_value = esc_attr( get_option( 'reach_account_guid' ) );
      echo "<input class='regular-text' type='text' name='reach_account_guid' value='$setting_value' placeholder='GUID' />";
    }

    public function field_for_api_host() {
      $setting_value = esc_attr( get_option( 'reach_api_host' ) );
      echo "<input class='regular-text' type='text' name='reach_api_host' value='$setting_value' placeholder='domain.reachapp.co' />";
    }
    
    public function field_for_sponsorship_classes() {
      $setting_value = esc_attr( get_option( 'reach_sponsorship_class' ) );
      echo "<input class='regular-text' type='text' name='reach_sponsorship_class' value='$setting_value' />";
    }
    
    public function field_for_campaign_classes() {
      $setting_value = esc_attr( get_option( 'reach_campaign_class' ) );
      echo "<input class='regular-text' type='text' name='reach_campaign_class' value='$setting_value' />";
    }
    
    public function field_for_disable_jquery() {
      $setting_value = esc_attr( get_option( 'reach_disable_jquery' ) );
      if ($setting_value == 1) {
        echo "<input type='checkbox' name='reach_disable_jquery' value='1' checked /> ";
      } else {
        echo "<input type='checkbox' name='reach_disable_jquery' value='1' /> ";
      }
    }

    public function text_for_section_one() {
    	echo "Enter your REACH&#174; Account URL and Account GUID to setup the REACH&#174; Connector plugin.";
    }
    
    public function text_for_style_section() {
    	echo "The REACH Connector requires jQuery. If you are already using jQuery on your site disable the auto-included version. Also, enter additional CSS classes to use for the sponsorship and campaign widgets. Separate class names with a space.";
    }
    
    public function text_for_section_two() {
    	echo "To pull a list of sponsorships from REACH&#174; to display on your site use the shortcode [sponsorships]. You can also pass optional parameters to filter your sponsorship results similar to the dropdown filters on the Sponsorships page using the parameters:";
      echo "<p><blockquote>sponsorship_type<br/>location<br/>project<br/>sponsorship_categories<br/>status</blockquote></p>";
      echo '<p>Example: [sponsorships sponsorship_type="children"]';
    }
    
    public function text_for_section_three() {
    	echo "To pull a list of campaigns from REACH&#174; to display on your site use the shortcode [campaigns].";
    }
    
    public function text_for_section_four() {
    	echo "To pull a specific campaign from REACH&#174; to display on your site use the shortcode with the permalink shown in REACH&#174; [campaign permalink='my-campaign-permalink'].";
    }
    
    public function text_for_section_five() {
    	echo "To display a donation page from REACH&#174; on your site use the shortcode [donations] on any page. You can also pass optional paramters defined in the Giving Options page in REACH to customize the donation form by setting amount, recurring period, purpose etc.";
      echo "<p><blockquote>amount<br/>fixed_amount (true/false)<br/>recurring<br/>fixed_recurring (true/false)<br/>referral</blockquote></p>";
      echo '<p>Example: [donations amount="50"]';
    }
    
    public function text_for_section_six() {
    	echo "To pull a list of projects from REACH&#174; to display on your site use the shortcode [projects].";
    }
    
    public function text_for_section_seven() {
    	echo "To pull a specific project from REACH&#174; to display on your site use the shortcode with the permalink shown in REACH&#174; [project permalink='my-project-permalink'].";
    }
    
    public function text_for_section_eight() {
    	echo "To pull a list of places from REACH&#174; to display on your site use the shortcode [places].";
    }
    
    public function text_for_section_nine() {
    	echo "To pull a specific place from REACH&#174; to display on your site use the shortcode with the permalink shown in REACH&#174; [place permalink='my-place-permalink'].";
    }
    
    public function text_for_section_ten() {
    	echo "To pull a custom form from REACH&#174; to display on your site use the shortcode with the permalink shown in REACH&#174; [custom_form permalink='my-custom-form-permalink'].";
    }
    
    public function text_for_section_eleven() {
    	echo "To pull a list of products from REACH&#174; to display on your site use the shortcode [products].";
    }
    
    public function text_for_section_twelve() {
    	echo "To pull a list of events from REACH&#174; to display on your site use the shortcode [events].";
    }
    
    public function text_for_section_thirteen() {
    	echo "To display a calendar of events from REACH&#174; on your site use the shortcode [calendar]";
    }
    
    public function text_for_section_fourteen() {
    	echo "To pull a specific event from REACH&#174; to display on your site use the shortcode with the permalink shown in REACH&#174; [event permalink='my-event-permalink'].";
    }
    
    public function text_for_section_fifteen() {
    	echo "To display a login page for supporters from REACH&#174; on your site use the shortcode [supporter_login].";
    }
  }
}

function reach_get_sponsorship_json($i) {
  $search  = array('https://', 'http://');
  $reach_api_host = esc_attr( get_option( 'reach_api_host' ) );
  $url = "https://".str_replace($search, '', $reach_api_host)."/sponsorships.json?".http_build_query($i);
  $json = json_decode(file_get_contents($url));
	return $json;
}

function reach_get_campaign_json($i) {
  $search  = array('https://', 'http://');
  $reach_api_host = esc_attr( get_option( 'reach_api_host' ) );
  $url = "https://".str_replace($search, '', $reach_api_host)."/campaigns.json?".http_build_query($i);
  $json = json_decode(file_get_contents($url));
	return $json;
}

function reach_get_sponsorships($atts) {
  $reach_disable_jquery = esc_attr( get_option( 'reach_disable_jquery' ) );
  $reach_api_host = esc_attr( get_option( 'reach_api_host' ) );
  $reach_sponsorship_class = esc_attr( get_option( 'reach_sponsorship_class' ) );
  $search  = array('https://', 'http://');
  $reach_api_host = esc_attr( get_option( 'reach_api_host' ) );
  $atts = shortcode_atts( array(
      'sponsorship_type' => '',
      'location' => '',
      'project' => '',
      'sponsorship_categories' => '',
      'status' => '',
      'hide_filters' => '',
      'disablenav' => 'true',
  ), $atts, 'sponsorships' );
  
  $reach_html = "<iframe id='sponsorships-iframe' src='https://".str_replace($search, '', $reach_api_host)."/sponsorships?".http_build_query($atts)."' width='100%' height='2000px' scrolling='no' frameborder='0'></iframe>";
  if ($reach_disable_jquery != 1) { $reach_html.='<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>'; }
  $reach_html.="<script type='text/javascript' src='https://dkx8xz7sz3t1z.cloudfront.net/static-assets/iframeResizer.min.js'></script>";
  $reach_html.='<script>jQuery("#sponsorships-iframe").iFrameResize({checkOrigin:false});</script>';
  
  return $reach_html;
}

function reach_get_campaigns() {
  $reach_disable_jquery = esc_attr( get_option( 'reach_disable_jquery' ) );
  $reach_api_host = esc_attr( get_option( 'reach_api_host' ) );
  $reach_campaign_class = esc_attr( get_option( 'reach_campaign_class' ) );
  $search  = array('https://', 'http://');
  $reach_api_host = esc_attr( get_option( 'reach_api_host' ) );
  
  $reach_html = "<iframe id='campaigns-iframe' src='https://".str_replace($search, '', $reach_api_host)."/campaigns?disablenav=true' width='100%' scrolling='no' frameborder='0'></iframe>";
  if ($reach_disable_jquery != 1) { $reach_html.='<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>'; }
  $reach_html.="<script type='text/javascript' src='https://dkx8xz7sz3t1z.cloudfront.net/static-assets/iframeResizer.min.js'></script>";
  $reach_html.='<script>jQuery("#campaigns-iframe").iFrameResize({checkOrigin:false,scrollCallback:(0,0)});</script>';
  
  return $reach_html;
}

function reach_get_projects() {
  $reach_disable_jquery = esc_attr( get_option( 'reach_disable_jquery' ) );
  $reach_api_host = esc_attr( get_option( 'reach_api_host' ) );
  $search  = array('https://', 'http://');
  $reach_api_host = esc_attr( get_option( 'reach_api_host' ) );
  
  $reach_html = "<iframe id='projects-iframe' src='https://".str_replace($search, '', $reach_api_host)."/projects?disablenav=true' width='100%' scrolling='no' frameborder='0'></iframe>";
  if ($reach_disable_jquery != 1) { $reach_html.='<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>'; }
  $reach_html.="<script type='text/javascript' src='https://dkx8xz7sz3t1z.cloudfront.net/static-assets/iframeResizer.min.js'></script>";
  $reach_html.='<script>jQuery("#projects-iframe").iFrameResize({checkOrigin:false});</script>';
  
  return $reach_html;
}

function reach_get_places() {
  $reach_disable_jquery = esc_attr( get_option( 'reach_disable_jquery' ) );
  $reach_api_host = esc_attr( get_option( 'reach_api_host' ) );
  $search  = array('https://', 'http://');
  $reach_api_host = esc_attr( get_option( 'reach_api_host' ) );
  
  $reach_html = "<iframe id='places-iframe' src='https://".str_replace($search, '', $reach_api_host)."/places?disablenav=true' width='100%' scrolling='no' frameborder='0'></iframe>";
  if ($reach_disable_jquery != 1) { $reach_html.='<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>'; }
  $reach_html.="<script type='text/javascript' src='https://dkx8xz7sz3t1z.cloudfront.net/static-assets/iframeResizer.min.js'></script>";
  $reach_html.='<script>jQuery("#places-iframe").iFrameResize({checkOrigin:false});</script>';
  
  return $reach_html;
}

function reach_get_project_page($atts) {
  $reach_disable_jquery = esc_attr( get_option( 'reach_disable_jquery' ) );
  $reach_api_host = esc_attr( get_option( 'reach_api_host' ) );
  $search  = array('https://', 'http://');
  $reach_api_host = esc_attr( get_option( 'reach_api_host' ) );
  $atts = shortcode_atts( array(
      'permalink' => '',
      'disablenav' => 'true',
  ), $atts, 'sponsorships' );
  
  $reach_html = "<iframe id='".$atts['permalink']."-iframe' src='https://".str_replace($search, '', $reach_api_host)."/projects/".$atts['permalink']."?disablenav=true' width='100%' scrolling='no' frameborder='0'></iframe>";
  if ($reach_disable_jquery != 1) { $reach_html.='<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>'; }
  $reach_html.="<script type='text/javascript' src='https://dkx8xz7sz3t1z.cloudfront.net/static-assets/iframeResizer.min.js'></script>";
  $reach_html.='<script>jQuery("#'.$atts['permalink'].'-iframe").iFrameResize({checkOrigin:false});</script>';
  
  return $reach_html;
}

function reach_get_place_page($atts) {
  $reach_disable_jquery = esc_attr( get_option( 'reach_disable_jquery' ) );
  $reach_api_host = esc_attr( get_option( 'reach_api_host' ) );
  $search  = array('https://', 'http://');
  $reach_api_host = esc_attr( get_option( 'reach_api_host' ) );
  $atts = shortcode_atts( array(
      'permalink' => '',
      'disablenav' => 'true',
  ), $atts, 'sponsorships' );
  
  $reach_html = "<iframe id='".$atts['permalink']."-iframe' src='https://".str_replace($search, '', $reach_api_host)."/places/".$atts['permalink']."?disablenav=true' width='100%' scrolling='no' frameborder='0'></iframe>";
  if ($reach_disable_jquery != 1) { $reach_html.='<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>'; }
  $reach_html.="<script type='text/javascript' src='https://dkx8xz7sz3t1z.cloudfront.net/static-assets/iframeResizer.min.js'></script>";
  $reach_html.='<script>jQuery("#'.$atts['permalink'].'-iframe").iFrameResize({checkOrigin:false});</script>';
  
  return $reach_html;
}

function reach_get_campaign_page($atts) {
  $reach_disable_jquery = esc_attr( get_option( 'reach_disable_jquery' ) );
  $reach_api_host = esc_attr( get_option( 'reach_api_host' ) );
  $search  = array('https://', 'http://');
  $reach_api_host = esc_attr( get_option( 'reach_api_host' ) );
  $atts = shortcode_atts( array(
      'permalink' => '',
      'disablenav' => 'true',
  ), $atts, 'sponsorships' );
  
  $reach_html = "<iframe id='".$atts['permalink']."-iframe' src='https://".str_replace($search, '', $reach_api_host)."/campaigns/".$atts['permalink']."?disablenav=true' width='100%' scrolling='no' frameborder='0'></iframe>";
  if ($reach_disable_jquery != 1) { $reach_html.='<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>'; }
  $reach_html.="<script type='text/javascript' src='https://dkx8xz7sz3t1z.cloudfront.net/static-assets/iframeResizer.min.js'></script>";
  $reach_html.='<script>jQuery("#'.$atts['permalink'].'-iframe").iFrameResize({checkOrigin:false});</script>';
  
  return $reach_html;
}

function reach_get_donation_page($atts) {
  $reach_disable_jquery = esc_attr( get_option( 'reach_disable_jquery' ) );
  $iframe_id = uniqid();
  $reach_api_host = esc_attr( get_option( 'reach_api_host' ) );
  $reach_campaign_class = esc_attr( get_option( 'reach_campaign_class' ) );
  $search  = array('https://', 'http://');
  $reach_api_host = esc_attr( get_option( 'reach_api_host' ) );
  $atts = shortcode_atts( array(
      'amount' => '',
      'fixed_amount' => '',
      'recurring' => '',
      'fixed_recurring' => '',
      'referral' => '',
      'disablenav' => 'true',
  ), $atts, 'sponsorships' );
  
  $reach_html = "<iframe id='donations-".$iframe_id."-iframe' src='https://".str_replace($search, '', $reach_api_host)."/donations/new?".http_build_query($atts)."' width='100%' scrolling='no' frameborder='0'></iframe>";
  if ($reach_disable_jquery != 1) { $reach_html.='<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>'; }
  $reach_html.="<script type='text/javascript' src='https://dkx8xz7sz3t1z.cloudfront.net/static-assets/iframeResizer.min.js'></script>";
  $reach_html.='<script>jQuery("#donations-'.$iframe_id.'-iframe").iFrameResize({checkOrigin:false});</script>';
  
  return $reach_html;
}

function reach_get_custom_form($atts) {
  $reach_disable_jquery = esc_attr( get_option( 'reach_disable_jquery' ) );
  $reach_api_host = esc_attr( get_option( 'reach_api_host' ) );
  $search  = array('https://', 'http://');
  $reach_api_host = esc_attr( get_option( 'reach_api_host' ) );
  $atts = shortcode_atts( array(
      'permalink' => '',
      'disablenav' => 'true',
  ), $atts, 'sponsorships' );
  
  $reach_html = "<iframe id='custom-form-".$atts['permalink']."-iframe' src='https://".str_replace($search, '', $reach_api_host)."/custom_forms/".$atts['permalink']."?disablenav=true' width='100%' scrolling='no' frameborder='0'></iframe>";
  if ($reach_disable_jquery != 1) { $reach_html.='<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>'; }
  $reach_html.="<script type='text/javascript' src='https://dkx8xz7sz3t1z.cloudfront.net/static-assets/iframeResizer.min.js'></script>";
  $reach_html.="<script>jQuery('#custom-form-".$atts['permalink']."-iframe').iFrameResize({checkOrigin:false});</script>";
  
  return $reach_html;
}

function reach_get_products() {
  $reach_disable_jquery = esc_attr( get_option( 'reach_disable_jquery' ) );
  $reach_api_host = esc_attr( get_option( 'reach_api_host' ) );
  $search  = array('https://', 'http://');
  $reach_api_host = esc_attr( get_option( 'reach_api_host' ) );
  
  $reach_html = "<iframe id='products-iframe' src='https://".str_replace($search, '', $reach_api_host)."/products?disablenav=true' width='100%' scrolling='no' frameborder='0'></iframe>";
  if ($reach_disable_jquery != 1) { $reach_html.='<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>'; }
  $reach_html.="<script type='text/javascript' src='https://dkx8xz7sz3t1z.cloudfront.net/static-assets/iframeResizer.min.js'></script>";
  $reach_html.='<script>jQuery("#products-iframe").iFrameResize({checkOrigin:false});</script>';
  
  return $reach_html;
}

function reach_get_events() {
  $reach_disable_jquery = esc_attr( get_option( 'reach_disable_jquery' ) );
  $reach_api_host = esc_attr( get_option( 'reach_api_host' ) );
  $search  = array('https://', 'http://');
  $reach_api_host = esc_attr( get_option( 'reach_api_host' ) );
  
  $reach_html = "<iframe id='events-iframe' src='https://".str_replace($search, '', $reach_api_host)."/events?disablenav=true' width='100%' scrolling='no' frameborder='0'></iframe>";
  if ($reach_disable_jquery != 1) { $reach_html.='<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>'; }
  $reach_html.="<script type='text/javascript' src='https://dkx8xz7sz3t1z.cloudfront.net/static-assets/iframeResizer.min.js'></script>";
  $reach_html.='<script>jQuery("#events-iframe").iFrameResize({checkOrigin:false});</script>';
  
  return $reach_html;
}

function reach_get_calendar_page() {
  $reach_disable_jquery = esc_attr( get_option( 'reach_disable_jquery' ) );
  $reach_api_host = esc_attr( get_option( 'reach_api_host' ) );
  $search  = array('https://', 'http://');
  $reach_api_host = esc_attr( get_option( 'reach_api_host' ) );
  
  $reach_html = "<iframe id='calendar-iframe' src='https://".str_replace($search, '', $reach_api_host)."/calendar?disablenav=true' width='100%' scrolling='no' frameborder='0'></iframe>";
  if ($reach_disable_jquery != 1) { $reach_html.='<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>'; }
  $reach_html.="<script type='text/javascript' src='https://dkx8xz7sz3t1z.cloudfront.net/static-assets/iframeResizer.min.js'></script>";
  $reach_html.='<script>jQuery("#calendar-iframe").iFrameResize({checkOrigin:false});</script>';
  
  return $reach_html;
}

function reach_get_event_page($atts) {
  $reach_disable_jquery = esc_attr( get_option( 'reach_disable_jquery' ) );
  $reach_api_host = esc_attr( get_option( 'reach_api_host' ) );
  $search  = array('https://', 'http://');
  $reach_api_host = esc_attr( get_option( 'reach_api_host' ) );
  $atts = shortcode_atts( array(
      'permalink' => '',
      'disablenav' => 'true',
  ), $atts, 'sponsorships' );
  
  $reach_html = "<iframe id='event-".$atts['permalink']."-iframe' src='https://".str_replace($search, '', $reach_api_host)."/events/".$atts['permalink']."?disablenav=true' width='100%' scrolling='no' frameborder='0'></iframe>";
  if ($reach_disable_jquery != 1) { $reach_html.='<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>'; }
  $reach_html.="<script type='text/javascript' src='https://dkx8xz7sz3t1z.cloudfront.net/static-assets/iframeResizer.min.js'></script>";
  $reach_html.="<script>jQuery('#event-".$atts['permalink']."-iframe').iFrameResize({checkOrigin:false});</script>";
  
  return $reach_html;
}

function reach_get_supporter_page($atts) {
  $reach_disable_jquery = esc_attr( get_option( 'reach_disable_jquery' ) );
  $reach_api_host = esc_attr( get_option( 'reach_api_host' ) );
  $search  = array('https://', 'http://');
  $reach_api_host = esc_attr( get_option( 'reach_api_host' ) );
  $atts = shortcode_atts( array(
      'permalink' => '',
      'disablenav' => 'true',
  ), $atts, 'sponsorships' );
  
  $reach_html = "<iframe id='supporter-iframe' src='https://".str_replace($search, '', $reach_api_host)."/users?disablenav=true' height='800px' width='100%' scrolling='no' frameborder='0'></iframe>";
  if ($reach_disable_jquery != 1) { $reach_html.='<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>'; }
  $reach_html.="<script type='text/javascript' src='https://dkx8xz7sz3t1z.cloudfront.net/static-assets/iframeResizer.min.js'></script>";
  $reach_html.='<script>jQuery("#supporter-iframe").iFrameResize({checkOrigin:false});</script>';
  
  return $reach_html;
}

add_shortcode('sponsorships', 'reach_get_sponsorships');
add_shortcode('campaigns', 'reach_get_campaigns');
add_shortcode('campaign', 'reach_get_campaign_page');
add_shortcode('projects', 'reach_get_projects');
add_shortcode('project', 'reach_get_project_page');
add_shortcode('places', 'reach_get_places');
add_shortcode('place', 'reach_get_place_page');
add_shortcode('donations', 'reach_get_donation_page');
add_shortcode('custom_form', 'reach_get_custom_form');
add_shortcode('products', 'reach_get_products');
add_shortcode('events', 'reach_get_events');
add_shortcode('calendar', 'reach_get_calendar_page');
add_shortcode('event', 'reach_get_event_page');
add_shortcode('supporter_login', 'reach_get_supporter_page');
add_shortcode('reach_sponsorships', 'reach_get_sponsorships');
add_shortcode('reach_campaigns', 'reach_get_campaigns');
add_shortcode('reach_campaign', 'reach_get_campaign_page');
add_shortcode('reach_projects', 'reach_get_projects');
add_shortcode('reach_project', 'reach_get_project_page');
add_shortcode('reach_places', 'reach_get_places');
add_shortcode('reach_place', 'reach_get_place_page');
add_shortcode('reach_donations', 'reach_get_donation_page');
add_shortcode('reach_custom_form', 'reach_get_custom_form');
add_shortcode('reach_products', 'reach_get_products');
add_shortcode('reach_events', 'reach_get_events');
add_shortcode('reach_calendar', 'reach_get_calendar_page');
add_shortcode('reach_event', 'reach_get_event_page');
add_shortcode('reach_supporter_login', 'reach_get_supporter_page');

if(class_exists('Reach_Connector_Plugin')) {
  // WordPress hooks to activate and deactivate the plugin
  register_activation_hook(__FILE__, array('Reach_Connector_Plugin', 'activate'));
  register_deactivation_hook(__FILE__, array('Reach_Connector_Plugin', 'deactivate'));

  // instantiate the plugin class
  $reach_connector_plugin = new Reach_Connector_Plugin();
}

// Adds link to the plugin settings page on the plugin info page
if(isset($reach_connector_plugin)) {

  function custom_settings_link($links) {
    $settings_link = '<a href="options-general.php?page=reach-connector-options">Settings</a>';
    array_unshift($links, $settings_link);
    return $links;
  }

  $plugin = plugin_basename(__FILE__);
  add_filter("plugin_action_links_$plugin", 'custom_settings_link');
}


?>