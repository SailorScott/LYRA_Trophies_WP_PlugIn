<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              www.linkedin.com
 * @since             1.0.0
 * @package           Lyra
 *
 * @wordpress-plugin
 * Plugin Name:       lyraTrophies
 * Plugin URI:        lyrawaters.org
 * Description:       Display LYRA's trophies.
 * Version:           1.0.0
 * Author:            Scott Nichols
 * Author URI:        www.linkedin.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       lyra
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('LYRA_VERSION', '1.1.0');
global $lyra_db_version;
$lyra_db_version = '1.0';



function lyraTrophies()
{
    wp_register_style('lyraTrophies', plugins_url('css/lyra_public_styles.css', __FILE__));
    wp_enqueue_style('lyraTrophies');
  
    wp_register_script("lyra_admin_boats",  plugins_url('lyra/js/lyra_admin_boats.js'), array('jquery'));
  //  wp_register_script("lyra_admin",  plugins_url('lyra/js/lyra_admin.js'), array('jquery'));
    wp_localize_script('lyra_admin_boats', 'myAjax', array('ajaxurl' => admin_url('admin-ajax.php')));
    wp_enqueue_script('jquery');
    wp_enqueue_script('lyra_admin_boats');



    wp_register_script("lyra_admin_winners",  plugins_url('lyra/js/lyra_admin_winners.js'), array('jquery'));
    wp_localize_script('lyra_admin_winners', 'myAjax', array('ajaxurl' => admin_url('admin-ajax.php')));
    wp_enqueue_script('lyra_admin_winners');
    // header("Location: http://www.rit.edu/");
  
}

add_action('init', 'lyraTrophies');
//add_action('wp_enqueue_scripts', 'lyraTrophies');
add_action('admin_menu', 'lyraAdmin_Landing');


function buildBoatLink(int $boatID, string $boatName)
{
    # <a href='../boat-details/?BoatId=". $post->BoatID."'>".$post->BoatName."</a>
    return "<a href='../boat-details/?BoatId=" . $boatID . "'>" . $boatName . "</a>";
}

function buildTrophyLink(int $trophyID, string $trophyName)
{
    # "<a href='../trophydetails/?trophyId=" .$post->TrophyID . "'>" .$post->TrophyNameShort . "</a>"
    return "<a href='../trophydetails/?trophyId=" . $trophyID . "'>" . $trophyName . "</a>";
}

function buildYearLink(int $year)
{
    # "<a href='../trophydetails/?trophyId=" .$post->TrophyID . "'>" .$post->TrophyNameShort . "</a>"
    return "<a href='../yeardetails/?regattYear=" . $year . "'>" . $year . "</a>";
}


require_once('inc/lyra_SC_trophyWinners.php');
require_once('inc/lyra_SC_Boat.php'); // details on a winning boat.
require_once('inc/lyra_SC_Years.php'); // List all winners for a year.

require_once('inc/lyra_admin.php'); // admin the trophy data landing page

// admin the boats page
require_once('inc/lyra_admin_boats.php'); 
require_once('inc/lyra_admin_boats_process.php');
add_action('wp_ajax_processBoat', 'processBoat');

// admin the winners page
require_once('inc/lyra_admin_winners.php'); 
require_once('inc/lyra_admin_winners_process.php');
add_action('wp_ajax_processWinners', array('Winners','process'));
// add_action('wp_ajax_nopriv_processBoat', 'processBoat');
