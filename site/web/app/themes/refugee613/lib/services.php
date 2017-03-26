<?php
/**
* Don't load this file directly
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$r613_services = new R613Services();

/** DEFINE THE CLASS THAT HANDLES THE BACK END **/
class R613Services {
  private $ajax_nonce = 'lBmgNDd9pWYYv<;<HlC<cvm|Ndo(DaVA<xZh!~-}]Xb>@6;6+-hY^Tt3sJ)H30TJ';

  function __construct() {
		add_action( 'init', array( $this, 'init' ) );
	}

	function init() {
    add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );
    add_action( 'wp_ajax_json_get_services', array( $this, 'json_get_services' ) );
    add_action( 'wp_ajax_nopriv_json_get_services', array( $this, 'json_get_services' ) );
  }

  function wp_enqueue_scripts() {
    wp_enqueue_script( 'ajax_custom_script',  get_stylesheet_directory_uri() . '/js/r613ajax.js', array('jquery') );
    //wp_localize_script( 'ajax_custom_script', 'frontendajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));
    wp_localize_script( 'ajax_custom_script', 'MyAjax', array(
       'ajaxurl' => admin_url( 'admin-ajax.php' ),
       'security' => wp_create_nonce( $this->ajax_nonce )
     ) );
  }

  private function get_services( $_service_type, $_languages = array(), $_client_types = array() ) {
    $_tax_query = array();
    $_tax_query[] = array(
      'taxonomy' => 'services',
      'field' => 'slug',
      'terms' => array( $_service_type ),
    );
    if ( ( is_array( $_languages ) ) && ( ! empty( $_languages ) ) ) {
      $_tax_query[] = array(
        'taxonomy' => 'language',
        'field' => 'slug',
        'terms' => $_languages,
      );
    }
    if ( ( is_array( $_client_types ) ) && ( ! empty( $_client_types ) ) ) {
      $_tax_query[] = array(
        'taxonomy' => 'client_type',
        'field' => 'slug',
        'terms' => $_client_types,
      );
    }
    $_args = array(
      'posts_per_page' => 20,
      'post_type' => 'services',
      'post_status' => 'publish',
      'tax_query' => $_tax_query,
      'suppress_filters' => false,
    );
    $_posts = get_posts( $_args );
    return $_posts;
  }

  public function json_get_services() {
    check_ajax_referer( $this->ajax_nonce, 'security' );
    $_output = array();
    $_service_type = isset( $_POST['service_type'] ) ? trim( $_POST['service_type'] ) : '';
    $_languages = isset( $_POST['languages'] ) ? ( is_array( $_POST['languages'] ) ? $_POST['languages'] : array() ) : array();
    $_clients = isset( $_POST['clients'] ) ? ( is_array( $_POST['clients'] ) ? $_POST['clients'] : array() ) : array();
    if ( '' !== $_service_type ) {
      $_posts = $this->get_services( $_service_type, $_languages, $_clients );
      foreach ( $_posts as $_post ) {
        $_output[] = $this->get_service_details( $_post );
      }
    }
    die( json_encode( $_output ) );
    //die( json_encode( $_output ) );
  }

  public function get_service_details( $_post ) {
    $_output = new \stdClass;
    $_output->id = $_post->ID;
    $_output->title = $_post->post_title;
    $_output->excerpt = $_post->post_excerpt;
    $_output->languages = $this->get_service_taxonomy( $_post->ID, 'language' );
    $_output->clients = $this->get_service_taxonomy( $_post->ID, 'client_type' );
    $_meta = get_post_meta( $_post->ID );
    $_output->location = isset( $_meta['location'][0] ) ? trim( $_meta['location'][0] ) : '';
    $_output->phone = isset( $_meta['phone'][0] ) ? trim( $_meta['phone'][0] ) : '';
    $_output->latitude = isset( $_meta['latitude'][0] ) ? trim( $_meta['latitude'][0] ) : '';
    $_output->longitude = isset( $_meta['longitude'][0] ) ? trim( $_meta['longitude'][0] ) : '';
    $_output->website = isset( $_meta['website'][0] ) ? trim( $_meta['website'][0] ) : '';
    return $_output;
  }

  public function get_service_taxonomy( $_post_id, $_type ) {
    $_output = array();
    $_taxonomies = wp_get_post_terms( $_post_id, array( $_type ) );
    foreach( $_taxonomies as $_taxonomy ) {
      $_tax = new \stdClass;
      $_tax->slug = $_taxonomy->slug;
      $_tax->name = $_taxonomy->name;
      $_output[] = $_tax;
    }
    return $_output;
  }

  public function get_service_type( $_post ) {
  	return strtolower( $_post->post_name );
  }

  public function html_list_services( $_post ) {
  	$_service_type = $this->get_service_type( $_post );
  	$_term = get_term_by( 'slug', $_service_type, 'services', OBJECT, 'raw' );
  	if ( ! empty( $_term ) ) {
  		echo '<h1>Service Providers</h1>';
      $_posts = $this->get_services( $_service_type );
  		foreach ( $_posts as $_post ) {
        $_service = $this->get_service_details( $_post );
  			echo '<p>';
        if ( '' !== $_service->website ) {
          echo '<a href="' . $_service->website . '">' . $_service->title . '</a>';
        } else {
          echo $_service->title;
        }
  			echo '<br />' . $_service->excerpt . '</p>';
  			// echo '<pre>' . print_r( $_service, true ) . '</pre>';
  			echo '<p>';
  			foreach ( $_service->languages as $_obj ) {
  				echo $_obj->name . ' .. ';
  				// echo $_obj->slug . ' .. ';
  			}
  			foreach ( $_service->clients as $_obj ) {
  				echo $_obj->name . ' .. ';
  				// echo $_obj->slug . ' .. ';
  			}
  			echo '</p>';
  		}
  	}
  }
}
