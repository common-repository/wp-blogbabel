<?php
/**
 * Manage Admin (back-end)
 *
 * @author          =undo= <g.fazioli@saidmade.com>
 * @copyright       Copyright (C) 2010 Saidmade Srl
 *
 */
class WPBLOGBABEL_ADMIN extends WPBLOGBABEL_CLASS {

    function WPBLOGBABEL_ADMIN() {
        $this->WPBLOGBABEL_CLASS();	// super

        $this->initDefaultOption();
    }

    /**
     * Init the default plugin options and re-load from WP
     */
    function initDefaultOption() {
        $this->options = array(
                            'wp_blogbabel_version'  => $this->version,
                            'wp_blogbabel_blogslug' => ''
                            );

        add_option( $this->options_key, $this->options, $this->options_title );

        parent::getOptions();

        add_action('admin_menu', 	array( $this, 'add_menus') );

        update_option( $this->options_key, $this->options);
    }

    /**
     * ADD OPTION PAGE TO WORDPRESS ENVIRORMENT
     *
     * Add callback for adding options panel
     *
     */
    function add_menus() {
        $menus = array();

        if ( function_exists('add_options_page') ) {
            $plugin_page = add_options_page( $this->options_title, $this->options_title, 8, basename(__FILE__), array( $this, 'set_options_subpanel') );
        }

//        if (function_exists('add_object_page')) {
//            $menus['main'] = add_object_page('WP BlogBabel', 'WP BlogBabel', 8, $this->directory.'-settings' );
//        } else
//            $menus['main'] = add_menu_page('WP BlogBabel', 'WP BlogBabel', 8, $this->directory.'-settings', array(&$this,'set_options_subpanel') );
//
//        $menus['settings'] = add_submenu_page($this->directory.'-settings', __('Settings'), __('Settings'), 8, $this->directory.'-settings', array(&$this,'set_options_subpanel') );

        add_action( 'admin_head-' . $plugin_page, array( &$this, 'set_admin_head' ) );

        /**
         * Add contextual Help
         */
        if (function_exists('add_contextual_help')) {
            add_contextual_help($plugin_page,'<p><strong>'.__('Use').':</strong></p>' .
                '<pre>wp_blobabel(\'blogslug=undolog\');</pre>or<br/>' .
                '<pre>wp_blogbabel_get(\'blogslug=undolog\', \'rank\');</pre>or<br/>' .
                '<pre>wp_blogbabel_get(\'blogslug=undolog\', \'trend\');</pre>or<br/>' .
                '<pre>
* blogslug            Your BlogBabel blog slug (default \'\')
* container_before    Main tag container open (default &lt;div&gt;)
* container_after     Main tag container close (default &lt;/div&gt;)
* show_title          Show Top Header Title (default \'1\')
* show_rank           Show Rank (default \'1\')
* show_trend          Show Trend (default \'1\')
</pre>' 			
            );
        }
    }

    /**
     * Draw Options Panel
     */
    function set_options_subpanel() {
        global $wpdb, $_POST;

        $any_error = "";                                                        // any error flag

        if( isset( $_POST['command_action'] ) ) {				// have to save options
            $any_error = __('Your settings have been saved.');

            switch( $_POST['command_action'] ) {
                case "update":
                    $this->options['wp_blogbabel_blogslug'] = $_POST['wp_blogbabel_blogslug'];
                    update_option( $this->options_key, $this->options);
                    break;
            }
        }

        /**
         * Show error or OK
         */
        if( $any_error != '') echo '<div id="message" class="updated fade"><p>' . $any_error . '</p></div>';

        /**
         * INSERT OPTION
         *
         * You can include a separate file: include ('options.php');
         *
         */
        ?>

<div class="wrap">
    <div class="icon32" id="icon-options-general"><br/></div>
    <h2><?=$this->options_title?> ver. <?=$this->version?></h2>

    <div id="poststuff" class="metabox-holder has-right-sidebar">
        <div class="inner-sidebar">
            <div id="side-sortables" class="meta-box-sortabless ui-sortable" style="position: relative;">

                <div id="sm_pnres" class="postbox">
                    <h3 class="hndle"><span>Links</span></h3>
                    <div class="inside">
                       <div style="text-align:center;margin-bottom:12px"><script type="text/javascript" src="http://www.mad-ideas.it/widgets/?id=9"></script></div>
                        <ul class="list-point">
                            <li>
                                <a target="_blank" href="http://www.saidmade.com">Saidmade Srl</a>
                            </li>
                             <li>
                                 <a target="_blank" href="http://www.undolog.com">Research &amp; Development Blog</a>
                            </li>

                        </ul>
                       <div id="liquida-logo">
                           <a target="_blank" href="http://www.liquida.it"><span>Liquida</span></a>
                       </div>
                       <ul class="list-point">
                            <li>
                                <a target="_blank" href="http://it.blogbabel.com">BlogBabel</a>
                            </li>
                             <li>
                                <a target="_blank" href="http://it.blogbabel.com/blog/2007/06/22/habemus-api/">BlogBabel API References</a>
                            </li>

                        </ul>
                    </div>
                </div>

                <div id="sm_pnres" class="postbox">
                    <h3 class="hndle"><span>Donate</span></h3>
                    <div class="inside">
                        <p style="text-align:center;font-family:Tahoma;font-size:10px">Developed by <a target="_blank" href="http://www.saidmade.com"><img alt="Saidmade" align="absmiddle" src="http://labs.saidmade.com/images/sm-a-80x15.png" border="0" /></a>
                            <br/>
                            more Wordpress plugins on <a target="_blank" href="http://labs.saidmade.com">labs.saidmade.com</a> and <a target="_blank" href="http://www.undolog.com">Undolog.com</a>
                            <br/>
                        </p>
                        <div>
                            <form style="text-align:center;width:auto;margin:0 auto" action="https://www.paypal.com/cgi-bin/webscr" method="post">
                                <input type="hidden" name="cmd" value="_s-xclick">
                                <input type="hidden" name="hosted_button_id" value="3499468">
                                <input type="image" src="https://www.paypal.com/it_IT/IT/i/btn/btn_donateCC_LG.gif" name="submit" alt="PayPal - Il sistema di pagamento online più facile e sicuro!">
                                <img alt="" border="0" src="https://www.paypal.com/it_IT/i/scr/pixel.gif" width="1" height="1">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="has-sidebar sm-padded">
            <div id="post-body-content" class="has-sidebar-content">
                <div class="meta-box-sortabless">

                    <div id="sm_rebuild" class="postbox">
                        <h3 class="hndle"><span><?php echo __('Test BlogBabel Slug')?></span></h3>
                        <div class="inside">
                            <form class="form_box" name="insert_bannerize" method="post" action="">
                                <input type="hidden" name="command_action" id="command_action" value="update" />

                                <table class="form-table">
                                    <tr>
                                        <th scope="row"><label for="wp_blogbabel_blogslug"><?php echo __('BlogBabel Slug')?>:</label></th>
                                        <td><input type="text" maxlength="128" name="wp_blogbabel_blogslug" id="wp_blogbabel_blogslug" value="<?php echo htmlentities($this->options['wp_blogbabel_blogslug']) ?>" size="32" /></td>
                                    </tr>
                                </table>
                                <p class="submit"><input class="button-primary" type="submit" value="<?php echo __('Update')?>" /></p
                            </form>
                        </div>
                    </div>

                    <?php
                        $blogslug = htmlentities($this->options['wp_blogbabel_blogslug']);
                        if( $blogslug != ""): ?>

                    <div id="sm_rebuild" class="postbox">
                        <h3 class="hndle"><span><?php echo __('Sample Data Output')?></span></h3>
                        <div class="inside">

                            <?php $xml = $this->get_from_cache( $blogslug ); ?>
                            <p><strong>Name:</strong> <a target="_blank" href="<?php echo $xml->url ?>"><?php echo $xml->name ?></a></p>
                            <p><strong>FeedURL:</strong> <?php echo $xml->feedurl ?></p>
                            <p><strong>Rank:</strong> <?php echo $xml->rank ?></p>
                            <p><strong>Trend:</strong> <?php echo $xml->trend ?></p>

                        </div>
                    </div>

                    <div id="sm_rebuild" class="postbox">
                        <h3 class="hndle"><span><?php echo __('Sample Output')?></span></h3>
                        <div class="inside">

                            <?php
                                if( intval($xml->trend) > 0 )
                                    $trend_class = "up";
                                else if( intval($xml->trend) < 0 )
                                    $trend_class = "down";
                                else if ( intval($xml->trend) == 0)
                                    $trend_class = "static";
                            ?>
                            <div class="wp-blogbabel">
                                <div class="topten"><a target="_blank" href="<?php echo $this->blogbabel_sheet . $blogslug  ?>"><span>BlogBabel</span></a></div>
                                <div class="url"><a target="_blank" href="<?php echo $xml->url ?>"><span><?php echo $xml->name ?></span></a></div>
                                <div class="rank"><span class="label">Rank:</span><span class="<?php echo $trend_class ?>"><?php echo $xml->rank ?></span></div>
                                <div class="trend <?php echo $trend_class ?>"></div>
                                <div class="credits"><a target="_blank" href="http://wordpress.org/extend/plugins/wp-blogbabel/">Powered by WP BlogBabel</a></div>
                            </div>

                        </div>
                    </div>

                    <?php endif; ?>

                    
                </div>
            </div>
        </div>
    </div>
</div>

    <?php
    }



    /**
     * Hook the admin/plugin head
     */
    function set_admin_head() {
        ?>
<link rel="stylesheet" href="<?php echo $this->uri?>/css/style.css" type="text/css" media="screen, projection" />
<link rel="stylesheet" href="<?php echo $this->uri?>/css/default.css" type="text/css" media="screen, projection" />
    <?php
    }

    /**
     * Attach settings in Wordpress Plugins list
     */
    function register_plugin_settings( $pluginfile ) {
        add_action( 'plugin_action_links_'.basename( dirname( $pluginfile ) ) . '/' . basename( $pluginfile ), array( &$this, 'plugin_settings' ), 10, 4 );
    }

    function plugin_settings( $links ) {
        $settings_link = '<a href="options-general.php?page=wp-blogbabel_admin.php">' . __('Settings') . '</a>';
        array_unshift( $links, $settings_link );
        return $links;
    }

} // end of class

?>