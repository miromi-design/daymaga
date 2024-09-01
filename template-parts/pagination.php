<?php
// クエリオブジェクトを引数として受け取る
$query = isset($args['query']) ? $args['query'] : $wp_query;

// 画像のパスを設定
$prev_img = get_template_directory_uri() . '/assets/img/common/arrow-prev.png';
$next_img = get_template_directory_uri() . '/assets/img/common/arrow-next.png';

// 現在のページ番号を取得
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

// ページネーションの設定
$big = 999999999; // need an unlikely integer
$paginate_links = paginate_links(array(
  'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
  'format' => '?paged=%#%',
  'current' => $paged,
  'total' => $query->max_num_pages,
  'prev_text' => '<img src="' . $prev_img . '" alt="前へ" class="c-pagination-arrow">',
  'next_text' => '<img src="' . $next_img . '" alt="次へ" class="c-pagination-arrow">',
  'before_page_number' => '<span class="c-page-number">',
  'after_page_number' => '</span>',
  'type' => 'array' // 配列として結果を取得
));

// ページネーションをカスタムHTMLで出力
if ($paginate_links) {
  echo '<nav class="c-pagination" role="navigation">';

  $prev_link = '';
  $next_link = '';
  $number_links = [];

  foreach ($paginate_links as $link) {
    if (strpos($link, 'prev') !== false) {
      $prev_link = '<div class="c-pagination__nav c-pagination__prev">' . $link . '</div>';
    } elseif (strpos($link, 'next') !== false) {
      $next_link = '<div class="c-pagination__nav c-pagination__next">' . $link . '</div>';
    } else {
      $number_links[] = $link;
    }
  }

  echo $prev_link;

  echo '<ul class="c-pagination__list">';
  foreach ($number_links as $link) {
    if (strpos($link, 'current') !== false) {
      echo '<li class="c-pagination__item is-current">' . $link . '</li>';
    } else {
      echo '<li class="c-pagination__item">' . $link . '</li>';
    }
  }
  echo '</ul>';

  echo $next_link;

  echo '</nav>';
}
