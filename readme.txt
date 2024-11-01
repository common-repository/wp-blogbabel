 === WP BlogBabel ===
Contributors: Giovambattista Fazioli
Donate link: http://labs.saidmade.com
Tags: Blogbabel, Rank, Trend, Stats
Requires at least: 2.8
Tested up to: 2.9.1
Stable tag: 0.1.4

WP BlogBabel show your (or others) site/blog rank and trend from BlogBabel stats

== Description ==

WP BlogBabel show your (or others) site/blog rank and trend from BlogBabel stats. In your template insert: `<?php wp_blogbabel('your-blog-slug'); ?>` or set it like Widget

**FEATURES**

* Show Rank and Trend via BlogBabel API
* Use internal cache to avoid overload BlogBabel Server (refresh ever 24h)
* Show multiple site rank (see Widget use for example)
* No image, no Flash, FULL XHTML and CSS
* Get single Rank or Trend from one or more site slug
* Fully customizable output
* Contextual Help


**HOW TO**

In PHP inline function mode you can set some args. This is the XHTML/CSS layout

`<?php wp_blogbabel('blogslug=undolog'); ?>`

**args**

`
* blogslug            Your BlogBabel blog slug (default '')
* container_before    Main tag container open (default <div>)
* container_after     Main tag container close (default </div>)
* show_title          Show Top Header Title (default '1')
* show_rank           Show Rank (default '1')
* show_trend          Show Trend (default '1')
`

or single rank value

`<?php wp_blogbabel_get('blogslug=undolog', 'rank'); ?>`

or single trend value

`<?php wp_blogbabel_get('blogslug=undolog', 'trend'); ?>`


= Related Links =

* [Saidmade](http://www.saidmade.com/ "Creazione siti Web")
* [Undolog](http://www.undolog.com/ "Author's Web")
* [Labs Saidmade](http://labs.saidmade.com/ "More Wordpress Plugin info")

For more information on the roadmap for future improvements please e-mail: g.fazioli@saidmade.com


== Screenshots ==

1. Options
2. Widget support


== Changelog ==

= 0.1.4 =
* Fix bug on Widget output in cache load

= 0.1.3 =
* Minor revision

= 0.1.2 =
* Change cache folder position in: /wp-content


= 0.1.1 =
* Rev readme.txt

= 0.1 =
* First beta release


== Upgrade Notice ==

= 0.1.4 =
Fix cache load on Widget. Download immediately.

= 0.1.2 =
Fix cache folder problem. Download immediately.

= 0.1.1 =
Revision readme.txt

= 0.1 =
Basic implement Blogbabel API


== Installation ==

1. Upload the entire content of plugin archive to your `/wp-content/plugins/` directory, 
   so that everything will remain in a `/wp-content/plugins/wp-blogbabel/` folder
2. Open the plugin configuration page, which is located under `Options -> wp-blogbabel`
3. Activate the plugin through the 'Plugins' menu in WordPress (deactivate and reactivate if you're upgrading).
4. Insert in you template php `<?php wp_blogbabel('blogslug=[your-blogbabel-slug]'); ?>` function
5. Done. Enjoy.

== Thanks ==

* [Liquida](http://www.liquida.it/) for resume BlogBabel


== Frequently Asked Questions == 

= Can I customize output? =

Yes, you can overwrite default css style. The XHTML/CSS layout is:


`
<div class="wp-blogbabel">
    <!-- 'labssaidmade' is a blogbabel slug example -->
    <div class="topten">
        <a href="http://it.blogbabel.com/classifica-blog/blog/labssaidmade" target="_blank"><span>BlogBabel</span></a>
    </div>
    <div class="url">
        <a href="http://labs.saidmade.com/" target="_blank"><span>labs.saidmade</span></a>
    </div>
    <div class="rank">
        <span class="label">Rank:</span><span class="down">3603</span>
    </div>
   <!--
       class 'down' when trend is down, ese 'up' or 'static'
       see below for css define
   -->
    <div class="trend down"/>
        <div class="credits">
             <a href="http://wordpress.org/extend/plugins/wp-blogbabel/" target="_blank">Powered by WP BlogBabel</a>
        </div>
    </div>
`

In the Plugin directory CSS you can see a default standard style-sheet:

`
/*
 * Main container
 */
div.wp-blogbabel {
    display:block;
    width:100px;
    height:100px;
    background:#eee;
    border:1px solid #aaa;
    -moz-border-radius:8px;
    -webkit-border-radius:8px;
    font-family:Arial;
    line-height:24px;
}

/*
 * Use this setting for all links
 */
 div.wp-blogbabel a {
     text-decoration:none;
 }

/*
 * TopTen is a link to BlogBabel sheet
 */
div.wp-blogbabel div.topten {
    text-align:center;
}
div.wp-blogbabel div.topten a {
    background:#ccc;
    -moz-border-radius:12px;
    -webkit-border-radius:12px;
    font-size:10px;
    margin:4px 4px 0;
    display:block;
    border:1px solid #aaa;
    line-height:14px;
    height:16px;
    color:#444;
    text-shadow:1px 1px 1px #fff;
}
div.wp-blogbabel div.topten a span {}


/*
 * Site URL link
 */
div.wp-blogbabel div.url {
    text-align:center;
}
div.wp-blogbabel div.url a {}
div.wp-blogbabel div.url a span {}

/*
 * Rank
 */
div.wp-blogbabel div.rank {
    text-align:center;
    font-size:38px;
    letter-spacing:-1px;
    font-family:Arial;
}
div.wp-blogbabel div.rank span.label {
    display:none;
}

/*
 * Trend for Rank
 */
 div.wp-blogbabel div.rank span.up {
     color:#0f0;
 }
 div.wp-blogbabel div.rank span.down {
     color:#c00;
 }
 div.wp-blogbabel div.rank span.static {
     color:#444;
 }

/*
 * Trend
 */
div.wp-blogbabel div.trend {}
div.wp-blogbabel div.up {}
div.wp-blogbabel div.down {}
div.wp-blogbabel div.static {}

/*
 * Footer credits
 */
div.wp-blogbabel div.credits {
    text-align:center;
}
div.wp-blogbabel div.credits a {
    font-size:9px;
    letter-spacing:-0.05em;

}`