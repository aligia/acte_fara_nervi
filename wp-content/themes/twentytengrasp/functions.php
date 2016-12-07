<?php

function my_theme_enqueue_styles() {

	write_log('THIS IS THE START OF MY CUSTOM DEBUG');

	write_log('Here 1');

    $parent_style = 'parent-style'; // This is 'twentyfifteen-style' for the Twenty Fifteen theme.

    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'child-style',
        get_stylesheet_directory_uri() . '/custom.css',
        array( $parent_style ),
        wp_get_theme()->get('Version')
    );
}
add_filter( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );

function twentyten_posted_on() 
{
	printf
	( __( '<span class="%1$s">Posted on</span> %2$s', 'twentyten' ),
		'meta-prep meta-prep-author',
		sprintf( '<a href="%1$s" title="%2$s" rel="bookmark"><span class="entry-date">%3$s</span></a>',
			get_permalink(),
			esc_attr( get_the_time() ),
			get_the_date()
		)
	);
}


if (!function_exists('write_log')) {
    function write_log ( $log )  {
        if ( true === WP_DEBUG ) {
            if ( is_array( $log ) || is_object( $log ) ) {
                error_log( print_r( $log, true ) );
            } else {
                error_log( $log );
            }
        }
    }
}

/* Searcher */
function sm_register_query_vars( $vars ) {
	$vars[] = 'type';
	$vars[] = 'city';
	return $vars;
} 
add_filter( 'query_vars', 'sm_register_query_vars' );

/**
 * Build a custom query based on several conditions
 * The pre_get_posts action gives developers access to the $query object by reference
 * any changes you make to $query are made directly to the original object - no return value is requested
 *
 * @link https://codex.wordpress.org/Plugin_API/Action_Reference/pre_get_posts
 *
 */
function sm_pre_get_posts( $query ) {
	// check if the user is requesting an admin page 
	// or current query is not the main query
	if ( is_admin() || ! $query->is_main_query() ){
		return;
	}

	// edit the query only when post type is 'accommodation'
	// if it isn't, return
	if ( !is_post_type_archive( 'accommodation' ) ){
		return;
	}

	$meta_query = array();

	// add meta_query elements
	if( !empty( get_query_var( 'city' ) ) ){
		$meta_query[] = array( 'key' => '_sm_accommodation_city', 'value' => get_query_var( 'city' ), 'compare' => 'LIKE' );
	}

	if( !empty( get_query_var( 'type' ) ) ){
		$meta_query[] = array( 'key' => '_sm_accommodation_type', 'value' => get_query_var( 'type' ), 'compare' => 'LIKE' );
	}

	if( count( $meta_query ) > 1 ){
		$meta_query['relation'] = 'AND';
	}

	if( count( $meta_query ) > 0 ){
		$query->set( 'meta_query', $meta_query );
	}
}
add_action( 'pre_get_posts', 'sm_pre_get_posts', 1 );

function sm_setup() {
	add_shortcode( 'sm_search_form', 'sm_search_form' );
}
add_action( 'init', 'sm_setup' );

function sm_search_form( $args ){
	
	write_log('THIS IS THE START OF MY CUSTOM DEBUG');

	write_log('Here 1');
		
	// The Query
	// meta_query expects nested arrays even if you only have one query
	$sm_query = new WP_Query( array( 'post_type' => 'accommodation', 'posts_per_page' => '-1', 'meta_query' => array( array( 'key' => '_sm_accommodation_city' ) ) ) );

	// The Loop
	if ( $sm_query->have_posts() ) {
		$cities = array();
		write_log('Here 2');
		while ( $sm_query->have_posts() ) {
			$sm_query->the_post();
			$city = get_post_meta( get_the_ID(), '_sm_accommodation_city', true );

			// populate an array of all occurrences (non duplicated)
			if( !in_array( $city, $cities ) ){
				$cities[] = $city;    
			}
		}
	} else{
		   echo 'Baza de date e goală.';
		   return;
	}
	write_log('Here 3');

	/* Restore original Post Data */
	wp_reset_postdata();

	if( count($cities) == 0){
		return;
	}

	asort($cities);
		
	$select_city = '<select name="city" style="width: 100%">';
	$select_city .= '<option value="" selected="selected">' . __( 'Select city', 'smashing_plugin' ) . '</option>';
	foreach ($cities as $city ) {
		$select_city .= '<option value="' . $city . '">' . $city . '</option>';
	}
	$select_city .= '</select>' . "\n";
	write_log('Here 4');
	reset($cities);

	$args = array( 'hide_empty' => false );
	$typology_terms = get_terms( 'typology', $args );
	if( is_array( $typology_terms ) ){
		write_log('Here 5');
		
		$select_typology = '<select name="typology" style="width: 100%">';
		$select_typology .= '<option value="" selected="selected">' . __( 'Select typology', 'smashing_plugin' ) . '</option>';
		foreach ( $typology_terms as $term ) {
			$select_typology .= '<option value="' . $term->slug . '">' . $term->name . '</option>';
		}
		$select_typology .= '</select>' . "\n";
		
		$select_type = '<select name="type" style="width: 100%">';
		$select_type .= '<option value="" selected="selected">' . __( 'Select room type', 'smashing_plugin' ) . '</option>';
		$select_type .= '<option value="entire">' . __( 'Entire house', 'smashing_plugin' ) . '</option>';
		$select_type .= '<option value="private">' . __( 'Private room', 'smashing_plugin' ) . '</option>';
		$select_type .= '<option value="shared">' . __( 'Shared room', 'smashing_plugin' ) . '</option>';
		$select_type .= '</select>' . "\n";

		$output = '<form action="' . esc_url( home_url() ) . '" method="GET" role="search">';
		$output .= '<div class="smselectbox">' . esc_html( $select_city ) . '</div>';
		$output .= '<div class="smselectbox">' . esc_html( $select_typology ) . '</div>';
		$output .= '<div class="smselectbox">' . esc_html( $select_type ) . '</div>';
		$output .= '<input type="hidden" name="post_type" value="accommodation" />';
		$output .= '<p><input type="submit" value="Go!" class="button" /></p></form>';

		write_log('Here 6');
	return $output;
	}
}

?>