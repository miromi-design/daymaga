<div class="p-keyword l-inner">
  <div class="p-keyword__titleWrap">
    <h3 id="keyword" class="p-keyword__title">キーワードで絞り込む</h3>
  </div>
  <div class="p-keyword__wrap">
    <?php
    $tags = get_tags(array('hide_empty' => false)); // すべてのタグを取得（使用されていないタグも含む）

    if ($tags) :
      foreach ($tags as $tag) :
        // タグのリンクを作成
        $tag_link = get_tag_link($tag->term_id);
    ?>
        <a href="<?php echo esc_url($tag_link); ?>" class="p-keyword__item">
          #<?php echo esc_html($tag->name); ?>
        </a>
    <?php
      endforeach;
    endif;
    ?>
  </div>
</div>