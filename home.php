<?php get_header(); ?>

<main>
  <div class="p-tag l-lower-page">
    <div class="p-tag__inner l-inner">
      <h2 class="c-section-title">すべての記事</h2>

      <div class="p-tabPost__tabContents is-lower-page">
        <p class="p-tabPost__lineUpWrap">
          <span class="p-tabPost__lineUp is-active">新着順</span>
          <span class="p-tabPost__lineUp">人気順</span>
        </p>
        <p class="p-tabPost__tab--tabALL"><span>すべて</span></p>

        <div class="p-tabPost__tabpanelWrapper">
          <div id="tabpanel_all" class="p-tabPost__tabpanel" role="tabpanel" aria-labelledby="tab-all">
            <div class="p-tabPost__cards">
              <?php
              // 現在のページ番号を取得
              $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

              // WP_Queryの引数を設定
              $args = [
                'post_type' => 'post',
                'posts_per_page' => 9, // 1ページあたり9件の投稿を表示
                'paged' => $paged, // 現在のページ番号を設定
                'orderby' => 'date', // 日付で並べ替え
                'order' => 'DESC', // 降順
              ];

              // WP_Queryのインスタンスを作成
              $all_posts_query = new WP_Query($args);
              ?>

              <!-- 記事のループ処理開始 -->
              <?php if ($all_posts_query->have_posts()) : ?>
                <?php while ($all_posts_query->have_posts()) : $all_posts_query->the_post(); ?>

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
      get_template_part('template-parts/pagination', null, array('query' => $all_posts_query));
      ?>

    <?php wp_reset_postdata(); ?>
    <!-- 記事のループ処理終了 -->

  </div>

  <?php get_template_part('template-parts/keyword'); ?>

  <?php get_template_part('template-parts/cta'); ?>

</main>

<?php get_footer(); ?>