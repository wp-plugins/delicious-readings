<?php
/*
 * Plugin Name: Delicious Readings
 * Description:  Publish a reading list using your Delicious bookmarks
 * Plugin URI: http://www.aldolat.it/wordpress/wordpress-plugins/delicious-readings/
 * Author: Aldo Latino
 * Author URI: http://www.aldolat.it/
 * Version: 2.0
 * License: GPLv3 or later
 * Text Domain: delicious-readings
 * Domain Path: /languages/
 */

/*
 * Copyright (C) 2012  Aldo Latino  (email : aldolat@gmail.com)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package DeliciousReadings
 * @version 1.1
 * @author Aldo Latino <aldolat@gmail.com>
 * @copyright Copyright (c) 2012, Aldo Latino
 * @link http://www.aldolat.it/wordpress/wordpress-plugins/delicious-readings/
 * @license http://www.gnu.org/licenses/gpl.html
 */


/**
 * Check for the cache lifetime in the database and set it to 3600 seconds minimum.
 *
 * @since 1.0
 * @param int $seconds The number of seconds of feed lifetime
 * @return int
 * @link http://codex.wordpress.org/Plugin_API/Filter_Reference/wp_feed_cache_transient_lifetime Codex Documentation
 */
function dr_cache_handler( $seconds ) {
	$options = (array) get_option( 'widget_dr-widget' );
	$seconds = isset( $options['time'] ) ? $options['time'] : 3600;
	return $seconds;
}


/**
 * The core function.
 * It retrieves the feed and display the content.
 *
 * @since 1.0
 * @param mixed $args Variables to customize the output of the function
 * @return mixed
 */
function dr_fetch_feed( $args ) {
	$defaults = array(
		'feed_url'         => '',
		'quantity'         => 5,
		'display_desc'     => false,
		'truncate'         => 0,
		'display_date'     => false,
		'date_text'        => __( 'Stored on:', 'delicious-readings' ),
		'display_tags'     => false,
		'tags_text'        => __( 'Tags:', 'delicious-readings' ),
		'display_hashtag'  => true,
		'display_arrow'    => false,
		'display_archive'  => true,
		'archive_text'     => __( 'More posts', 'delicious-readings' ),
		'display_arch_arr' => true,
		'new_tab'          => false,
		'nofollow'         => true,
	);
	$args = wp_parse_args( $args, $defaults );
	extract( $args, EXTR_SKIP );

	add_filter( 'wp_feed_cache_transient_lifetime', 'dr_cache_handler' );

	include_once( ABSPATH . WPINC . '/feed.php' );

	$rss = fetch_feed( $feed_url );

	remove_filter( 'wp_feed_cache_transient_lifetime', 'dr_cache_handler' ); ?>

	<ul class="reading-list">

	<?php if( is_wp_error( $rss ) ) { ?>
		<li class="reading-list-li">
			<?php printf( __( 'There was a problem with your feed! The error is %s', 'delicious-readings' ), '<code>' . $rss->get_error_message() . '</code>' ); ?>
		</li>
	<?php } else {
		if( $quantity > 10 ) $quantity = 10;
		$maxitems  = $rss->get_item_quantity( $quantity );
		$rss_items = $rss->get_items( 0, $maxitems );
		if( $maxitems == 0 ) { ?>
			<li class="reading-list-li">
				<?php _e( 'No items.', 'delicious-readings' ); ?>
			</li>
		<?php } else {
			foreach ( $rss_items as $item ) { ?>
				<li class="reading-list-li">

					<?php // Title
						if( $display_arrow ) $arrow        = '&nbsp;&rarr;';            else $arrow = '';
						if( isset( $new_tab ) )       $new_tab_link = ' target="_blank"';
						if( $nofollow )      $rel_txt      = 'rel="bookmark nofollow"'; else $rel_txt = 'rel="bookmark"';
					?>

					<?php $title = sprintf( __( 'Read &laquo;%s&raquo;', 'delicious-readings' ), $item->get_title() ); ?>

					<p class="reading-list-title">
						<a <?php echo $rel_txt; ?> href="<?php echo $item->get_permalink(); ?>" title="<?php echo $title; ?>"<?php echo $new_tab_link; ?>>
							<?php echo $item->get_title() . $arrow; ?>
						</a>
					</p>

					<?php // Description
					if( $display_desc ) {
						if( $item->get_description() ) {
							if( $truncate > 0 ) { ?>
								<p  class="reading-list-desc">
									<?php echo wp_trim_words( $item->get_description(), $truncate, '&hellip;' ); ?>
								</p>
							<?php } else { ?>
								<p  class="reading-list-desc"><?php echo $item->get_description(); ?></p>
							<?php }
						}
					}

					// Date
					if( $display_date ) {
						$bookmark_date = date_i18n( get_option( 'date_format' ), strtotime( $item->get_date() ), false ); ?>
						<p class="reading-list-date">
							<?php if( $date_text ) echo $date_text . ' '; ?>
							<a rel="bookmark" href="<?php echo $item->get_id(); ?>" title="<?php _e( 'Go to the bookmark stored on Delicious.', 'delicious-readings' ); ?>"<?php echo $new_tab_link; ?>>
								<?php echo $bookmark_date; ?>
							</a>
						</p>
					<?php }

					// Tag
					if( $display_tags ) {
						$tags = (array) $item->get_item_tags( '', 'category' ); ?>
						<p class="reading-list-tags">
							<?php if( $tags_text ) echo $tags_text . ' '; ?>
							<?php if( $display_hashtag ) $hashtag = '#'; ?>
							<?php foreach( $tags as $tag ) {
								$the_domain = isset( $tag['attribs']['']['domain'] ) ? $tag['attribs']['']['domain'] : '';
								$the_tag    = isset( $tag['data'] ) ? $tag['data'] : ''; ?>
								<?php echo $hashtag; ?><a rel="bookmark" href="<?php echo $the_domain . $tag['data']; ?>" title="<?php printf( __( 'Go to the tag %s su Delicious', 'delicious-readings' ), $hashtag . $the_tag ); ?>"<?php echo $new_tab_link; ?>><?php echo $the_tag; ?></a>
							<?php } ?>
						</p>
					<?php } ?>

				</li>

			<?php }
		}
	} ?>
	
	</ul>

	<?php if( $display_archive ) { ?>
		<?php if( $display_arch_arr ) $arrow = '&nbsp;&rarr;'; else $arrow = ''; ?>
		<p class="reading-list-more">
			<a href="<?php echo $rss->get_link(); ?>"<?php echo $new_tab_link; ?>>
				<?php echo $archive_text . $arrow; ?>
			</a>
		</p>
	<?php }
}


/**
 * Include the widget
 *
 * @since 1.1
 */
include_once( 'delicious-readings-widget.php' );


/**
 * Include the shortcode
 *
 * @since 2.0
 */
include_once( 'delicious-readings-shortcode.php' );


/**
 * Load the translation.
 *
 * @since 1.0
 */
function dr_load_languages() {
	load_plugin_textdomain( 'delicious-readings', false, dirname( plugin_basename( __FILE__ ) ) . '/languages');
}
add_action( 'plugins_loaded', 'dr_load_languages' );


/***********************************************************************
 *                            CODE IS POETRY
 ***********************************************************************/

 