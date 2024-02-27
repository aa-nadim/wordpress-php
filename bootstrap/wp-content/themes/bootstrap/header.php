<?php
/**
 * The header.
 *
 * This is the template that displays all of the <head> section and everything up until main.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 * @subpackage Bootstrap
 * @since Bootstrap 1.0
 */

?><!doctype html>
<html <?php language_attributes(); ?> data-bs-theme="auto">
  <head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
  </head>
  <body <?php body_class(); ?>>
    <?php wp_body_open(); ?>

    <?php get_template_part('template-parts/header/color-modes'); ?>

  <div class="container">
    
    <?php get_template_part('template-parts/header/logo-area'); ?>
    <?php get_template_part('template-parts/header/navbar'); ?>
    
  </div>
