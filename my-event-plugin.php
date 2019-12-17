<?php
/*
Plugin Name:Event Plugin
Plugin URI: 
Description: A plugin for showing events in a slider view
Version: 1.0
Author: Khurram Ansari , Fatima Maryam , Mehreen Sultana
Author URI: https://automattic.com/wordpress-plugins/
*/
define("PLUGIN_DIR_PATH",plugin_dir_path( __FILE__ ));
define("PLUGIN_DIR_URL",plugin_dir_url( __FILE__ ));

function add_custom_menu(){
    add_menu_page( "My Event Plugin",   //page title
     "My Event Plugin",  //menu title
      "manage_options", //capability 
      "my-event-plugin", //slug
       "all_event_view",  //callback func
       "http://icons.iconarchive.com/icons/dakirby309/simply-styled/16/Calendar-icon.png"); //icon

       add_submenu_page( "my-event-plugin",    //parent slug
        "All Events",//page title
         "All Events",//menu title
          "manage_options",//capability 
           "my-event-plugin",//slug
            "all_event_view" );//callback func
       add_submenu_page( "my-event-plugin", "Add Events", "Add Events", "manage_options", "add-event-plugin", "add_event_view" );
       add_submenu_page( "", "", "", "manage_options", "edit-event-plugin", "edit_event_view" );
}
add_action( "admin_menu", "add_custom_menu" );


function all_event_view(){
    include PLUGIN_DIR_PATH."views/all-events-view.php";

}
function add_event_view(){
    include PLUGIN_DIR_PATH."views/add-events-view.php";

}
function edit_event_view(){
    include PLUGIN_DIR_PATH."views/edit-events-view.php";

}
function link_assets(){
wp_enqueue_style( "my-event-style", PLUGIN_DIR_URL."assets/css/style.css");
wp_enqueue_style( "bootstrap-library-css", PLUGIN_DIR_URL."assets/css/bootstrap.min.css");
wp_enqueue_style( "datatable-library-css", PLUGIN_DIR_URL."assets/css/datatables.min.css");
wp_enqueue_style( "slick-library-css", PLUGIN_DIR_URL."assets/slick/slick.css");
wp_enqueue_style( "slicktheme-library-css", PLUGIN_DIR_URL."assets/slick/slick-theme.css");
wp_enqueue_script( "jqeury-library", PLUGIN_DIR_URL."assets/js/jquery.min.js","","",true);
wp_enqueue_script( "my-event-script", PLUGIN_DIR_URL."assets/js/script.js","","",true);
wp_enqueue_script( "notify-library", PLUGIN_DIR_URL."assets/js/notify.min.js","","",true);
wp_enqueue_script( "jquery-validate-library", PLUGIN_DIR_URL."assets/js/validate.min.js","","",true);
wp_enqueue_script( "bootstrap-library-js", PLUGIN_DIR_URL."assets/js/bootstrap.min.js","","",true);
wp_enqueue_script( "datatable-library-js", PLUGIN_DIR_URL."assets/js/datatables.min.js","","",true);
wp_enqueue_script( "slick-library-js", PLUGIN_DIR_URL."assets/slick/slick.min.js","","",true);


wp_localize_script( "my-event-script", "ajaxurl",admin_url( "admin-ajax.php"));
}
add_action( "init", "link_assets");

// if(isset($_REQUEST['action'])){
//     switch ($_REQUEST['action']){
//         case "event_plugin_library":
//             add_action( "admin_init", "event_plugin_library");
//             function event_plugin_library(){
//                 global $wpdb;
//                 include_once PLUGIN_DIR_PATH."library/event-plugin-lib.php";
                
//             } break;

//     }
// }

add_action( "wp_ajax_event_plugin_library","event_plugin_library_ajax_handler");
function event_plugin_library_ajax_handler(){
global $wpdb;
    if ($_REQUEST['param']=="add_event_data"){
    // add event to table
    $status=$wpdb->insert(get_table_name(),array(
        "title"=>$_REQUEST['txttitle'],
        "description"=>$_REQUEST['txtdescription'],
        "thumb"=>$_REQUEST['thumburl'],
        "date"=>$_REQUEST['txtdate'],
        "slug"=>$_REQUEST['txtslug']
    ));
    if ($status==1){
    echo json_encode(array("status"=>1,"message"=>"Event Added"));}
    else {
        echo json_encode(array("status"=>2,"message"=>"Event not Added"));
    }
    }
    if ($_REQUEST['param']=="edit_event_data"){
        // edit event to table
        $status=$wpdb->update(get_table_name(),array(
            "title"=>$_REQUEST['txttitle'],
            "description"=>$_REQUEST['txtdescription'],
            "thumb"=>$_REQUEST['thumburl'],
            "date"=>$_REQUEST['txtdate'],
            "slug"=>$_REQUEST['txtslug']
        ),array(
            "id"=>$_REQUEST['event_id']
        ));
        if ($status==1){
        echo json_encode(array("status"=>1,"message"=>"Event Edited"));
        }
        else {
            echo json_encode(array("status"=>2,"message"=>"Event not Edited"));

        }
    }
    if ($_REQUEST['param']=="delete_event_data"){
        // add event to table
        $status=$wpdb->delete(get_table_name(),array(
            "id"=>$_REQUEST['id']
        ));
        if ($status==1){
            echo json_encode(array("status"=>1,"message"=>"Event Deleted"));
        }
        else {
            echo json_encode(array("status"=>2,"message"=>"Event not Deleted"));

        }
    }
    wp_die();
}
// table generation on activation

function get_table_name(){
    global $wpdb;
    return $wpdb->prefix."event_plugin";
}

function add_event_plugin_table(){
    global $wpdb;
    $wp_track_table=get_table_name();
    require_once(ABSPATH. 'wp-admin/includes/upgrade.php');
    if (count($wpdb->get_var("SHOW TABLES LIKE '$wp_track_table'"))==0){
        $query="CREATE TABLE `".$wp_track_table."` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `title` varchar(200) NOT NULL,
            `description` text NOT NULL,
            `thumb` text NOT NULL,
            `date` date NOT NULL,
            `slug` varchar(200) NOT NULL,
            PRIMARY KEY (`id`)
           ) ENGINE=InnoDB DEFAULT CHARSET=latin1";
        dbDelta( $query);
    }
}
register_activation_hook( __FILE__, "add_event_plugin_table" );

function drop_event_plugin_table(){
    global $wpdb;
    $wp_track_table=get_table_name();
    $wpdb->query("Drop table IF EXISTS `".$wp_track_table."`");
}
register_deactivation_hook( __FILE__, "drop_event_plugin_table" );

add_shortcode("myeventplugin","short_code_view");
function short_code_view(){
    ob_start();
    include PLUGIN_DIR_PATH.'views/shortcode-template.php';
    $content=ob_get_contents();
    ob_end_clean();
    return $content;
}
function display_events_from_db(){
    global $wpdb;
    $allevents=$wpdb->get_results(
        $wpdb->prepare("select * from ". get_table_name() ." order by date ASC",""),ARRAY_A);
    return $allevents;
}