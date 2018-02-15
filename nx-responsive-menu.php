<?php
/*
Plugin Name: Nx Responsive Menu
Plugin URI: http://templatesnext.org/
Description: Nx Responsive menu is a mobile menu plugin.
Version: 1.0.2
Author: TemplatesNext
Author URI: http://templatesnext.org/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

/**
 *
 * Enable Localization
 *
 */
load_plugin_textdomain('nxrmenu', false, basename( dirname( __FILE__ ) ) . '/lang' );

/**
 *
 * Add admin settings
 *
 */
define( 'WPR_OPTIONS_FRAMEWORK_DIRECTORY',  plugins_url( '/inc/', __FILE__ ) );
define( 'WPR_OPTIONS_FRAMEWORK_PATH',   dirname( __FILE__ ) . '/inc/' );
require_once dirname( __FILE__ ) . '/inc/options-framework.php';

// add required js/css files
add_action( 'wp_enqueue_scripts', 'nxrmenu_enqueue_scripts' );

function nxrmenu_enqueue_scripts() {
	$options = get_option('nxrmenu_options');
	wp_enqueue_style( 'nxrmenu.css' , plugins_url('css/nxrmenu.css', __FILE__) );
	wp_enqueue_style( 'nxrmenu-font' , '//fonts.googleapis.com/css?family=Open+Sans:400,300,600' );
	wp_enqueue_script('jquery.transit', plugins_url( '/js/jquery.transit.min.js', __FILE__ ), array( 'jquery' ));
	wp_enqueue_script('sidr', plugins_url( '/js/jquery.sidr.js', __FILE__ ), array( 'jquery' ));
	wp_enqueue_script('nxrmenu.js', plugins_url( '/js/nxrmenu.js', __FILE__ ), array( 'jquery' ));
	$wpr_options = array( 'zooming' => $options['zooming'],'from_width' => $options['from_width'],'swipe' => $options['swipe'] );
	wp_localize_script( 'nxrmenu.js', 'nxrmenu', $wpr_options );
}

function nxwpr_search_form() {
	$options = get_option('nxrmenu_options');
	return '<form role="search" method="get" class="wpr-search-form" action="' . site_url() . '"><label><input type="search" class="wpr-search-field" placeholder="' . $options['search_box_text'] . '" value="" name="s" title="Search for:"></label></form>';
}

add_action('wp_footer', 'nxrmenu_menu', 100);
function nxrmenu_menu() {
	$options = get_option('nxrmenu_options');
	if($options['enabled']) :
		?>
		<div id="nxrmenu_bar" class="nxrmenu_bar">
        	<div class="nxrmenu-inner">
                <div class="nxrmenu_icon">
                    <span class="nxrmenu_ic_1"></span>
                    <span class="nxrmenu_ic_2"></span>
                    <span class="nxrmenu_ic_3"></span>
                </div>
                <div class="menu_title">
                    <?php echo $options['bar_title'] ?>
                    <?php if($options['bar_logo']) echo '<img class="bar_logo" src="'.$options['bar_logo'].'"/>' ?>
                </div>
            </div>
		</div>

		<div id="nxrmenu_menu" class="nxrmenu_levels <?php echo $options['position'] ?> nxrmenu_custom_icons">
			<?php if( $options['search_box'] == 'above_menu' ) { ?> 
			<div class="wpr_search">
				<?php echo nxwpr_search_form(); ?>
			</div>
			<?php } ?>
			<ul id="nxrmenu_menu_ul">
				<?php
				/*
				if ( has_nav_menu( 'primary' ) ) {
					 wp_nav_menu( array('theme_location'=>'primary','container'=>false,'items_wrap'=>'%3$s'));
				} elseif ( has_nav_menu( 'alt-primary' ) ) {
					 wp_nav_menu( array('theme_location'=>'alt-primary','container'=>false,'items_wrap'=>'%3$s'));
				}
				*/
				wp_nav_menu( array('theme_location'=>'primary','container'=>false,'items_wrap'=>'%3$s'));
				?>
			</ul>
			<?php if( $options['search_box'] == 'below_menu' ) { ?> 
			<div class="wpr_search">
				<?php echo nxwpr_search_form(); ?>
			</div>
			<?php } ?>
		</div>
		<?php
	endif;
}


