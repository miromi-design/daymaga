<?php get_header(); ?>

<main class="page404">
  <section class="p-404 l-lower-page">
    <div class="p-404__inner">
      <div class="p-404__wrap l-wrap">
        <figure class="p-404__mark">
          <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/img/common/logo-mark_color.png" alt="">
        </figure>
        <hgroup class="p-404__titleWrap">
          <h2 class="p-404__title--en" lang="en">this page not found</h2>
          <p class="p-404__title">404</p>
        </hgroup>
        <div class="p-404__footer">
          <div class="p-404__lead">申し訳ございません。お探しのページが存在しません。</div>
          <p class="p-404__text">トップページに戻ってコンテンツお探しください。</p>
          <div class="p-404__footer">
            <a href="<?php echo esc_url(home_url('')); ?>" class="c-btn__blue">TOPページに戻る</a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <?php get_template_part('template-parts/single-keyword'); ?>

  <?php get_template_part('template-parts/cta'); ?>

</main>

<?php get_footer(); ?>