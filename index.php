<?php get_header(); ?>
  <main role="main" aria-label="Content">
    <!-- section -->
    <section>
      <h1><?php bloginfo('name'); wp_title(' - '); ?></h1>
      <?php get_template_part( 'loop' ); ?>
      <?php next_posts_link( 'Next page' ); ?>
    </section>
    <!-- /section -->
  </main>
<?php get_footer(); ?>
