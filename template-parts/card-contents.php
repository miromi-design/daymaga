<article class="c-card" aria-labelledby="<?php the_title(); ?>">
  <a class="c-card__link" href="<?php the_permalink(); ?>">
    <h3 class="c-card__title">
      <?php the_title(); ?>
    </h3>
    <!--タイトル-->
    <p class="c-card__category">
      <span class="u-visually-hidden">カテゴリ：</span>
      <?php
      $categories = get_the_category();
      if ($categories && !is_wp_error($categories)) :
        foreach ($categories as $category) :
          $category_slug_class = 'is-' . sanitize_title($category->slug); // カテゴリスラッグを使ってクラスを生成
      ?>
          <span class="<?php echo esc_attr($category_slug_class); ?>"><?php echo esc_html($category->name); ?></span>
      <?php
        endforeach;
      endif;
      ?>
    </p>
    <!--カテゴリ-->
    <p class="c-card__tag">
      <span class="u-visually-hidden">タグ：</span>
      <?php
      $tags = get_the_tags();
      if ($tags && !is_wp_error($tags)) :
        foreach ($tags as $tag) :
      ?>
          <span class="c-card__tagName"><?php echo '#' . esc_html($tag->name); ?></span>
      <?php
        endforeach;
      endif;
      ?>
    </p>
    <!--タグ-->
    <p class="c-card__publish">
      <span class="u-visually-hidden">投稿日：</span>
      <time datetime="<?php the_time('c'); ?>"><?php the_time('Y.m.d'); ?></time>
    </p>
    <figure class="c-card__thumbnail">
      <?php if (has_post_thumbnail()) : ?>
        <img src="<?php the_post_thumbnail_url('full'); ?>" decoding="async" loading="lazy" alt="<?php the_title_attribute(); ?>" />
      <?php else : ?>
        <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/common/placeholeder.png" width="960" height="693" decoding="async" loading="lazy" alt="Placeholder" />
      <?php endif; ?>
    </figure>
    <!--サムネイル-->
  </a>
</article>