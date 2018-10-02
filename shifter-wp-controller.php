<?php
/*
Plugin Name: Shifter WP Controller
Plugin URI: https://github.com/getshifter/shifter-wp-controller
Description: Shifter controls from the WordPress Dashboard.
Version: 0.1.1
Author: DigitalCube
Author URI: https://getshifter.io
License: GPL2
*/

/*
 * Shifter WP API
 * Check if Shifter WordPress Plugin API Plugin Exists
 */

if (!class_exists('Shifter_API')) {
  return;
}

function shifter_icon() {
  return "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAYAAACNiR0NAAAABmJLR0QA/wD/AP+gvaeTAAAACXBIWXMAAC4jAAAuIwF4pT92AAAAB3RJTUUH4QUQCAwVRk5ANwAAAeFJREFUOMu9lEFIVEEYx38zs7LiQSja7BAaHcIoaqGDslFIyEadYusU0cki6yAaBV0FD8kSBGlLeVEwIcgkKMukDmEYLLXtJamI7dihQ0Qs+t73psM+t0V9vidI32mGmfl98/2/+Y/Cj9nMSN6IHIiJgxEHI+6ftplrW9hgxGrGFqgLWIscmk2OWqDajGS1ZS0A5RpwGeBDR7824hITB+05Xut8llLystKeKCNuRW/XVUpZ2fZlogKczYzQOdl1LiBpCYgD9aAO+vMe4Ea1Mq0KWDkO2BhA52QXr07dw3jSqj25YMTJp6Z7J/wDiQoMwC7L0ABs93lvEp/H06t0OjZ1EavUDNAHPHiXzu6PINnXHQujR3/sPR8ofKL6hpRKhMB+WaP3ATR9GgsAWo4Aj4Du9hdXX68D+yi6fuvO4v2l9bpMx5NLeeAMwNsTt0hN961J21UYflpKXtnYww6C/YMO/R+nRPHruO/xOuB32OaVdmPu5G2lrBf3fxyMuN6yU4y4uuoOcW1zMbcY5YaNvg3jIRf5BhyKAiz7TmgMqe5hpKYcftazhGUwBOY1F3M3I3c59bx3AMvjtVkWqzgN8D3ZHQ04n87S9vJ6BjgLzAGLFn4COWDP7vd3pgBaCndXnf0LIlef9HGSOIAAAAAASUVORK5CYII=";
}

// /*
//  * CSS Styles
//  * Admin and Front-End
//  */

add_action('wp_enqueue_scripts', 'add_shifter_support_css' );
add_action('admin_enqueue_scripts', 'add_shifter_support_css' );
function add_shifter_support_css() {

  // $shifter_css = plugins_url( 'src/css/main.css', __FILE__ );
  // wp_register_style("shifter-support", $shifter_css);

  wp_register_style("sweetalert2", "https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.26.11/sweetalert2.min.css");

  if (is_user_logged_in()) {
    wp_enqueue_style("sweetalert2");
  }
}

/*
 * JS Scripts
 * Admin and Front-End
 * Load after enqueue jQuery
 */

add_action('wp_enqueue_scripts', 'add_shifter_support_js' );
add_action('admin_enqueue_scripts', 'add_shifter_support_js' );
function add_shifter_support_js() {

  $shifter_js = plugins_url( 'main/main.js', __FILE__ );

  wp_register_script("shifter-js", $shifter_js, array( 'jquery' ));
  wp_localize_script('shifter-js', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );

  wp_register_script( 'sweetalert2', 'https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.26.11/sweetalert2.min.js', null, null, true );
  wp_localize_script('sweetalert2', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );

  if (is_user_logged_in()) {
    wp_enqueue_script("shifter-js");
    wp_enqueue_script("sweetalert2");
  }
}

/*
 * Admin Menu
 *
 */

add_action("wp_before_admin_bar_render", "add_shifter_support");
function add_shifter_support() {
  $local_class = getenv("SHIFTER_LOCAL") ? "disable_shifter_operation" : "";
  $api = new Shifter_API;
  global $wp_admin_bar;
  $shifter_top_menu = '
    <span class="ab-icon">
      <img src="'.shifter_icon().'" alt="Shifter Icon" />
    </span>
    <span class="ab-label">Shifter</span>';

    $shifter_support = array(
      "id" => "shifter_support",
      "title" => $shifter_top_menu
    );

  $shifter_support_back_to_shifter_dashboard = array(
    "id"    => "shifter_support_back_to_shifter_dashboard",
    "title" => "Shifter Dashboard <span style='font-family: dashicons; position: relative; top:-2px' class='dashicons dashicons-external'></span>",
    "parent" => "shifter",
    "href" => $api->shifter_dashboard_url,
    "meta" => array("target" => '_blank', "rel" => 'nofollow noopener noreferrer')
  );

  $shifter_support_terminate = array(
    "id"    => "shifter_support_terminate",
    "title" => "Terminate App",
    "parent" => "shifter",
    "href" => "#",
    "meta" => array("class" => $local_class)
  );

  $shifter_support_generate = array(
    "id"    => "shifter_support_generate",
    "title" => "Generate Artifact",
    "parent" => "shifter",
    "href" => "#",
    "meta" => array("class" => $local_class)
  );

  $wp_admin_bar->add_menu($shifter_support_back_to_shifter_dashboard);
  $wp_admin_bar->add_menu($shifter_support_generate);
  $wp_admin_bar->add_menu($shifter_support_terminate);
}

/*
 * Controller Functions
 *
 */

add_action("wp_ajax_shifter_app_terminate", "shifter_app_terminate");
function shifter_app_terminate() {
  $api = new Shifter_API;
  return $api->terminate_wp_app();
}

add_action("wp_ajax_shifter_app_generate", "shifter_app_generate");
function shifter_app_generate() {
  $api = new Shifter_API;
  return $api->generate_wp_app();
}
