<?php

// Defines
define( 'FL_CHILD_THEME_DIR', get_stylesheet_directory() );
define( 'FL_CHILD_THEME_URL', get_stylesheet_directory_uri() );

// Classes
require_once 'classes/class-fl-child-theme.php';

// Actions
add_action( 'wp_enqueue_scripts', 'FLChildTheme::enqueue_scripts', 1000 );

add_action( 'init', 'customize_font_list' );
function customize_font_list(){
 
    $custom_fonts = array(
        'overpass' => array(
            'fallback' => 'Arial, sans-serif',
            'weights' => array(
            	'100',
            	'200',
            	'300',
                '400',
                '500',
                '600',
                '700',
                '800',
                '900'
            )
        )
    );
 
    foreach($custom_fonts as $name => $settings){
        // Add to Theme Customizer
        if(class_exists('FLFontFamilies') && isset(FLFontFamilies::$system)){
            FLFontFamilies::$system[$name] = $settings;
        }
 
        // Add to Page Builder
        if(class_exists('FLBuilderFontFamilies') && isset(FLBuilderFontFamilies::$system)){
            FLBuilderFontFamilies::$system[$name] = $settings;
        }
    }
}



/*function splash_add_fullpage_scripts() {
    if(is_front_page()) {
        wp_enqueue_script( 'scrollify_js', get_stylesheet_directory_uri() . '/js/jquery.scrollify.min.js', array( 'jquery' ), '1.0', true );
        wp_enqueue_script( 'scrollify_init', get_stylesheet_directory_uri() . '/js/jquery.scrollify.init.js', array( 'scrollify_js' ), '1.0', true );
    }
}

add_action('wp_enqueue_scripts', 'splash_add_fullpage_scripts'); */


function splash_menu_classes( $classes, $item, $args ) {
    //if( 'header' !== $args->theme_location )
        //return $classes;

    if( ( is_singular( 'post' ) || is_category() || is_tag() ) && 'blog.' == $item->title )
        $classes[] = 'current-menu-item';
        /*
    if( ( is_singular( 'code' ) || is_tax( 'code-tag' ) ) && 'Code' == $item->title )
        $classes[] = 'current-menu-item';*/
        
    if( is_singular( 'portfolio' ) && 'portfolio.' == $item->title ) {
        $classes[] = 'current-menu-item';
    }
        if( is_singular( 'post' ) && 'blog.' == $item->title ) {
        $classes[] = 'current-menu-item';
    }

        
    return array_unique( $classes );
}
add_filter( 'nav_menu_css_class', 'splash_menu_classes', 10, 3 );



function filter_media_comment_status( $open, $post_id ) {
    $post = get_post( $post_id );
    if( $post->post_type == 'attachment' ) {
        return false;
    }
    return $open;
}
add_filter( 'comments_open', 'filter_media_comment_status', 10 , 2 );

