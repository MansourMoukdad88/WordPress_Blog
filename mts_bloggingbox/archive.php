<?php
/**
 * The template for displaying archive pages.
 *
 * Used for displaying archive-type pages. These views can be further customized by
 * creating a separate template for each one.
 *
 * - author.php (Author archive)
 * - category.php (Category archive)
 * - date.php (Date archive)
 * - tag.php (Tag archive)
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 */
$mts_options = get_option(MTS_THEME_NAME);
get_header(); ?>

<div id="page">
	<div class="<?php mts_article_class(); ?>">
		<div id="content_box">
			<h1 class="postsby">
				<span><?php the_archive_title(); ?></span>
			</h1>
			<p><?php the_archive_description(); ?></p>
			<?php 
			if ( empty( $mts_options['mts_featured_categories'] ) ) {
				// default
				$class = 'page-magazine';
				$posts_num = 10;
			} else {
				$section = reset( $mts_options['mts_featured_categories'] );
				$category_id = $section['mts_featured_category'];
				$featured_categories[] = $category_id;
				$posts_num = $section['mts_featured_category_postsnum'];
				$layout = isset( $section['mts_featured_category_layout'] ) ? $section['mts_featured_category_layout'] : 'magazinepost';
				if ( $layout == 'gridpost' ) {
					$class = 'page-grid';
				} elseif( $layout == 'magazinepost' ) {
					$class = 'page-magazine';
				} elseif( $layout == 'fullpost' ) {
					$class = 'page-fullpost';
				}
			}

			?>
			<div class="article-wrap <?php echo $class; ?>">
			<?php $j = 1; if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
				<?php mts_archive_post($layout, $j); ?>
			<?php ++$j; endwhile; endif; ?>
			</div>

			<?php if ( $j !== 1 ) { // No pagination if there is no posts ?>
				<?php mts_pagination(); ?>
			<?php } ?>
		</div>
	</div>
	<?php get_sidebar(); ?>
<?php get_footer(); ?>