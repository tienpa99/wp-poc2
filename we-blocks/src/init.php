<?php
/**
 * Blocks Initializer
 *
 * Enqueue CSS/JS of all the blocks.
 *
 * @since   1.0.0
 * @package WE
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue Gutenberg block assets for both frontend + backend.
 *
 * @uses {wp-editor} for WP editor styles.
 * @since 1.0.0
 */
function we_blocks_assets() { // phpcs:ignore
	// Styles.
	wp_enqueue_style(
		'we_blocks-style-css', // Handle.
		plugins_url( 'dist/blocks.style.build.css', dirname( __FILE__ ) ), // Block style CSS.
		array( 'wp-editor' ) // Dependency to include the CSS after it.
		// filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.style.build.css' ) // Version: File modification time.
	);

	wp_enqueue_style('we_blocks-slick-style', plugins_url( 'assets/css/slick.min.css', dirname( __FILE__ ) ) );
	wp_enqueue_script('we_blocks-slick-script',  plugins_url( 'assets/js/slick.min.js', dirname( __FILE__ ) ), array('jquery'));

	wp_enqueue_script(
		'post-slider-block-slide', // Handle.
		plugins_url( 'assets/js/we-posts-slider.js', dirname( __FILE__ ) ),
		array( 'wp-blocks', 'wp-i18n', 'wp-element', 'jquery' ), 
		true 
	);
}

// Hook: Frontend assets.
add_action( 'enqueue_block_assets', 'we_blocks_assets' );

/**
 * Enqueue Gutenberg block assets for backend editor.
 *
 * @uses {wp-blocks} for block type registration & related functions.
 * @uses {wp-element} for WP Element abstraction — structure of blocks.
 * @uses {wp-i18n} to internationalize the block's text.
 * @uses {wp-editor} for WP editor styles.
 * @since 1.0.0
 */
function we_blocks_editor_assets() { // phpcs:ignore
	// Scripts.
	wp_enqueue_script(
		'we_blocks-js', // Handle.
		plugins_url( '/dist/blocks.build.js', dirname( __FILE__ ) ), // Block.build.js: We register the block here. Built with Webpack.
		array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ), // Dependencies, defined above.
		// filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.build.js' ), // Version: File modification time.
		true // Enqueue the script in the footer.
	);

	// Styles.
	wp_enqueue_style(
		'we_blocks-editor-css', // Handle.
		plugins_url( 'dist/blocks.editor.build.css', dirname( __FILE__ ) ), // Block editor CSS.
		array( 'wp-edit-blocks' ) // Dependency to include the CSS after it.
		// filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.editor.build.css' ) // Version: File modification time.
	);
}

// Hook: Editor assets.
add_action( 'enqueue_block_editor_assets', 'we_blocks_editor_assets' );




