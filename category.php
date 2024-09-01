<?php
// category.php

get_header(); ?>

<main>
  <div class="p-category l-lower-page">
    <div class="p-category__inner l-inner">
      <h2 class="c-section-title">
        <?php
        if (is_category()) :
          // 現在のカテゴリーオブジェクトを取得
          $category = get_queried_object();
          // カテゴリー名を表示
          echo esc_html($category->name) . 'の検索結果';
        else :
          echo 'カテゴリーが見つかりませんでした'; // カテゴリーが見つからない場合のメッセージ
        endif;
        ?>
      </h2>

      <div class="p-tabPost__tabContents is-lower-page">
        <p class="p-tabPost__lineUpWrap">
          <span class="p-tabPost__lineUp is-active">新着順</span>
          <span class="p-tabPost__lineUp">人気順</span>
        </p>
        <?php
        // 現在のカテゴリーオブジェクトを取得
        $category = get_queried_object();
        $category_slug = $category->slug;
        ?>

        <p class="p-tabPost__tab--tabALL is-<?php echo esc_attr($category_slug); ?>">
          <span><?php single_cat_title(); ?></span>
        </p>

        <div class="p-tabPost__tabpanelWrapper">
          <div class="p-tabPost__tabpanel is-<?php echo esc_attr($category_slug); ?>">
            <div class="p-tabPost__cards">
              <?php
              // 現在のカテゴリーを取得
              $category = get_queried_object();

              // WP_Queryの引数を設定
              $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
              $args = [
                'post_type' => 'post',
                'posts_per_page' => 9, // 最大9件の投稿を表示
                'paged' => $paged, // ページネーションの設定
                'orderby' => 'date', // 日付で並べ替え
                'order' => 'DESC', // 降順
                'cat' => $category->term_id, // 現在のカテゴリーを指定
              ];

              // WP_Queryのインスタンスを作成
              $category_query = new WP_Query($args);
              ?>

              <!-- 記事のループ処理開始 -->
              <?php if ($category_query->have_posts()) : ?>
                <?php while ($category_query->have_posts()) : $category_query->the_post(); ?>

                  <?php get_template_part('template-parts/card-contents'); ?>
                  <!--記事のカード-->

                <?php endwhile; ?>

              <?php else : ?>
                <p class="p-tabPost__noPost">投稿の準備中です。</p>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
      <!-- ページネーション -->
      <?php
      // pagination.phpテンプレートパーツを呼び出し
      get_template_part('template-parts/pagination', null, array('query' => $category_query));
      ?>
      <?php wp_reset_postdata(); ?>
      <!-- 記事のループ処理終了 -->
    </div>
    <?php get_template_part('template-parts/keyword'); ?>

    <?php get_template_part('template-parts/cta'); ?>
  </div>

</main>
<?php get_footer();
