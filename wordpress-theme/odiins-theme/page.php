<?php
get_header();
?>

<main class="container section section-white" style="max-width:900px; min-height:60vh; padding-top:3rem;">
  <?php
  if (have_posts()) :
      while (have_posts()) : the_post();
          the_title('<h1>', '</h1>');
          echo '<div style="margin-top:1.5rem; line-height:1.8;">';
          the_content();
          echo '</div>';
      endwhile;
  endif;
  ?>
</main>

<?php
get_footer();
?>