function nxrmenu_header_styles() {
	$options = get_option('nxrmenu_options');
	if($options['enabled']) :
		?>
		<style id="nxrmenu_css" type="text/css" >
			/* apply appearance settings */
			#nxrmenu_bar {
				background: <?php echo $options["bar_bgd"] ?>;
			}
			#nxrmenu_bar .menu_title, #nxrmenu_bar .nxrmenu_icon_menu {
				color: <?php echo $options["bar_color"] ?>;
			}
			#nxrmenu_menu {
				background: <?php echo $options["menu_bgd"] ?>!important;
			}
			#nxrmenu_menu.nxrmenu_levels ul li {
				border-bottom:1px solid <?php echo $options["menu_border_bottom"] ?>;
				border-top:1px solid <?php echo $options["menu_border_top"] ?>;
			}
			#nxrmenu_menu ul li a {
				color: <?php echo $options["menu_color"] ?>;
			}
			#nxrmenu_menu ul li a:hover {
				color: <?php echo $options["menu_color_hover"] ?>;
			}
			#nxrmenu_menu.nxrmenu_levels a.nxrmenu_parent_item {
				border-left:1px solid <?php echo $options["menu_border_top"] ?>;
			}
			#nxrmenu_menu .nxrmenu_icon_par {
				color: <?php echo $options["menu_color"] ?>;
			}
			#nxrmenu_menu .nxrmenu_icon_par:hover {
				color: <?php echo $options["menu_color_hover"] ?>;
			}
			#nxrmenu_menu.nxrmenu_levels ul li ul {
				border-top:1px solid <?php echo $options["menu_border_bottom"] ?>;
			}
			#nxrmenu_bar .nxrmenu_icon span {
				background: <?php echo $options["menu_icon_color"] ?>;
			}
			<?php
			//when option "hide bottom borders is on...
			if($options["menu_border_bottom_show"] === 'no') { ?>
				#nxrmenu_menu, #nxrmenu_menu ul, #nxrmenu_menu li {
					border-bottom:none!important;
				}
				#nxrmenu_menu.nxrmenu_levels > ul {
					border-bottom:1px solid <?php echo $options["menu_border_top"] ?>!important;
				}
				.nxrmenu_no_border_bottom {
					border-bottom:none!important;
				}
				#nxrmenu_menu.nxrmenu_levels ul li ul {
					border-top:none!important;
				}
			<?php } ?>

			#nxrmenu_menu.left {
				width:<?php echo $options["how_wide"] ?>%;
				left: -<?php echo $options["how_wide"] ?>%;
			    right: auto;
			}
			#nxrmenu_menu.right {
				width:<?php echo $options["how_wide"] ?>%;
			    right: -<?php echo $options["how_wide"] ?>%;
			    left: auto;
			}


			<?php if( isset( $options["nesting_icon"] ) ) : ?>
				#nxrmenu_menu .nxrmenu_icon:before {
					font-family: 'fontawesome'!important;
				}
			<?php endif; ?>

			<?php if($options["menu_symbol_pos"] == 'right') : ?>
				#nxrmenu_bar .nxrmenu_icon {
					float: <?php echo $options["menu_symbol_pos"] ?>!important;
					margin-right:0px!important;
				}
				#nxrmenu_bar .bar_logo {
					pading-left: 0px;
				}
			<?php endif; ?>
			/* show the bar and hide othere navigation elements */
			@media only screen and (max-width: <?php echo $options["from_width"] ?>px) {
				html { padding-top: 42px!important; }
				#nxrmenu_bar { display: block!important; }
				div#wpadminbar { position: fixed; }
				<?php
				if( $options['hide'] != '' ) {
					echo $options['hide'];
					echo ' { display:none!important; }';
				}
				?>
			}
		</style>
		<?php
	endif;
}
add_action('wp_head', 'nxrmenu_header_styles');