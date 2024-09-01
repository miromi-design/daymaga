    <footer class="l-footer">
      <div class="l-footer__inner l-inner">
        <div class="l-footer__wrap">
          <figure class="l-footer__logo">
            <a href="<?php echo esc_url(home_url()); ?>" aria-label="Home">
              <svg
                class="l-footer__logo--svg"
                role="img"
                aria-label="<?php bloginfo('name'); ?>">
                <use href="<?php echo esc_url(get_template_directory_uri()); ?>/assets/svg/sprite.svg#logo-white"></use>
              </svg>
            </a>
          </figure>
          <?php
          // メニューの位置を指定してメニューを取得
          $menu_name = 'footer-menu'; // フッターメニューのスラッグ
          $locations = get_nav_menu_locations();
          $menu = wp_get_nav_menu_object($locations[$menu_name]);
          $menu_items = wp_get_nav_menu_items($menu->term_id);

          if ($menu_items) {
            $half = ceil(count($menu_items) / 2); // 2列に分けるための半分の数を計算
            $columns = array_chunk($menu_items, $half); // 配列を2つに分割
          ?>
            <div class="l-footer__menu">
              <div class="l-footer__menu--column">
                <ul>
                  <?php foreach ($columns[0] as $item): ?>
                    <li><a href="<?php echo esc_url($item->url); ?>"><?php echo esc_html($item->title); ?></a></li>
                  <?php endforeach; ?>
                </ul>
              </div>
              <div class="l-footer__menu--column">
                <ul>
                  <?php foreach ($columns[1] as $item): ?>
                    <li><a href="<?php echo esc_url($item->url); ?>"><?php echo esc_html($item->title); ?></a></li>
                  <?php endforeach; ?>
                </ul>
              </div>
            </div>
          <?php
          }
          ?>

        </div>
        <p class="l-footer__copyright">&copy;2024 Daytra Consul</p>
        <p class="l-footer__text">このサイトはCrown Cat株式会社様のご協力のもと作成したコーディング用の練習課題です。実在の人物・団体とは関係ありません。</p>
      </div>

    </footer>
    <!-- /.l-footer -->

    <?php wp_footer(); ?>
    </body>

    </html>