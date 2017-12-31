<?php
function understrap_remove_scripts() {
    wp_dequeue_style( 'understrap-styles' );
    wp_deregister_style( 'understrap-styles' );

    wp_dequeue_script( 'understrap-scripts' );
    wp_deregister_script( 'understrap-scripts' );

    // Removes the parent themes stylesheet and scripts from inc/enqueue.php
}
add_action( 'wp_enqueue_scripts', 'understrap_remove_scripts', 20 );

add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
function theme_enqueue_styles() {

	// Get the theme data
	$the_theme = wp_get_theme();
    wp_enqueue_style( 'child-understrap-styles', get_stylesheet_directory_uri() . '/css/child-theme.min.css', array(), $the_theme->get( 'Version' ) );
    wp_enqueue_script( 'jquery');
	wp_enqueue_script( 'popper-scripts', get_template_directory_uri() . '/js/popper.min.js', array(), false);
    wp_enqueue_script( 'child-understrap-scripts', get_stylesheet_directory_uri() . '/js/child-theme.min.js', array(), $the_theme->get( 'Version' ), true );
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}

/* Copied from understrap_posted_on and understrap_entry_footer */
function understrap_entry_meta() {
    $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
    $time_string = sprintf( $time_string,
                            esc_attr( get_the_date( 'c' ) ),
                            esc_html( get_the_date() )
    );
    $posted_on = sprintf(
        esc_html_x( 'Posted on %s', 'post date', 'understrap' ),
        '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
    );
    $byline = sprintf(
        esc_html_x( 'by %s', 'post author', 'understrap' ),
        '<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
    );
    echo '<span class="posted-on">' . $posted_on . '</span><span class="byline"> ' . $byline . '</span> | '; // WPCS: XSS OK.
    comments_popup_link(
        esc_html__( 'Leave a reply', 'understrap' ),
        esc_html__( '1 reply', 'understrap' ),
        esc_html__( '% replies', 'understrap' ),
        '',
        esc_html__( 'No replies', 'understrap' ) );
    edit_post_link(
        esc_html__( 'Edit', 'understrap' ),
        '',
        '',
        '',
        'edit-link'
    );
}

/**
 * Prints meta info for footer.
 */
function understrap_entry_footer() {
    if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
        $time_string = '<time class="updated" datetime="%1$s">%2$s</time>';
        $time_string = sprintf( $time_string,
                                esc_attr( get_the_modified_date( 'c' ) ),
                                esc_html( get_the_modified_date() )
        );
        echo sprintf(
            esc_html_x( 'Updated on %s', 'update date', 'understrap' ),
            $time_string
        );
    }
}

function urth_understrap_post_nav_link($link) {
    if ($link == '' ) {
        return;
    }

    echo preg_replace( '/<a /', '<a class="btn btn-sm btn-outline-primary" ', $link );
}

function understrap_post_nav() {
    // Don't print empty markup if there's nowhere to navigate.
    $previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
    $next     = get_adjacent_post( false, '', false );

    if ( ! $next && ! $previous ) {
        return;
    }
    ?>
    <nav class="container navigation post-navigation">
        <h2 class="sr-only"><?php _e( 'Post navigation', 'understrap' ); ?></h2>
		<div class="row nav-links justify-content-between">
			<?php
            urth_understrap_post_nav_link( get_previous_post_link( '%link', _x( '←&nbsp;Previous post', 'Previous post link', 'understrap' ) ) );
            urth_understrap_post_nav_link( get_next_post_link( '%link', _x( 'Next post&nbsp;→', 'Next post link', 'understrap' ) ) );
			?>
		</div><!-- .nav-links -->
	</nav><!-- .navigation -->

	<?php
}

/**
 * Adds a custom read more link to all excerpts, manually or automatically generated
 *
 * @param string $post_excerpt Posts's excerpt.
 *
 * @return string
 */
function urth_understrap_all_excerpts_get_more_link( $post_excerpt ) {
    return $post_excerpt . ' … <small>(<a href="' . esc_url( get_permalink( get_the_ID() )) . '">' .
           __( 'Read More', 'understrap' ) . '</a>)</small>';
}


function urth_understrap_init() {
    remove_filter( 'wp_trim_excerpt', 'understrap_all_excerpts_get_more_link' );
    add_filter( 'wp_trim_excerpt', 'urth_understrap_all_excerpts_get_more_link' );
}

add_action( 'init', 'urth_understrap_init' );

/* Copied from The Bootstrap theme and then edited a bit. */
function urth_understrap_one_comment( $comment, $args, $depth ) {
    $GLOBALS['comment'] = $comment;
    $edit_link_before = '<span class="edit-link">';
    $edit_link_after = '</span>';

    if ( 'pingback' == $comment->comment_type OR 'trackback' == $comment->comment_type ) : ?>
        <li id="li-comment-<?php comment_ID(); ?>" <?php comment_class(); ?>>
            <p class="row">
                <strong class="ping-label"><?php _e( 'Pingback:', 'the-bootstrap' ); ?></strong>
                <span class=""><?php comment_author_link(); edit_comment_link( __( 'Edit', 'the-bootstrap' ),
                                                                               $edit_link_before, $edit_link_after ); ?></span>
            </p>
        
    <?php else: ?>
        
        <li id="li-comment-<?php comment_ID(); ?>" <?php comment_class(); ?>>
            <article class="comment">
                <div class="comment-author-avatar media">
                    <?php echo get_avatar( $comment, 70, null, false, array( 'class' => 'mr-2' ) ); ?>
                    <div class="media-body">
                        <footer class="comment-meta">
                            <div class="comment-author vcard">
                                <?php
                                /* translators: 1: comment author, 2: date and time */
                                printf( __( '%1$s <span class="says">said</span> on %2$s:', 'the-bootstrap' ),
                                        sprintf( '<span class="fn">%s</span>', get_comment_author_link() ),
                                        sprintf( '<a href="%1$s"><time pubdate datetime="%2$s">%3$s</time></a>',
                                                                                                  esc_url( get_comment_link( $comment->comment_ID ) ),
                                                get_comment_time( 'c' ),
                                                /* translators: 1: date, 2: time */
                                                sprintf( __( '%1$s at %2$s', 'the-bootstrap' ), get_comment_date(), get_comment_time() )
                                       )
                               );
                               edit_comment_link( __( 'Edit', 'the-bootstrap' ), $edit_link_before, $edit_link_after );
                               ?>
                           </div><!-- .comment-author .vcard -->

                           <?php if ( ! $comment->comment_approved ) : ?>
                               <div class="comment-awaiting-moderation alert alert-info"><em><?php _e( 'Your comment is awaiting moderation.', 'the-bootstrap' ); ?></em></div>
                           <?php endif; ?>

                       </footer><!-- .comment-meta -->
                       <div class="comment-content">
                           <?php
                           comment_text();
                           comment_reply_link( array_merge( $args, array(
                               'reply_text'    =>  __( 'Reply <span>&darr;</span>', 'urth-understrap' ),
                               'depth'         =>  $depth,
                               'max_depth'     =>  $args['max_depth']
                           ) ) ); ?>
                       </div><!-- .comment-content -->
                   </div>
               </div>
            </article><!-- #comment-<?php comment_ID(); ?> .comment -->
            
    <?php endif; // comment_type
}

