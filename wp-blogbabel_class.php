<?php
/**
 * Classe principale dalla quale derivano le sottoclassi
 *
 * @author          =undo= <g.fazioli@saidmade.com>
 * @copyright       Copyright (C) 2010 Saidmade Srl
 *
 */

class WPBLOGBABEL_CLASS {

    var $release                        = 0;
    var $minor                          = 1;
    var $revision                       = 4;
    var $version 						= "";               // plugin version
    var $plugin_name 					= "WP BlogBabel";	// plugin name
    var $options_key 					= "wp-blogbabel";	// options key to store in database
    var $options_title					= "WP BlogBabel";	// label for "setting" in WP

    var $blogbabel_api                  = "http://it.blogbabel.com/api/v1/blog/";
    var $blogbabel_sheet                = "http://it.blogbabel.com/classifica-blog/blog/";
    /**
     * Usefull vars
     * @internal
     */
    var $content_url					= "";
    var $plugin_url						= "";
    var $ajax_url						= "";

    var $path 							= "";
    var $content_path                   = "";
    var $file 							= "";
    var $directory						= "";
    var $uri 							= "";
    var $siteurl 						= "";
    var $wpadminurl 					= "";

    var $options						= array();

    function WPBLOGBABEL_CLASS() {
        global $wpdb;

        $this->version = $this->release . "." . $this->minor . "." . $this->revision;

        $this->path 					= dirname(__FILE__);
        $this->file 					= basename(__FILE__);
        $this->directory 				= basename($this->path);
        $this->uri                      = WP_PLUGIN_URL . "/" . $this->directory;
        $this->siteurl                  = get_bloginfo('url');
        $this->wpadminurl				= admin_url();

        $this->content_url 				= get_option('siteurl') . '/wp-content';
        $this->content_path             = $this->path . "/../..";
        $this->plugin_url 				= $this->content_url . '/plugins/' . plugin_basename( dirname(__FILE__) ) . '/';
        $this->ajax_url					= $this->plugin_url . "ajax.php";
    }

    /**
     * Get option from database
     */
    function getOptions() {
        $this->options 					= get_option( $this->options_key );
    }

    /**
     * Controlla la presenza di un file in cache partendo dallo blogslug di blogbabel
     * Se il file viene trovato nella cache esegue un controllo sulla data del file e
     * legge o aggiorna se sono passate 24 ore.
     * Se il file nella cache non viene trovato viene creato. La cartella "cache" è
     * creata sotto la cartella wp-content, essendo questa in 777
     *
     * @param string $blogslug
     * @return string Dati XML
     *
     */
    function get_from_cache( $blogslug ) {
        $target = $this->content_path . "/cache";
        // se la cartella "cache" non esiste la crea
        if( !file_exists( $target ))
            if(!@mkdir($target, 0777) ) {
                die(_("Can't create cache folder"));
            }
        else {
            $stat = @stat( dirname( $target ) );
            $dir_perms = $stat['mode'] & 0007777;  // Get the permission bits.
            chmod( $target, $dir_perms );
        }

        // controlla la presenza del file di cache
        $filename = $this->content_path . "/cache/" . md5( $blogslug );
        if( !file_exists( $filename)) {
            $handle = fopen( $filename, "w+");
            $xml = simplexml_load_file( $this->blogbabel_api . $blogslug );
            fwrite($handle, $xml->asXML() );
            fclose($handle);
            return $xml;
        } else {
            $now = intval( date('Ymd') );
            $fd  = intval( date('Ymd', filemtime( $filename )) );

            if( $now - $fd > 0) {
                $handle = fopen( $filename, "w+");
                $xml = simplexml_load_file( $this->blogbabel_api . $blogslug );
                fwrite($handle, $xml->asXML());
                fclose($handle);
                return $xml;
            } else {
                $xml = simplexml_load_file( $filename );
                return $xml;
            }
        }
    }

    /**
     * La funzione di output la metto qui in quanto viene
     * utilizzata sia dal backend, sia dal frontend, sia dal widget
     *
     * @param object $args
     *
     * blogslug             Slug of Site/Blog like present in BlogBabel (default '')
     * container_before		Main tag container open (default <div>)
     * container_after		Main tag container close (default </div>)
     * show_title               1 for show title in output (default '1')
     * show_rank                1 for show rank in output (default '1')
     * show_trend               1 for show trend in output (default '1')
     *
     * @return string XHTML format
     *
     */
    function showBlogBabel($args = '') {
        $default = array(
            'blogslug' 			=> '',
            'container_before'		=> '<div>',
            'container_after'		=> '</div>',
            'show_title'                => '1',
            'show_rank'                 => '1',
            'show_trend'                => '1'
        );

        $new_args = wp_parse_args( $args, $default );

        $xml = $this->get_from_cache( $new_args['blogslug'] );

        if( intval($xml->trend) > 0 )
            $trend_class = "up";
        else if( intval($xml->trend) < 0 )
            $trend_class = "down";
        else if ( intval($xml->trend) == 0)
            $trend_class = "static";
        ?>
        <div class="wp-blogbabel">
            <?php if( $new_args['show_title'] == "1" ): ?>
            <div class="topten"><a target="_blank" href="<?php echo $this->blogbabel_sheet . $blogslug  ?>"><span>BlogBabel</span></a></div>
            <div class="url"><a target="_blank" href="<?php echo $xml->url ?>"><span><?php echo $xml->name ?></span></a></div>
            <?php endif; ?>

            <?php if( $new_args['show_rank'] == "1" ): ?>
            <div class="rank"><span class="label">Rank:</span><span class="<?php echo $trend_class ?>"><?php echo $xml->rank ?></span></div>
            <?php endif; ?>

            <?php if( $new_args['show_trend'] == "1" ): ?>
            <div class="trend <?php echo $trend_class ?>"></div>
            <?php endif; ?>

            <div class="credits"><a target="_blank" href="http://wordpress.org/extend/plugins/wp-blogbabel/">Powered by WP BlogBabel</a></div>
        </div>
        <?php
    }

    /**
     * Restituisce l'url del blog in base al blogslug di blogbabel
     *
     * @param string $blogslug
     * @return string
     */
    function getFeedURL( $blogslug ) {
         $xml = $this->get_from_cache( $blogslug );
         return $xml->feedurl;
    }

    /**
     * Restituisce il nome del blog a partire dallo blogslug di blogbabel
     *
     * @param string $blogslug
     * @return string
     */
    function getName( $blogslug ) {
         $xml = $this->get_from_cache( $blogslug );
         return $xml->name;
    }

    function getURL( $blogslug ) {
         $xml = $this->get_from_cache( $blogslug );
         return $xml->url;
    }

    function getRank( $blogslug ) {
         $xml = $this->get_from_cache( $blogslug );
         return $xml->rank;
    }

    function getTrend( $blogslug ) {
         $xml = $this->get_from_cache( $blogslug );
         return $xml->trend;
    }

    /**
     * Check the Wordpress relase for more setting
     */
    function checkWordpressRelease() {
        global $wp_version;
        if ( strpos($wp_version, '2.7') !== false || strpos($wp_version, '2.8') !== false  ) {}
    }
} // end of class

/**
 * Avoid widget support
 *
 * @since 0.1.3
 */
if(class_exists("WP_Widget")) {
    require_once('wp-blogbabel_widget.php');
    add_action('widgets_init', create_function('', 'return register_widget("WP_BLOGBABEL_WIDGET");'));
}
?>