<?php
namespace AMPforWP\AMPVendor;
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
/**
 * Class AMP_Playlist_Embed_Handler
 *
 * @package AMP
 * @since 0.7
 */
 /**
 * Class AMP_Playlist_Embed_Handler
 *
 * Creates AMP-compatible markup for the WordPress 'playlist' shortcode.
 *
 * @package AMP
 */
class AMP_Playlist_Embed_Handler extends AMP_Base_Embed_Handler {
 	/**
	 * The tag of the shortcode.
	 *
	 * @var string
	 */
	const SHORTCODE = 'playlist';
 	/**
	 * The default height of the thumbnail image for 'audio' playlist tracks.
	 *
	 * @var int
	 */
	const DEFAULT_THUMB_HEIGHT = 64;
 	/**
	 * The default width of the thumbnail image for 'audio' playlist tracks.
	 *
	 * @var int
	 */
	const DEFAULT_THUMB_WIDTH = 48;
 	/**
	 * The max width of the audio thumbnail image.
	 *
	 * This corresponds to the max-width in wp-mediaelement.css:
	 * .wp-playlist .wp-playlist-current-item img
	 *
	 * @var int
	 */
	const THUMB_MAX_WIDTH = 60;
 	/**
	 * The height of the carousel.
	 *
	 * @var int
	 */
	const CAROUSEL_HEIGHT = 250;
 	/**
	 * The pattern to get the playlist data.
	 *
	 * @var string
	 */
	const PLAYLIST_REGEX = ':<script type="application/json" class="wp-playlist-script">(.+?)</script>:s';
 	/**
	 * The ID of individual playlist.
	 *
	 * @var int
	 */
	public static $playlist_id = 0;
 	/**
	 * The removed shortcode callback.
	 *
	 * @var callable
	 */
	public $removed_shortcode_callback;
 	/**
	 * Registers the playlist shortcode.
	 *
	 * @global array $shortcode_tags
	 * @return void
	 */
	public function register_embed() {
		global $shortcode_tags;
		if ( shortcode_exists( self::SHORTCODE ) ) {
			$this->removed_shortcode_callback = $shortcode_tags[ self::SHORTCODE ];
		}
		add_shortcode( self::SHORTCODE, array( $this, 'shortcode' ) );
		remove_action( 'wp_playlist_scripts', 'wp_playlist_scripts' );
	}
 	/**
	 * Unregisters the playlist shortcode.
	 *
	 * @return void
	 */
	public function unregister_embed() {
		if ( $this->removed_shortcode_callback ) {
			add_shortcode( self::SHORTCODE, $this->removed_shortcode_callback );
			$this->removed_shortcode_callback = null;
		}
		add_action( 'wp_playlist_scripts', 'wp_playlist_scripts' );
	}
	/**	
	 * Enqueues the playlist styling.	
	 *	
	 * @return void	
	 */	
 	public function ampforwp_playlist_styles() {	
 		add_action('amp_post_template_css', array( &$this,'ampforwp_playlist') ); 
	}	
 	/**
	 * Gets AMP-compliant markup for the playlist shortcode.
	 *
	 * Uses the JSON that wp_playlist_shortcode() produces.
	 * Gets the markup, based on the type of playlist.
	 *
	 * @param array $attr The playlist attributes.
	 * @return string Playlist shortcode markup.
	 */
	public function shortcode( $attr ) {
		$data = $this->get_data( $attr );
		if ( isset( $data['type'] ) && ( 'audio' === $data['type'] ) ) {
			return $this->audio_playlist( $data );
		} elseif ( isset( $data['type'] ) && ( 'video' === $data['type'] ) ) {
			return $this->video_playlist( $data );
		}
	}
 	/**
	 * Gets an AMP-compliant audio playlist.
	 *
	 * @param array $data Data.
	 * @return string Playlist shortcode markup, or an empty string.
	 */
	public function audio_playlist( $data ) {
		if ( ! isset( $data['tracks'] ) ) {
			return '';
		}
		self::$playlist_id++;
		$container_id = 'wpPlaylist' . self::$playlist_id . 'Carousel';
		$state_id     = 'wpPlaylist' . self::$playlist_id;
		$amp_state    = array(
			'selectedIndex' => 0,
		);
		$this->ampforwp_playlist_styles();
		ob_start();
		?>
		<div class="wp-playlist wp-audio-playlist wp-playlist-light">
			<amp-state id="<?php echo esc_attr( $state_id ); ?>">
				<script type="application/json"><?php echo wp_json_encode( $amp_state ); ?></script>
			</amp-state>
			<amp-carousel id="<?php echo esc_attr( $container_id ); ?>" [slide]="<?php echo esc_attr( $state_id . '.selectedIndex' ); ?>" height="<?php echo esc_attr( self::CAROUSEL_HEIGHT ); ?>" width="auto" type="slides">
				<?php
				foreach ( $data['tracks'] as $track ) :
					$title      = $this->get_title( $track );
					$image_url  = isset( $track['thumb']['src'] ) ? $track['thumb']['src'] : '';
					$dimensions = $this->get_thumb_dimensions( $track );
					?>
					<div>
						<div class="wp-playlist-current-item">
							<?php if ( $image_url ) : ?>
								<amp-img src="<?php echo esc_url( $image_url ); ?>" height="<?php echo esc_attr( $dimensions['height'] ); ?>" width="<?php echo esc_attr( $dimensions['width'] ); ?>"></amp-img>
							<?php endif; ?>
							<div class="wp-playlist-caption">
								<span class="wp-playlist-item-meta wp-playlist-item-title"><?php echo esc_html( $title ); ?></span>
							</div>
						</div><?php 
						$track_src = preg_replace('#^https?:#', '', $track['src']);
						  ?>
						<amp-audio width="600" height="50" src="<?php echo esc_url( $track_src ); ?>"></amp-audio>
					</div>
				<?php endforeach; ?>
			</amp-carousel>
			<?php $this->print_tracks( $state_id, $data['tracks'] ); ?>
		</div>
		<?php
		return ob_get_clean();
	}
 	/**
	 * Gets an AMP-compliant video playlist.
	 *
	 * This uses similar markup to the native playlist shortcode output.
	 * So the styles from wp-mediaelement.min.css will apply to it.
	 *
	 * @global int $content_width
	 * @param array $data Data.
	 * @return string $video_playlist Markup for the video playlist.
	 */
	public function video_playlist( $data ) {
		global $content_width;
		if ( ! isset( $data['tracks'], $data['tracks'][0]['src'] ) ) {
			return '';
		}
		self::$playlist_id++;
		$state_id  = 'wpPlaylist' . self::$playlist_id;
		$amp_state = array(
			'selectedIndex' => 0,
		);
		foreach ( $data['tracks'] as $index => $track ) {
			$amp_state[ $index ] = array(
				'videoUrl' => $track['src'],
				'thumb'    => isset( $track['thumb']['src'] ) ? $track['thumb']['src'] : '',
			);
		}
 		$dimensions = isset( $data['tracks'][0]['dimensions']['resized'] ) ? $data['tracks'][0]['dimensions']['resized'] : null;
		$width      = isset( $dimensions['width'] ) ? $dimensions['width'] : $content_width;
		$height     = isset( $dimensions['height'] ) ? $dimensions['height'] : null;
		$src_bound  = sprintf( '%s[%s.selectedIndex].videoUrl', $state_id, $state_id );
		$this->ampforwp_playlist_styles();
		ob_start();
		?>
		<div class="wp-playlist wp-video-playlist wp-playlist-light">
			<amp-state id="<?php echo esc_attr( $state_id ); ?>">
				<script type="application/json"><?php echo wp_json_encode( $amp_state ); // WPCS: XSS ok. ?></script>
			</amp-state>
			<amp-video id="amp-video" src="<?php echo esc_url( $data['tracks'][0]['src'] ); ?>" [src]="<?php echo esc_attr( $src_bound ); ?>" width="<?php echo esc_attr( $width ); ?>" height="<?php echo esc_attr( $height ); ?>" controls></amp-video>
			<?php $this->print_tracks( $state_id, $data['tracks'] ); ?>
		</div>
		<?php
		return ob_get_clean(); // WPCS: XSS ok.
	}
 	/**
	 * Gets the thumbnail image dimensions, including height and width.
	 *
	 * If the width is higher than the maximum width,
	 * reduces it to the maximum width.
	 * And it proportionally reduces the height.
	 *
	 * @param array $track The data for the track.
	 * @return array {
	 *     Dimensions.
	 *
	 *     @type int $height Image height.
	 *     @type int $width  Image width.
	 * }
	 */
	public function get_thumb_dimensions( $track ) {
		$original_height = isset( $track['thumb']['height'] ) ? intval( $track['thumb']['height'] ) : self::DEFAULT_THUMB_HEIGHT;
		$original_width  = isset( $track['thumb']['width'] ) ? intval( $track['thumb']['width'] ) : self::DEFAULT_THUMB_WIDTH;
		if ( $original_width > self::THUMB_MAX_WIDTH ) {
			$ratio  = $original_width / self::THUMB_MAX_WIDTH;
			$height = intval( $original_height / $ratio );
		} else {
			$height = $original_height;
		}
		$width = min( self::THUMB_MAX_WIDTH, $original_width );
		return compact( 'height', 'width' );
	}
 	/**
	 * Outputs the playlist tracks, based on the type of playlist.
	 *
	 * These typically appear below the player.
	 * Clicking a track triggers the player to appear with its src.
	 *
	 * @param string $state_id The ID of the container.
	 * @param array  $tracks   Tracks.
	 * @return void
	 */
	public function print_tracks( $state_id, $tracks ) {
		?>
		<div class="wp-playlist-tracks">
			<?php foreach ( $tracks as $index => $track ) : ?>
				<?php
				$on            = 'tap:AMP.setState(' . wp_json_encode( array( $state_id => array( 'selectedIndex' => $index ) ) ) . ')';
				$initial_class = 0 === $index ? 'wp-playlist-item wp-playlist-playing' : 'wp-playlist-item';
				$bound_class   = sprintf( '%d == %s.selectedIndex ? "wp-playlist-item wp-playlist-playing" : "wp-playlist-item"', $index, $state_id );
				?>
				<div class="<?php echo esc_attr( $initial_class ); ?>" [class]="<?php echo esc_attr( $bound_class ); ?>" >
					<a class="wp-playlist-caption" on="<?php echo esc_attr( $on ); ?>">
						<?php echo esc_html( strval( $index + 1 ) . '.' ); ?> <span class="wp-playlist-item-title"><?php echo esc_html( $this->get_title( $track ) ); ?></span>
					</a>
					<?php if ( isset( $track['meta']['length_formatted'] ) ) : ?>
						<div class="wp-playlist-item-length"><?php echo esc_html( $track['meta']['length_formatted'] ); ?></div>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
		<?php
	}
 	/**
	 * Gets the data for the playlist.
	 *
	 * @see wp_playlist_shortcode()
	 * @param array $attr The shortcode attributes.
	 * @return array $data The data for the playlist.
	 */
	public function get_data( $attr ) {
		$markup = wp_playlist_shortcode( $attr );
		preg_match( self::PLAYLIST_REGEX, $markup, $matches );
		if ( empty( $matches[1] ) ) {
			return array();
		}
		return json_decode( $matches[1], true );
	}
 	/**
	 * Gets the title for the track.
	 *
	 * @param array $track The track data.
	 * @return string $title The title of the track.
	 */
	public function get_title( $track ) {
		if ( ! empty( $track['caption'] ) ) {
			return $track['caption'];
		} elseif ( ! empty( $track['title'] ) ) {
			return $track['title'];
		}
		return '';
	}
	function ampforwp_playlist(){ ?>
		.amp-carousel-slide .wp-playlist-current-item{display: grid;grid-template-columns: 50px 1fr;align-items: center;margin-bottom: 20px;grid-gap: 0px 20px;}
		 .amp-carousel-slide{background: #ccc;padding: 10px;border-radius: 3px;box-sizing: border-box;display: inline-grid;}
		.wp-playlist-tracks{background: #f1f1f1;width: 100%;margin-top: 10px;padding: 30px;margin-bottom:20px;box-sizing: border-box;}
		.wp-playlist-item{margin-bottom:15px;font-size: 16px;line-height: 1.4;}
		.wp-playlist-item a{color:#333;}
		.amp-carousel-slide amp-audio audio{width:100%;}
		.wp-playlist-caption{cursor: pointer;} 
		.wp-playlist-item.wp-playlist-playing, .wp-playlist-item.wp-playlist-playing a{color:#00b900;}
		.amp-carousel-slide amp-audio{margin:0 auto;display:block;}
		@media(max-width:767px){
			.amp-carousel-slide amp-audio{max-width:300px}
		}
		@media(max-width:500px){
			.wp-playlist-tracks {padding:20px;}
		}
		@media(max-width:400px){
			.amp-carousel-slide amp-audio{max-width:240px}
		}
		<?php  
	}
} 