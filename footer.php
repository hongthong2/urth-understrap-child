<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package understrap
 */

$the_theme = wp_get_theme();
$container = get_theme_mod( 'understrap_container_type' );
?>

<?php get_sidebar( 'footerfull' ); ?>

<div class="wrapper" id="wrapper-footer">

	<div class="<?php echo esc_attr( $container ); ?>">

		<div class="row">

			<div class="col-12">

				    <footer class="site-footer" id="colophon">

					    <div class="site-info">
                            <a href="https://creativecommons.org/licenses/by-sa/4.0/"><img src="https://blog.urth.org/wp-content/plugins/creative-commons-configurator-1/media/cc/by-sa/4.0/80x15.png"></a>
                            Copyright &copy; <?php echo date('Y') ?> David Rolsky. Some rights reserved.
                            <br>
                            All content on this site is licensed under the <a href="https://creativecommons.org/licenses/by-sa/4.0/">Creative Commons Attribution-ShareAlike 4.0 International License</a> unless otherwise noted.
                        <br>
                        This theme is based on the <a href="https://github.com/holger1411/understrap">Understrap Child Theme</a>.
					</div><!-- .site-info -->

				</footer><!-- #colophon -->

			</div><!--col end -->

		</div><!-- row end -->

	</div><!-- container end -->

</div><!-- wrapper end -->

</div><!-- #page we need this extra closing tag here -->

<?php wp_footer(); ?>

</body>

</html>

