<?php
/*
Plugin Name: VM2X Insert GIPHY Images
Plugin URI: https://vm2x.com
Description:The fastest and easiest way to bring the full GIPHY experience directly to your Wordpress website - GIGHY GIFs Searcher for tinyMCE , Copy from kevin's vm plugin  - vm2x.com , It allow you use the powerful images libary of GIPHY in your wordpress through default content editor - tinyMCE, so you can use the GIPHY images libary in Wordpress’s Posts . By default, this plugin hides all functionality available in the new block editor (“Gutenberg”).
Author: Kevin Ye
Version: 1.1.0
*/


function vigi_init_js() {

    //wp_enqueue_script( 'my_custom_script', plugin_dir_url( __FILE__ ) . 'assets/js/loading-newpage.js', array(), '1.0' );


    wp_enqueue_script('thickbox');
    //加载css(wp自带)
    wp_enqueue_style('thickbox');

    wp_enqueue_style( 'my_custom_css', plugin_dir_url( __FILE__ ) . 'assets/css/commom.css', array(), '1.0' );
}
add_action( 'admin_enqueue_scripts', 'vigi_init_js' );


//0528
//let the div style - "display" work in $allowed_html
add_filter( 'safe_style_css', function( $styles ) {
    $styles[] = 'display';
    return $styles;
} );

