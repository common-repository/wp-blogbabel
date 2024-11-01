<?php
/**
 * Classe principale per la sezione front-end
 *
 * @author          =undo= <g.fazioli@saidmade.com>
 * @copyright       Copyright (C) 2010 Saidmade Srl
 *
 */

class WPBLOGBABEL_CLIENT extends WPBLOGBABEL_CLASS {

    function WPBLOGBABEL_CLIENT() {
        $this->WPBLOGBABEL_CLASS();							// super

        parent::getOptions();								// retrive options from database

        add_action('wp_head', array( &$this, 'addStyleToHead' ) );

    }

    function addStyleToHead() { ?>
<link rel="stylesheet" href="<?php echo $this->uri?>/css/default.css" type="text/css" media="screen, projection" />
<?php
    }

} // end of class

?>