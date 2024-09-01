<?php get_header(); ?>
<main>
  <div class="p-index-mv">
    <div class="p-index-mv__inner">
      <div id="splide-mv" class="splide" aria-label="ピックアップ記事">
        <div class="splide__arrows">
          <button class="splide__arrow splide__arrow--prev"></button>
          <button class="splide__arrow splide__arrow--next"></button>
        </div>
        <div class="splide__track">
          <div class="p-index-mv__cards splide__list">
            <?php
            $args = [
              'post_type' => 'post', // 投稿タイプのスラッグ(通常投稿なので'post')
              'posts_per_page' => 5, // 表示件数
              'tag' => 'pickup', // Pickupタグがついた記事を表示
            ];
            $wp_query = new WP_Query($args);
            ?>
            <!-- 記事のループ処理開始 -->
            <?php if ($wp_query->have_posts()) : ?>
              <?php while ($wp_query->have_posts()) : $wp_query->the_post(); ?>
                <article class="p-index-mv__card splide__slide" aria-labelledby="<?php the_title(); ?>">
                  <a class="p-index-mv__link" href="<?php the_permalink(); ?>">
                    <div class="p-index-mv__card__wrapper">
                      <h2 class="p-index-mv__title">
                        <?php the_title(); ?>
                      </h2>
                      <!--タイトル-->
                      <p class="p-index-mv__category">
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
                      <p class="p-index-mv__tag">
                        <span class="u-visually-hidden">タグ：</span>
                        <?php
                        $tags = get_the_tags();
                        if ($tags && !is_wp_error($tags)) :
                          foreach ($tags as $tag) :
                        ?>
                            <span class="p-index-mv__tagName"><?php echo '#' . esc_html($tag->name); ?></span>
                        <?php
                          endforeach;
                        endif;
                        ?>
                      </p>
                      <!--タグ-->
                      <p class="p-index-mv__publish">
                        <span class="u-visually-hidden">投稿日：</span>
                        <time datetime="<?php the_time('c'); ?>"><?php the_time('Y.m.d'); ?></time>
                      </p>
                      <figure class="p-index-mv__thumbnail">
                        <?php if (has_post_thumbnail()) : ?>
                          <img src="<?php the_post_thumbnail_url('full'); ?>" width="960" height="693" decoding="async" alt="<?php the_title_attribute(); ?>" fetchpriority="high" />
                        <?php else : ?>
                          <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/common/placeholeder.png" width="960" height="693" decoding="async" fetchpriority="high" alt="Placeholder" />
                        <?php endif; ?>
                      </figure>
                      <!--サムネイル-->
                    </div>
                  </a>
                </article>
              <?php endwhile; ?>
            <?php endif; ?>
            <?php wp_reset_postdata(); ?>
            <!-- 記事のループ処理終了 -->
          </div>
        </div>
      </div>
      <!--splideここまで-->
    </div>
  </div>
  <!--p-index-mv-->

  <section class="p-index-new">
    <div class="p-index-new__inner l-inner">
      <h2 id="new" class="c-section-title is-new">新着情報</h2>
      <div class="p-index-new__cards">
        <?php
        $args = [
          'post_type' => 'post', // 投稿タイプのスラッグ
          'posts_per_page' => 3, // 表示件数
          'orderby' => 'date',   // 投稿日時でソート
          'order' => 'DESC',     // 新しい記事が上に来るように降順
          'category_name' => 'new',    // カテゴリースラッグを指定
        ];
        $wp_query = new WP_Query($args);
        ?>
        <!-- 記事のループ処理開始 -->
        <?php if ($wp_query->have_posts()) : ?>
          <?php while ($wp_query->have_posts()) : $wp_query->the_post(); ?>

            <?php get_template_part('template-parts/card-contents'); ?>
            <!--記事のカード-->

          <?php endwhile; ?>
        <?php endif; ?>
        <?php wp_reset_postdata(); ?>
        <!-- 記事のループ処理終了 -->
      </div>
      <div class="p-index-new__footer">
        <a href="<?php echo esc_url(home_url('/archives/category/new/')); ?>" class="c-btn" aria-label="新着情報のページへ">もっと見る</a>
      </div>
    </div>
  </section>
  <!--p-index-new-->

  <!--おすすめ記事-->
  <section class="p-index-recommend">
    <div class="p-index-recommend__inner l-inner">
      <h2 id="recommend" class="c-section-title is-white">おすすめ記事</h2>

      <div class="splide-recommend p-recommend__splide splide" aria-label="おすすめ記事">
        <div class="splide__arrows">
          <button class="splide__arrow splide__arrow--prev"></button>
          <button class="splide__arrow splide__arrow--next"></button>
        </div>
        <div class="p-recommend__slideWrap">
          <div class="splide__track">
            <div class="p-recommend__cards splide__list">
              <?php
              $args = [
                'post_type' => 'post', // 投稿タイプのスラッグ(通常投稿なので'post')
                'posts_per_page' => 5, // 表示件数
                'orderby' => 'rand', // ランダムにソート
              ];
              $wp_query = new WP_Query($args);
              ?>
              <!-- 記事のループ処理開始 -->
              <?php if ($wp_query->have_posts()) : ?>
                <?php while ($wp_query->have_posts()) : $wp_query->the_post(); ?>

                <?php get_template_part('template-parts/recommend'); ?>

                <?php endwhile; ?>
              <?php endif; ?>
              <?php wp_reset_postdata(); ?>
              <!-- 記事のループ処理終了 -->
            </div>
          </div>
        </div>
        <!--slideWrap-->
        <div class="my-carousel-progress">
          <div class="my-carousel-progress-bar"></div>
        </div>
      </div>
      <!--splideここまで-->

    </div>
  </section>
  <!--p-index-recommend-->

  <!--すべての記事-->
  <section class="p-index-all">
    <div class="p-index-all__inner l-inner">
      <h2 id="all" class="c-section-title">すべての記事</h2>

      <div id="js-tabs" class="p-tabPost__tabContents">
        <p class="p-tabPost__lineUpWrap">
          <span class="p-tabPost__lineUp is-active">新着順</span>
          <span class="p-tabPost__lineUp">人気順</span>
        </p>
        <ul class="p-tabPost__tabList" role="tablist">
          <li role="presentation">
            <a class="p-tabPost__tab is-all"
              id="tab-all"
              role="tab"
              href="#tabpanel_all"
              data-smooth-scroll="disabled">
              <span>すべて</span>
            </a>
          </li>
          <?php
          $categories = get_categories(array('hide_empty' => false)); // 空のカテゴリーも含める
          foreach ($categories as $category) {
            $tab_id = 'tab-' . esc_attr($category->slug);  // カテゴリのスラッグをIDに使用
            $tabpanel_id = 'tabpanel-' . esc_attr($category->slug);  // スラッグをパネルIDに使用
            $category_class = 'is-' . esc_attr($category->slug);  // スラッグをクラスに使用
          ?>
            <li role="presentation">
              <a class="p-tabPost__tab <?php echo esc_attr($category_class); ?>"
                id="<?php echo esc_attr($tab_id); ?>"
                role="tab"
                href="<?php echo esc_attr($tabpanel_id); ?>"
                data-smooth-scroll="disabled">
                <span><?php echo esc_html($category->name); ?></span>
              </a>
            </li>
          <?php
          }
          ?>
        </ul>

        <div class="p-tabPost__tabpanelWrapper">
          <div id="tabpanel_all" class="p-tabPost__tabpanel" role="tabpanel" aria-labelledby="tab-all">
            <div class="p-tabPost__cards">
              <?php
              // デフォルトの表示件数を設定
              $posts_per_page = 6;

              // Cookieが設定されている場合、それに基づいて記事数を変更
              if (isset($_COOKIE['posts_per_page'])) {
                $posts_per_page = intval($_COOKIE['posts_per_page']);
              }

              $args = [
                'post_type' => 'post',
                'posts_per_page' => $posts_per_page,
                'orderby' => 'date', // 日付で並べ替え
                'order' => 'DESC',
              ];
              $all_query = new WP_Query($args);
              ?>
              <!-- 記事のループ処理開始 -->
              <?php if ($all_query->have_posts()) : ?>
                <?php while ($all_query->have_posts()) : $all_query->the_post(); ?>

                  <?php get_template_part('template-parts/card-contents'); ?>
                  <!--記事のカード-->

                <?php endwhile; ?>
              <?php endif; ?>
              <?php wp_reset_postdata(); ?>
              <!-- 記事のループ処理終了 -->
            </div>
          </div>

          <!--カテゴリーごとの記事を取得する-->
          <?php
          foreach ($categories as $category) {
            $tab_id = 'tab-' . esc_attr($category->slug);  // カテゴリのスラッグをIDに使用
            $tabpanel_id = 'tabpanel-' . esc_attr($category->slug);  // スラッグをパネルIDに使用
            $category_class = 'is-' . esc_attr($category->slug);  // スラッグをクラスに使用

            // カテゴリごとの記事を取得
            $args = [
              'post_type' => 'post',
              'posts_per_page' => $posts_per_page,
              'orderby' => 'date', // 日付で並べ替え
              'order' => 'DESC',
              'cat' => $category->term_id, // カテゴリーIDでフィルタリング
            ];
            $category_query = new WP_Query($args);
          ?>
            <div id="<?php echo esc_attr($tabpanel_id); ?>" class="p-tabPost__tabpanel <?php echo esc_attr($category_class); ?>" role="tabpanel" aria-labelledby="<?php echo esc_attr($tab_id); ?>">
              <div class="p-tabPost__cards">
                <?php
                // デフォルトの表示件数を設定
                $posts_per_page = 6;

                // Cookieが設定されている場合、それに基づいて記事数を変更
                if (isset($_COOKIE['posts_per_page'])) {
                  $posts_per_page = intval($_COOKIE['posts_per_page']);
                }

                $args = [
                  'post_type' => 'post',
                  'posts_per_page' => $posts_per_page,
                  'orderby' => 'date', // 日付で並べ替え
                  'order' => 'DESC',
                  'cat' => $category->term_id, // カテゴリーIDでフィルタリング
                ];
                $category_query = new WP_Query($args);
                ?>
                <!-- 記事のループ処理開始 -->
                <?php if ($category_query->have_posts()) : ?>
                  <?php while ($category_query->have_posts()) : $category_query->the_post(); ?>

                    <?php get_template_part('template-parts/card-contents'); ?>
                    <!--記事のカード-->

                  <?php endwhile; ?>
                <?php else : ?>
                  <!-- 投稿がない場合のメッセージ -->
                  <p class="p-tabPost__noPost">投稿の準備中です。</p>
                <?php endif; ?>

                <?php wp_reset_postdata(); ?>
                <!-- 記事のループ処理終了 -->
              </div>
            </div>
          <?php
          }
          ?>
        </div>
      </div>
      <!--p-tabPost__tabContents-->
      <div class="p-index-all__footer">
        <a href="<?php echo esc_url(home_url('/all')); ?>" class="c-btn" aria-label="すべての記事のページへ">もっと見る</a>
      </div>
      <!--p-index-new__footer-->
    </div>
  </section>
  <!--p-index-all-->

  <?php get_template_part('template-parts/keyword'); ?>

  <?php get_template_part('template-parts/cta'); ?>

</main>

<?php get_footer(); ?>