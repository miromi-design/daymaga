<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="format-detection" content="email=no,telephone=no,address=no" />
  <meta name="msapplication-TileColor" content="#da532c" />
  <meta name="theme-color" content="#ffffff" />
  <?php wp_head(); ?>
</head>

<body>
  <!-- header -->
  <header class="l-header js-header">
    <div class="l-header__inner js-fv">
      <?php if (!is_single()) : // シングルページ以外ではロゴをh1に、シングルページではfigureに 
      ?>
        <h1 class="l-header__logo" aria-label="DayMaga">
        <?php else : ?>
          <figure class="l-header__logo" aria-label="DayMaga">
          <?php endif; ?>

          <?php echo img_logo('header-logo.svg', 'l-header__logo--svg', 'DayMaga'); ?>



          <a class="l-header__logo--scrolled" href="<?php echo esc_url(home_url()); ?>" aria-label="Home">
            <svg
              role="img"
              aria-label="DayMaga">
              <use href="<?php echo esc_url(get_template_directory_uri()); ?>/assets/svg/sprite.svg#logo-mark"></use>
            </svg>
          </a>

          <?php if (!is_single()) : // シングルページ以外ではh1を閉じる 
          ?>
        </h1>
      <?php else : ?>
        </figure>
      <?php endif; ?>

      <!--ここからheaderの中身-->
      <div class="l-header__wrap">
        <nav class="l-header__nav" aria-label="メインメニュー">
          <ul class="l-header__menu">
            <?php
            wp_nav_menu(array(
              'theme_location' => 'global', // グローバルメニューのスラッグ
              'container' => false,
              'items_wrap' => '%3$s', // <ul>タグをカスタムHTML内に直接組み込む
              'depth' => 1, // 必要に応じてネストレベルを制御
            ));
            ?>
          </ul>
        </nav>

        <div class="l-header__btnWrap">
          <a href="#" class="l-header__btn">
            <svg
              role="img"
              aria-label="コンサルをお探しの企業様 まずは無料相談">
              <use href="<?php echo esc_url(get_template_directory_uri()); ?>/assets/svg/sprite.svg#header-btn1"></use>
            </svg>
          </a>
          <a href="#" class="l-header__btn">
            <svg
              role="img"
              aria-label="コンサルタントの方 案件の紹介登録">
              <use href="<?php echo esc_url(get_template_directory_uri()); ?>/assets/svg/sprite.svg#header-btn2"></use>
            </svg>
          </a>
        </div>
        <div class="l-header__keyword">
          <a href="#keyword"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/img/common/search-icon.png" alt=""></a>
        </div>
      </div>
    </div>
  </header>

  <div class="l-drawer__iconWrap">
    <button
      id="js-drawer-icon"
      class="l-header__open l-drawer__icon"
      aria-label="メニューを開く"
      type="button"
      aria-controls="menu"
      aria-expanded="false">
      <span class="l-drawer__icon--bars">
        <span class="l-drawer__icon--bar"></span>
        <span class="l-drawer__icon--bar"></span>
        <span class="l-drawer__icon--bar"></span>
      </span>
    </button>
    <div class="l-drawer__keyword">
      <a href="#keyword" role="button" aria-label="キーワードで絞り込むへジャンプする"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/img/common/search-icon.png" alt=""></a>
    </div>
  </div>
  <!-- /.drawer-icon-->

  <div id="js-drawer-content" class="l-drawer__content">
    <div class="l-drawer__inner">
      <nav class="l-drawer__nav" aria-label="ドロワーメニュー">
        <ul class="l-drawer__menu">
          <?php
          wp_nav_menu(array(
            'theme_location' => 'global', // グローバルメニューのスラッグ
            'container' => false,
            'items_wrap' => '%3$s', // <ul>タグをカスタムHTML内に直接組み込む
            'depth' => 1, // 必要に応じてネストレベルを制御
            'menu_class' => 'l-drawer__list',
          ));
          ?>
        </ul>
      </nav>
      <div class="l-drawer__contentKeyword">
        <a href="#keyword" role="button" aria-label="キーワードで絞り込むへジャンプする"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/img/common/search-icon.png" alt=""></a>
      </div>
      <div id="js-focus-trap" tabindex="0"></div>
    </div>
  </div>

  <div id="js-drawer-background" class="l-drawer__background"></div>
  <!-- /.drawer -->