<!doctype html>
<html <?php language_attributes(); ?> class="no-js">
  <head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <title><?php bloginfo('name'); wp_title(' - '); ?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php bloginfo( 'description' ); ?>">
    <?php wp_head(); ?>
  </head>
  <body>

    <!-- header -->
    <header role="banner">
      <?php wp_nav_menu(array(
            'theme_location'  => 'header-menu',)
    );?>
    </header>
    <!-- /header -->
