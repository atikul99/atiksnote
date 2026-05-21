<?php
/*
Template Name: All Categories
*/

get_header();
?>

<div class="all-category-page">
	<div class="container">
		<h2><?php esc_html_e( 'All Categories', 'atiksnote' ); ?></h2>

		<div class="category-grid">

			<?php
			$categories = get_categories(array(
				'hide_empty' => false,
			));

			foreach ($categories as $category) :
			?>

				<div class="category-card">
					<h4>
						<a href="<?php echo esc_url(get_category_link($category->term_id)); ?>">
							<?php echo esc_html($category->name); ?>
						</a>
					</h4>

					<p>
						<?php echo esc_html($category->description); ?>
					</p>

					<span>
						<?php
						printf(
							esc_html__( '%d Posts', 'atiksnote' ),
							absint( $category->count )
						);
						?>
					</span>
				</div>

			<?php endforeach; ?>

		</div>
	</div>
</div>

<?php get_footer(); ?>