//We Posts Slider Render Function
function we_post_slider_block_fn( $attributes, $content ) {

	$categoriesButton = "";
	$categories = get_categories(array(
		'taxonomy' => 'category',
		'term_taxonomy_id'   => $attributes['categories']
	));
	$blockId = $attributes['blockId'];
	$horizontalSpacing = $attributes['horizontalSpacing'] ? $attributes['horizontalSpacing'] : 0 ;

	//getting the slider settings
	$dataAttributes = "";
	foreach($attributes as $name => $value){
		if(is_bool($value)){
			$dataAttributes .= 'data-'.$name.'="true" ';
		} else if( is_int($value)){
			$dataAttributes .= 'data-'.$name.'="'.$value.'" ';
		}
	}
	if(isset($attributes["readMoreText"])){
		$readMoreText = $attributes["readMoreText"];
	}
	if(isset($attributes["sliderDotsClass"])){
		$sliderDotsClass = $attributes["sliderDotsClass"];
		$dataAttributes .= 'data-sliderDotsClass="'.$sliderDotsClass.'" ';
	}
	if(isset($attributes["cssEase"])){
		$cssEase = $attributes["cssEase"];
		$dataAttributes .= 'data-cssEase="'.$cssEase.'" ';
	}

    $recent_posts = get_posts( array(
		'post_status' => 'publish',
		'post_type' => 'post',
		'numberposts' => $attributes['postsToShow'] ? $attributes['postsToShow'] : 5,
		'orderby' => 'post_date',
		'category' => $attributes['categories'],
		'orderby' => $attributes['orderBy'] ? $attributes['orderBy'] : '',
		'order' => $attributes['order'] ? $attributes['order'] : ''
	) );
    
    if ( count( $recent_posts ) === 0 ) {
        return 'No posts';
	}
	
	$post_data = "<style>";
	if($horizontalSpacing >= 0){
		$post_data .= " #{$blockId} .gallery-frontend .slick-slide { margin: 0 {$horizontalSpacing}px; }";
		$post_data .= " #{$blockId} .gallery-frontend .slick-list  { margin: 0 -{$horizontalSpacing}px; }";
	}
	$post_data .= "</style>";
	
	$post_data .="<div id={$blockId} class='wp_posts_slider_block_wrapper'><div {$dataAttributes} class='we_psb_container gallery-frontend'>";
	$categoriesButton = "";
	foreach($recent_posts as  $key => $post){

		$attachment_id = get_post_thumbnail_id($post->ID);
		$url = wp_get_attachment_url( $attachment_id, 'thumbnail' );
		$link = esc_url(get_permalink($post->ID));
		$attachment_alt = get_post_meta( $attachment_id, '_wp_attachment_image_alt', true );
		$attachment_title = get_the_title($attachment_id);
		$key +=1;
		$post_data .= 
		"<div class='grid-item grid-item-{$key}' key={$post->ID} >";
		
			$post_data .= "<div class='gallery-image'>";
			if(!empty($url))
				$post_data .= "<div class='slider-image-container'><a href={$link}><img title='{$post->post_title}' alt='{$attachment_alt}' src='{$url}' /></a></div>";
			else 
			$post_data .= "<div class='slider-image-container'><a href={$link}><img title='{$post->post_title}' alt='{$attachment_alt}' src='".plugins_url( 'assets/img/placeholder-image.jpg', dirname( __FILE__ ) )."' /></a></div>";
			$post_data .= "<div class='box-content'>";
			if($attributes['showTitle']){
				$post_data .= "<h3 class='title'>";
				$post_data .= "<a href={$link}>{$post->post_title}</a>";
				$post_data .= "</h3>";
			}
					
			
			$post_data .= "</div>";
			if($attributes['displayPostExcerpt']){
				// Get the excerpt
				$excerpt = apply_filters( 'the_excerpt', get_post_field( 'post_excerpt', $post->ID, 'display' ) );

				if( empty( $excerpt ) ) {
					$excerpt = apply_filters( 'the_excerpt', wp_trim_words( $post->post_content, 40 ) );
				}

				if ( ! $excerpt ) {
					$excerpt = null;
				}
				$post_data .= "<p>".strip_tags($excerpt)."</p>";
			}

			$post_data .= "<div class='author-and-date'>";
			if($attributes['displayAuthor']){
				$userInfo = get_user_by("ID",$post->post_author);
				if($userInfo){					
					$post_data .= "<a href='{$userInfo->user_url}'>";
					$post_data .= "<img src='".plugins_url( 'assets/img/author.png', dirname( __FILE__ ) )."' class='author_pic'><span>{$userInfo->display_name}</span>";
					$post_data .= "</a>";
				}
			}

			if($attributes['displayPostDate']){
				$post_data .=  "<span>";
				$post_data .= sprintf(
					' <img src="'.plugins_url( 'assets/img/time.png', dirname( __FILE__ ) ).'"> <time datetime="%1$s" class="post-date">%2$s</time>',
					esc_attr( get_the_date( 'c', $post->ID ) ),
					esc_html( get_the_date( '', $post->ID ) )
				);
				$post_data .= "</span>";
			}

			if($attributes['displayCountReading']){
				$post_data .= "<a href={$link} class='readmore'>{$attributes['readMoreText']}</a>";
			}

			$post_data .= "</div>";
			$post_data .= "</div>";

		$post_data .= "</div>";
		
	}
	$post_data .= "</div>";

	return $post_data;
}
/**
 * Register action after all plugins have loaded
 * 
 */
function weRegisterPostSliderBlock(){
	if (function_exists("register_block_type")) {
		register_block_type( 'we/we-posts-slider-block', array(
			'render_callback' => 'we_post_slider_block_fn',
		) );
	}
}

add_action( 'plugins_loaded', 'weRegisterPostSliderBlock' );