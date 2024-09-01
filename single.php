<?php get_header(); ?>

<main class="single">
  <div class="p-single l-lower-page">
    <div class="p-single__inner">
      <?php if (have_posts()): while (have_posts()): the_post(); ?>
          <article class="p-single__wrap">
            <div class="p-single__headingWrap">
              <h1 class="p-entry-heading p-entry__title">
                <?php the_title(); ?>
              </h1>
              <!--タイトル-->
              <p class="p-single__publish">公開日&emsp;<time datetime="<?php the_time('c'); ?>"><?php the_time('Y.m.d'); ?></time></p>
              <!--日時-->
              <p class="p-entry__category">
                <span class="u-visually-hidden">カテゴリ：</span>
                <?php
                $categories = get_the_category();
                if ($categories && !is_wp_error($categories)) :
                  foreach ($categories as $category) :
                    $category_slug_class = 'is-' . sanitize_title($category->slug); // カテゴリスラッグを使ってクラスを生成
                    $category_link = get_category_link($category->term_id); // カテゴリのリンクを取得
                ?>
                    <a href="<?php echo esc_url($category_link); ?>" class="<?php echo esc_attr($category_slug_class); ?>">
                      <?php echo esc_html($category->name); ?>
                    </a>
                <?php
                  endforeach;
                endif;
                ?>

              </p>
              <!--カテゴリ-->
            </div>
            <figure class="p-entry__thumbnail">
              <?php if (has_post_thumbnail()): // アイキャッチ画像が設定されてれば表示 
              ?>
                <?php the_post_thumbnail(); ?>
              <?php else : ?>
                <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/common/placeholeder.png" width="960" height="693" decoding="async" loading="lazy" alt="Placeholder" />
              <?php endif; ?>
            </figure>
            <!--画像-->

            <div class="p-entry__body p-entry-content">
              <?php the_content(); // 本文の表示 
              ?>
            </div>
            <div class="p-entry__tagContent">
              <p class="p-entry__tagText">この記事のタグ</p>
              <div class="p-entry__tagWrap">
                <p class="c-card__tag">
                  <?php
                  $tags = get_the_tags();
                  if ($tags && !is_wp_error($tags)) :
                    foreach ($tags as $tag) :
                      $tag_link = get_tag_link($tag->term_id); // タグのリンクを取得
                  ?>
                      <a href="<?php echo esc_url($tag_link); ?>" class="c-card__tagName">
                        <?php echo '#' . esc_html($tag->name); ?>
                      </a>
                  <?php
                    endforeach;
                  endif;
                  ?>
                </p>

              </div>
            </div>
          <?php endwhile; ?>
        <?php endif; ?>
          </article>

    </div>
  </div>


  <!--おすすめ記事-->
  <section class="p-single__recommend">
    <div class="p-single-recommend__inner l-inner">
      <h2 class="p-single-recommend__title">おすすめ記事</h2>

      <div class="splide-recommend p-recommend__splide splide" aria-label="おすすめ記事">
        <div class="splide__arrows">
          <button class="splide__arrow splide__arrow--prev is-single"></button>
          <button class="splide__arrow splide__arrow--next is-single"></button>
        </div>
        <div class="p-recommend__slideWrap">
          <div class="splide__track">
            <div class="p-recommend__cards splide__list">
              <?php
              // 現在の投稿のIDを取得
              $current_post_id = get_the_ID();

              // 現在の投稿のカテゴリを取得
              $categories = get_the_category($current_post_id);

              if (!empty($categories)) {
                $category_ids = array();
                foreach ($categories as $category) {
                  $category_ids[] = $category->term_id;
                }

                $args = [
                  'post_type' => 'post',
                  'posts_per_page' => 5,
                  'orderby' => 'date', // 日付順にソート
                  'order' => 'DESC', // 新しい順に
                  'category__in' => $category_ids, // 現在の投稿と同じカテゴリの記事を取得
                  'post__not_in' => array($current_post_id), // 現在の投稿を除外
                ];

                $wp_query = new WP_Query($args);

                // 記事のループ処理開始
                if ($wp_query->have_posts()) :
                  while ($wp_query->have_posts()) : $wp_query->the_post();
                    get_template_part('template-parts/recommend');
                  endwhile;
                else :
                  echo '<p>関連する記事がありません。</p>';
                endif;
                wp_reset_postdata();
              } else {
                echo '<p>カテゴリが設定されていません。</p>';
              }
              ?>
              <!-- 記事のループ処理終了 -->
            </div>
          </div>
        </div>
        <div class="my-carousel-progress is-single">
          <div class="my-carousel-progress-bar is-single"></div>
        </div>
      </div>
      <!--splideここまで-->
    </div>
  </section>
  <!--p-recommend-->

  <?php get_template_part('template-parts/single-keyword'); ?>


  <?php get_template_part('template-parts/cta'); ?>

</main>

<?php get_footer(); ?>