add_action('admin_footer', 'vigi_add_admin_footer_function');
function vigi_add_admin_footer_function($data) {
    //$data = '<div id="my-content-id">This will be inserted at the bottom of admin page</div>';
    $allowed_html = array(
        'input' => array(
            'type'  => array(),
            'id'    => array(),
            'name'  => array(),
            'value' => array()
        ),
        'select' => array(
            'class'  => array(),
            'id'    => array(),
            'name'  => array()
        ),
        'option' => array(
            'value'  => array()
        ),
        'div' => array(
            'id'    => array(),
            'style' => array(
                'display' => array(),
                'overflow' => array()
            )
        ),
        'form' => array(
            'id'    => array(),
            'method' => array()
        ),
        'hr' => array()
    );

    echo wp_kses('<div id="my-content-id" style="display:none;overflow:scroll;"><form id="searchform_gif" method="post">
    <div>
        Keywords:
        <input type="text" name="keywords" id="keywords" class = "gipText"/>
        <select name="gType" id="gType" class = "select_default">
            <option value="1">gifs/search</option>
            <option value="2">stickers/search</option>
        </select>
        <input type="button" name="gifsearch_Btn" id="gifsearch_Btn"  value="Search" />
        <input type="hidden" name="g_index" id="g_index" value="1"/>
        <input type="hidden" name="g_limit" id="g_limit" value="20"/>
        <input type="hidden" name="loading_2_path" id="loading_2_path" value="' .plugin_dir_url( __FILE__ ).  '"/>
    </div>
<hr />
    <div id="loading"></div>
    <div id="ImageMain" style="width: 740px;height: 600px;overflow: scroll;"></div>
</form></div>',$allowed_html);




    //return $data;
}


if(version_compare(get_bloginfo('version'),'5.0.0','>='))
{
    add_filter('use_block_editor_for_post_type','__return_false');
}
else
{
    add_filter('gutenberg_can_edit_post_type','__return_false');
}

add_action( 'admin_head', 'vigi_add_shortcode_button');





function vigi_add_shortcode_button()
{

    add_filter( 'mce_external_plugins', 'vigi_add_shortcode_tinymce_plugin');

    add_filter( 'mce_buttons', 'vigi_register_shortcode_button' );


}



 function vigi_add_shortcode_tinymce_plugin( $plugin_array ) {


    $plugin_array['gy2mce_shortcodes_button'] = vigi_get_rs_plugin_url()  . '/assets/js/gy2mce-plugin.js';
     //$plugin_array['gy2mce_shortcodes_button'] = 'http://localhost/wp5.3/wp-content/plugins/giphy-tm/assets/js/gy2mce-plugin.js';

    return $plugin_array;
}



 function vigi_register_shortcode_button( $buttons ) {


      if(is_admin() && get_post_type() == "post")
      {
          array_push( $buttons,"|", 'gy2mce_shortcodes_button' );
      }

      return $buttons;
}




function vigi_get_rs_plugin_url(){

    $url = str_replace('index.php', '', plugins_url('index.php', __FILE__ ));
    if(strpos($url, 'http') === false) {
        $site_url	= get_site_url();
        $url		= (substr($site_url, -1) === '/') ? substr($site_url, 0, -1). $url : $site_url. $url;
    }
    $url = str_replace(array(chr(10), chr(13)), '', $url);

    return $url;
}




function vigi_settings_init() {
    // register a new setting for "reading" page
    register_setting('media', 'Giphy_k');

    register_setting('media', 'Giphy_c');

    // register a new section in the "reading" page
    add_settings_section(
        'wporg_settings_section',
        'GIPHY Config Section', 'vigi_settings_section_callback',
        'media'
    );

    // register a new field in the "wporg_settings_section" section, inside the "reading" page
    add_settings_field(
        'giphy_settings_apikey',
        'GIPHY ApiKey', 'vigi_settings_field_callback1',
        'media',
        'wporg_settings_section'
    );

    add_settings_field(
        'giphy_settings_count',
        'GIPHY Image Count', 'vigi_settings_field_callback2',
        'media',
        'wporg_settings_section'
    );

    add_settings_field(
        'giphy_settings_link',
        'Get Help from the Author', 'vigi_settings_field_callback3',
        'media',
        'wporg_settings_section'
    );

}

/**
 * register wporg_settings_init to the admin_init action hook
 */
add_action('admin_init', 'vigi_settings_init');

/**
 * callback functions
 */

// section content cb
function vigi_settings_section_callback() {
    esc_attr('<p> GIPHY Config.</p>');
}

// field content cb
function vigi_settings_field_callback1() {
    // get the value of the setting we've registered with register_setting()
    $setting_key = get_option('Giphy_k');
    if($setting_key == "")
    {

        $setting_key =  "sXpGFDGZs0Dv1mmNFvYaGUvYwKX0PWIh" ;
    }
    // output the field
    ?>
    <input type="text" name="Giphy_k" value="<?php echo isset( $setting_key ) ? esc_attr( $setting_key ) : ''; ?>">
    <?php
}

function vigi_settings_field_callback2() {
    // get the value of the setting we've registered with register_setting()
    $setting_cnt = get_option('Giphy_c');
    if($setting_cnt == "")
    {

        $setting_cnt =  "50" ;
    }
    // output the field
    ?>
    <input type="text" name="Giphy_c" value="<?php echo isset( $setting_cnt ) ? esc_attr( $setting_cnt ) : '50'; ?>">
    <?php
}

// field content cb
function vigi_settings_field_callback3() {
    ?>
    <a href="https://vm2x.com/?p=256">Visit the Pro Product here</a>
    <?php
}

//add in 0528
add_action( 'admin_enqueue_scripts', 'vigi_api_enqueue' );

/**
 * Enqueue my scripts and assets.
 *
 * @param $hook
 */
function vigi_api_enqueue( $hook ) {
    //if ( 'giphy-tm.php' !== $hook ) {
    //    return;
    //}
    wp_enqueue_script(
        'vigi_ajax-script',
        plugins_url( '/assets/js/loading-newpage.js', __FILE__ ),
        array( 'jquery' ),
        '1.0.0',
        true
    );

    wp_localize_script(
        'vigi_ajax-script',
        'vigi_my_ajax_obj',
        array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce'    => wp_create_nonce( 'giphy-tm-nonce' ),
        )
    );
}
add_action( 'wp_ajax_vigi_my_search_gifs', 'vigi_ajax_handler__json' );
function vigi_ajax_handler__json() {
    check_ajax_referer( 'giphy-tm-nonce' );


    $sear = sanitize_text_field($_POST['giphy_keywords']) ;
    $sear = str_replace( " " , "+" , $sear) ;
//sXpGFDGZs0Dv1mmNFvYaGUvYwKX0PWIh(这个是从官方教程中提取的官方测试API Key)
    $api_Type_Sel =sanitize_text_field($_POST['giphy_type']) ;   //选择API类型 1 - 7
    $APIArray = array(
        'http://api.giphy.com/v1/gifs/search',
        'http://api.giphy.com/v1/stickers/search',
        'http://api.giphy.com/v1/gifs/translate', //返回有一个object
        'http://api.giphy.com/v1/stickers/translate',//返回有一个object
        'http://api.giphy.com/v1/gifs/trending',  //未授权？？？？ 401 unauthorized
        'http://api.giphy.com/v1/stickers/trending', //未授权？？？？ 401 unauthorized
        'http://api.giphy.com/v1/trending/searches' //返回String[] ; 未授权？？？？ 401 unauthorized
    );
    $apiType = $APIArray[$api_Type_Sel-1];

//$api_key = "sXpGFDGZs0Dv1mmNFvYaGUvYwKX0PWIh" ; //(这个是从官方教程中提取的官方测试API Key)
//$api_key = "6UfRN9g33FWCkLv9zDXfG6Gg9KIxzGqz" ; //(这个是我的账号的API Key)

    $api_key = get_option('Giphy_k');
    $limit = get_option('Giphy_c') ;
    $api_index =  sanitize_text_field($_POST['g_index']) ; //g_index

    $urlArry = array(
        $apiType . "?q=" . $sear . "&api_key=" . $api_key . "&limit=" . $limit. "&offset=" . $api_index,
        $apiType . "?q=" . $sear . "&api_key=" . $api_key . "&limit=" . $limit. "&offset=" . $api_index,
        $apiType . "?s=" . $sear . "&api_key=" . $api_key . "&weirdness=9", //Value from 0-10 which makes results weirder as you go up the scale.
        $apiType . "?s=" . $sear . "&api_key=" . $api_key . "&weirdness=1", //Value from 0-10 which makes results weirder as you go up the scale.
        $apiType . "&api_key=" . $api_key. "&limit=" . $limit,
        $apiType . "&api_key=" . $api_key. "&limit=" . $limit,
        $apiType . "&api_key=" . $api_key

    );
    $return = json_decode(file_get_contents($urlArry[$api_Type_Sel-1]));


    if ($return->meta->status==200) {
        if ($return->data != null){

            $data = array('success' => 1, 'data' => $return->data,'limit' => $limit) ;
            wp_send_json($data);
        }
        else
        {
            vigi_logoutputtofile($return);
            wp_send_json(array('success' => 0));

        }
    } else {
        vigi_logoutputtofile($return);
        wp_send_json(array('success' => 0));

    }




    //wp_send_json( esc_html( $_POST['giphy_keywords'] ) ."33333333333333333" .esc_html( $_POST['giphy_type'] ) );
}


function vigi_logoutputtofile($arg)
{
    $log= vsprintf('%s', print_r($arg,true));
    $log= date('[Y/m/d H:i:s]') .'---'.$log. PHP_EOL;
    $path= dirname(__FILE__) .'/logs';
    $fp= file_put_contents($path,$log, FILE_APPEND);
    return true;
}

