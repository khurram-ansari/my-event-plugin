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
    add_submenu_page( "my-event-plugin",    //parent slug
        "Guide",//page title
        "Guide",//menu title
        "manage_options",//capability
        "guide-event-plugin",//slug
        "guide_event_view" );
}
add_action( "admin_menu", "add_custom_menu" );

function guide_event_view(){
    include PLUGIN_DIR_PATH."views/guide-view.php";
}
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
add_action( "wp_ajax_event_plugin_library","event_plugin_library_ajax_handler");
function event_plugin_library_ajax_handler(){
global $wpdb;
    if ($_REQUEST['param']=="add_event_data"){
   if (is_duplicate_event($_REQUEST['txtslug'])==0){
    $status=$wpdb->insert(get_table_name(),array(
        "title"=>$_REQUEST['txttitle'],
        "description"=>$_REQUEST['txtdescription'],
        "thumb"=>$_REQUEST['thumburl'],
        "date"=>$_REQUEST['txtdate'],
        "slug"=>$_REQUEST['txtslug']
    ));
    if ($status==1){
        // Gather post data.
        add_post_dynamically($_REQUEST['txttitle'],$_REQUEST['txtdate'],$_REQUEST['txtdescription'],$_REQUEST['txtslug'],$_REQUEST['thumburl']);
        echo json_encode(array("status"=>1,"message"=>"Event Added"));
    }
   }

    else {
        echo json_encode(array("status"=>2,"message"=>"Event not Added"));
    }
    }
    if ($_REQUEST['param']=="edit_event_data"){
        // edit event to table
        $prev_slug=$wpdb->get_var($wpdb->prepare("select slug from ".get_table_name()." where id=%d",$_REQUEST['event_id']));
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
            $pst_id=get_postid_by_slug($prev_slug);
            update_post_dynamically($pst_id,$_REQUEST['txttitle'],$_REQUEST['txtdate'],$_REQUEST['txtdescription'],$_REQUEST['txtslug'],$_REQUEST['thumburl']);
            echo json_encode(array("status"=>1,"message"=>"Event Edited"));
        }

        else {
            echo json_encode(array("status"=>2,"message"=>"Event not Edited"));
        }
    }
    if ($_REQUEST['param']=="delete_event_data"){
        // add event to table
        $eventslug=$_REQUEST['event_slug'];
        $status=$wpdb->delete(get_table_name(),array(
            "id"=>$_REQUEST['id']
        ));
        if ($status==1){
            $pst_id=get_postid_by_slug($eventslug);
            delete_post_dynamically($pst_id);
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
    add_category_on_activation();

}
function add_category_on_activation(){
    global $wpdocs_cat_id;
    $wpdocs_cat = array('cat_name' => 'Events', 'category_description' => 'An Event Category to show all events', 'category_nicename' => 'events', 'category_parent' => '');
    $wpdocs_cat_id = wp_insert_category($wpdocs_cat);
    if($wpdocs_cat_id)
    {
        update_option('wpdocs_cat_id',$wpdocs_cat_id);
    }

}
function is_duplicate_event($slug){
    global $wpdb;
    $slug_count=count($wpdb->get_var("Select * from ".get_table_name()." Where slug='".$slug."'"));
    return $slug_count;
}
function delete_category_on_deactivation(){
    $wpdocs_cat_id = get_option('wpdocs_cat_id');
    if($wpdocs_cat_id)
    {
        wp_delete_category($wpdocs_cat_id);
        delete_option('wpdocs_cat_id');
    }

}
register_activation_hook( __FILE__, "add_event_plugin_table" );

function drop_event_plugin_table(){
    global $wpdb;
    $wp_track_table=get_table_name();
    $wpdb->query("Drop table IF EXISTS `".$wp_track_table."`");
    delete_category_on_deactivation();

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
function get_postid_by_slug($slug,$type='post'){
    $post = get_page_by_path($slug, OBJECT, $type);
    return $post->ID;
}
function add_post_dynamically($pst_title,$pst_date,$pst_desc,$pst_slug,$pst_thumb){
    $my_post = array(
        'post_title'    => $pst_title,
        'post_content'  => '<b>Event Date: </b>'.$pst_date.'<br>'.$pst_desc,
        'post_status'   => 'publish',
        'post_author'   => get_current_user_id(),
        'post_name'=> $pst_slug,
        'comment_status'=> 'closed',
        'post_category'=>array(get_option('wpdocs_cat_id'))
    );
    $attachment_id = attachment_url_to_postid( $pst_thumb );
// Insert the post into the database.
    $postid=wp_insert_post( $my_post );
    set_post_thumbnail( $postid, $attachment_id);
}
function update_post_dynamically($id,$pst_title,$pst_date,$pst_desc,$pst_slug,$pst_thumb){
    $my_post = array(
        'ID' => $id,
        'post_title'    => $pst_title,
        'post_content'  => '<b>Event Date: </b>'.$pst_date.'<br>'.$pst_desc,
        'post_name'=> $pst_slug,
    );
    $attachment_id = attachment_url_to_postid( $pst_thumb );
// Insert the post into the database.
    $postid=wp_update_post( $my_post );
    set_post_thumbnail( $postid, $attachment_id);
}
function delete_post_dynamically($pstid){
wp_delete_post($pstid);
}