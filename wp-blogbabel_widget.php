<?php
/**
 * Widget support
 *
 * @author          =undo= <g.fazioli@saidmade.com>
 * @copyright       Copyright (C) 2010 Saidmade Srl
 */
class WP_BLOGBABEL_WIDGET extends WP_Widget {

    var $path                   = "";
    var $content_path           = "";
    var $blogbabel_api          = "http://it.blogbabel.com/api/v1/blog/";
    var $blogbabel_sheet        = "http://it.blogbabel.com/classifica-blog/blog/";

    function WP_BLOGBABEL_WIDGET() {
        global $wpdb;

        $this->path             = dirname(__FILE__);
        $this->content_path     = $this->path . "/../..";

        $widget_ops = array('classname' => 'widget_wp_blogbabel', 'description' => 'Show BlogBabel rank and trend for a site/blog');
        $control_ops = array('width' => 400, 'height' => 350);
        $this->WP_Widget('wp_blogbabel', 'WP BlogBabel', $widget_ops, $control_ops);
    }

    function widget( $args, $instance ) {
        global $wpdb;

        extract($instance);

        echo $before_widget;
        echo $container_before;

        $xml = $this->get_from_cache( $blogslug );

        if( intval($xml->trend) > 0 )
            $trend_class = "up";
        else if( intval($xml->trend) < 0 )
            $trend_class = "down";
        else if ( intval($xml->trend) == 0)
            $trend_class = "static";
        ?>
        <div class="wp-blogbabel">
            <?php if( $show_title == "1" ): ?>
            <div class="topten"><a class="<?php echo $aclass ?>" target="_blank" href="<?php echo $this->blogbabel_sheet . $blogslug  ?>"><span>BlogBabel</span></a></div>
            <div class="url"><a class="<?php echo $aclass ?>" target="_blank" href="<?php echo $xml->url ?>"><span><?php echo $xml->name ?></span></a></div>
            <?php endif; ?>

            <?php if( $show_rank == "1" ): ?>
            <div class="rank"><span class="label">Rank:</span><span class="<?php echo $trend_class ?>"><?php echo $xml->rank ?></span></div>
            <?php endif; ?>

            <?php if( $show_trend == "1" ): ?>
            <div class="trend <?php echo $trend_class ?>"></div>
            <?php endif; ?>

            <div class="credits"><a class="<?php echo $aclass ?>" target="_blank" href="http://wordpress.org/extend/plugins/wp-blogbabel/">Powered by WP BlogBabel</a></div>
        </div>
        <?php

        echo $container_after;
        echo $after_widget;
    }

    /**
     * Aggiorna i dati del widget
     *
     * @param <string> $new_instance
     * @param <string> $old_instance
     * @return <type>
     */
    function update( $new_instance, $old_instance ) {
        $instance                       = $old_instance;
        $instance['title']              = strip_tags($new_instance['title']);
        $instance['blogslug']           = strip_tags($new_instance['blogslug']);

        $instance['container_before'] 	= ($new_instance['container_before']);
        $instance['container_after'] 	= ($new_instance['container_after']);
        $instance['show_title']         = ($new_instance['show_title']);
        $instance['show_rank']          = ($new_instance['show_rank']);
        $instance['show_trend']         = ($new_instance['show_trend']);

        $instance['aclass']             = ($new_instance['aclass']);

        return $instance;
    }

    function form( $instance ) {
        $instance	= wp_parse_args( (array) $instance,
            array( 'title'      => '',
            'blogslug'          => '',
            'container_before'  => '<div>',
            'container_after'	=> '</div>',
            'show_title'        => '0',
            'show_rank'         => '0',
            'show_trend'        => '0',
            'aclass'            => ''
            )
        );
        $title                  = strip_tags($instance['title']);
        $blogslug               = strip_tags($instance['blogslug']);
        $show_title             = ($instance['show_title']);
        $show_rank              = ($instance['show_rank']);
        $show_trend             = ($instance['show_trend']);

        $aclass                 = strip_tags($instance['aclass']);

        $container_before	= ($instance['container_before']);
        $container_after	= ($instance['container_after']);

        ?>
<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>

<p><label for="<?php echo $this->get_field_id('blogslug'); ?>"><?php _e('BlogSlug:'); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id('blogslug'); ?>" name="<?php echo $this->get_field_name('blogslug'); ?>" type="text" value="<?php echo esc_attr($blogslug); ?>" /></p>

<p><label for="<?php echo $this->get_field_id('show_title'); ?>"><?php _e('Show Title:'); ?></label>
    <input rel="<?php echo $show_title ?>" <?php echo ($show_title != '0') ? 'checked="chekced"' : '' ?> value="1" type="checkbox" name="<?php echo $this->get_field_name('show_title'); ?>" id="<?php echo $this->get_field_id('show_title'); ?>" /></p>

<p><label for="<?php echo $this->get_field_id('show_rank'); ?>"><?php _e('Show Rank:'); ?></label>
    <input <?php echo ($show_rank != '0') ? 'checked="chekced"' : '' ?> value="1" type="checkbox" name="<?php echo $this->get_field_name('show_rank'); ?>" id="<?php echo $this->get_field_id('show_rank'); ?>" /></p>

<p><label for="<?php echo $this->get_field_id('show_trend'); ?>"><?php _e('Show Trend:'); ?></label>
    <input <?php echo ($show_trend != '0') ? 'checked="chekced"' : '' ?> value="1" type="checkbox" name="<?php echo $this->get_field_name('show_trend'); ?>" id="<?php echo $this->get_field_id('show_trend'); ?>" /></p>


<p><strong>HTML Markup:</strong></p>

<p><label for="<?php echo $this->get_field_id('aclass'); ?>"><?php _e('A class:'); ?></label>
    <input size="8" type="text" value="<?php echo $aclass ?>" name="<?php echo $this->get_field_name('aclass'); ?>" id="<?php echo $this->get_field_id('aclass'); ?>" /></p>


<p><label for="<?php echo $this->get_field_id('container_before'); ?>"><?php _e('container_before:'); ?></label>
    <input size="8" type="text" value="<?php echo $container_before ?>" name="<?php echo $this->get_field_name('container_before'); ?>" id="<?php echo $this->get_field_id('container_before'); ?>" /></p>

<p><label for="<?php echo $this->get_field_id('container_after'); ?>"><?php _e('container_after:'); ?></label>
    <input size="8" type="text" value="<?php echo $container_after ?>" name="<?php echo $this->get_field_name('container_after'); ?>" id="<?php echo $this->get_field_id('container_after'); ?>" /></p>

    <?php
    }

    /**
     * Questo metodo è identico a quello presente nella Classe principale
     * vedi wp-blogbabel_class.php per dettagli
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
}

?>