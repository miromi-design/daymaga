<?php
// tag.php

get_header(); ?>

<main>
  <div class="p-tag l-lower-page">
    <div class="p-tag__inner l-inner">
      <h2 class="c-section-title">
        <?php
        if (is_tag()) :
          // 現在のタグオブジェクトを取得
          $tag = get_queried_object();
          // タグ名を表示
          echo '#' . esc_html($tag->name) . 'の検索結果';
        else :
          echo 'タグが見つかりませんでした'; // タグが見つからない場合のメッセージ
        endif;
        ?>
      </h2>

      <div class="p-tabPost__tabContents is-lower-page">
        <p class="p-tabPost__lineUpWrap">
          <span class="p-tabPost__lineUp is-active">新着順</span>
          <span class="p-tabPost__lineUp">人気順</span>
        </p>
        <p class="p-tabPost__tab--tabALL"><span>すべて</span></p>

        <div class="p-tabPost__tabpanelWrapper">
          <div class="p-tabPost__tabpanel">
            <div class="p-tabPost__cards">
              <?php
              // 現在のタグを取得
              $tag = get_queried_object();

              // WP_Queryの引数を設定
              $args = [
                'post_type' => 'post',
                'posts_per_page' => 9, // 最大9件の投稿を表示
                'paged' => get_query_var('paged') ? get_query_var('paged') : 1, // ページネーションの設定
                'orderby' => 'date', // 日付で並べ替え
                'order' => 'DESC', // 降順
                'tag' => $tag->slug, // 現在のタグを指定
              ];

              // WP_Queryのインスタンスを作成
              $tag_query = new WP_Query($args);
              ?>

              <!-- 記事のループ処理開始 -->
              <?php if ($tag_query->have_posts()) : ?>
                <?php while ($tag_query->have_posts()) : $tag_query->the_post(); ?>

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
    </div>
    <!-- ページネーション -->
    <?php
    // pagination.phpテンプレートパーツを呼び出し
    get_template_part('template-parts/pagination', null, array('query' => $tag_query));
    ?>
    <?php wp_reset_postdata(); ?>
    <!-- 記事のループ処理終了 -->
  </div>

  <?php get_template_part('template-parts/keyword'); ?>

  <?php get_template_part('template-parts/cta'); ?>

</main>

<?php get_footer(); ?>