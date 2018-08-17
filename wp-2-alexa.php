<?php
/**
 * Plugin Name:     WP2Alexa
 * Plugin URI:      PLUGIN SITE HERE
 * Description:     This plugin creates customizable, Amazon Alexa-ready feeds for WordPress posts.
 * Author:          WP2Selceeus
 * Author URI:      YOUR SITE HERE
 * Text Domain:     wp2alexa
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         WP2Alexa
 */

// Your code starts here.

// Build Alexa Flash Brief Feed

add_action( 'save_post', 'build_alexa_feed' );
add_action( 'publish_future_post', 'build_alexa_feed' );

function build_alexa_feed( $post_id ) {

    $category = get_the_category( $post_id );
    $categories_included = "category name";
    $revision = wp_is_post_revision( $post_id );

    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
            return;
  
    foreach( $category as $cat ) {
  
      if ( $cat ) {
        
        if ( $cat->slug == $categories_included ) {
  
          if( is_singular( $post_id ) || $revision = 1 ) {
  
            $feed = file_get_contents( 'https://(url)/wp-json/wp/v2/posts' );
            $json = json_decode($feed, true);
  
            $response = array();
            $posts = array();
            $flash_brief_content = $json ;
  
            foreach ( $flash_brief_content as $flash_brief_content_item ) {
  
                  $posts[] = array( 'uid' => $flash_brief_content_item['date'],
                                    'updateDate' => $flash_brief_content_item['modified'] . '.0Z', 
                                    'titleText' => $flash_brief_content_item['title'],
                                    'mainText' => $flash_brief_content_item['content'],
                                    'redirectionUrl' => $flash_brief_content_item['link']
                                  );
  
                }
  
                $fp = fopen( 'path to directory', 'w' );
                fwrite($fp, json_encode( $posts, JSON_UNESCAPED_UNICODE ));
                fclose($fp);
          }
      
        }
  
      }
  
    }
  
  }
