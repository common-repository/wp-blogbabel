<?php
/**
 * Wrap standard function
 * 
 * @return 
 * @param object $args[optional]
 */
function wp_blogbabel( $args = '' ) {
    global $wp_blogbabel_client;
    $wp_blogbabel_client->showBlogBabel( $args );
}

/**
 * Get XML field from param
 *
 * @global  class     $wp_blogbabel_client    Client Class pointer
 * @param   string    $blogslug               Blog Slug
 * @param   string    $arg                    Which param: "rank", "trend"
 * @return  string                            Value
 */
function wp_blogbabel_get( $blogslug, $arg ) {
    global $wp_blogbabel_client;
    switch( strtolower( $arg ) ) {
        case "rank":
            return $wp_blogbabel_client->getRank($blogslug);
            break;
        case "trend":
            return $wp_blogbabel_client->getTrend($blogslug);
            break;
        default:
            return null;
            break;
    }
    return null;
}

?>