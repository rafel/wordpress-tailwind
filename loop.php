<?php if (have_posts()): while (have_posts()) : the_post(); ?>
  <!-- article -->
  <article>
    <?php if ( has_post_thumbnail() ) : ?>
      <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
        <?php the_post_thumbnail(); ?>
      </a>
    <?php endif; ?>
    <h2><?php the_title(); ?></h2>
    <?php the_excerpt(); ?>
    <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
      Read more ->
    </a>
  </article>
  <!-- /article -->
<?php endwhile; ?>
<?php else : ?>
  <h2><?php esc_html_e( 'No results found', 'wptw' ); ?></h2>
<?php endif; ?>
