<?php

/*----------------------------------
		CSSやJSを読み込む
-----------------------------------*/
function my_script_init()
{
	// WordPressに含まれているjquery.jsを読み込まない
	wp_deregister_script('jquery');

	// テーマのCSSファイルの読み込み
	wp_enqueue_style("my-css", get_template_directory_uri() . "/assets/css/style.min.css", array(), filemtime(get_theme_file_path('assets/css/style.min.css')), "all");

	// jQueryの読み込み・true → </body>の前に書かれる
	wp_enqueue_script('jquery', "https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js", "", "1.0.1", true);

	// テーマのJavaScriptファイルの読み込み
	wp_enqueue_script("my-js", get_template_directory_uri() . "/assets/js/main.min.js", array("jquery"), filemtime(get_theme_file_path('assets/js/main.min.js')), true);

	// テーマのスタイルシートの読み込み
	wp_enqueue_style('my-theme-style', get_stylesheet_uri());

	// フロントページとシングルページでSplideのCSSとJSを読み込む
	if (is_front_page() || is_single()) {
		// SplideのCSSファイルの読み込み
		wp_enqueue_style("splide", get_template_directory_uri() . "/assets/splide/splide-core.min.css");

		// SplideのJavaScriptファイルの読み込み
		wp_enqueue_script("splide-js", get_template_directory_uri() . "/assets/splide/splide.min.js", [], false, true);
	}
}
add_action("wp_enqueue_scripts", "my_script_init");


/*----------------------------------------------------------*/
/* JS読み込みにdefer属性を付与 */
/*----------------------------------------------------------*/

add_filter('script_loader_tag', 'add_defer', 10, 2);
function add_defer($tag, $handle)
{
	if ($handle !== 'my-js') {
		return $tag;
	}
	return str_replace(' src=', ' defer src=', $tag);
}


/*----------------------------------
		アイキャッチ画像等の隠れているものを表示する
-----------------------------------*/
function my_setup()
{
	add_theme_support('post-thumbnails'); // アイキャッチ画像を有効化
	add_theme_support('automatic-feed-links'); // 投稿とコメントのRSSフィードのリンクを有効化
	add_theme_support('title-tag'); // titleタグ自動生成
	add_theme_support('html5', array('comment-list', 'comment-form', 'search-form', 'gallery', 'caption', 'style', 'script'));
}
add_action("after_setup_theme", "my_setup");


/*----------------------------------
		 メニューナブの設定
-----------------------------------*/
function my_menu_init()
{
	register_nav_menus(
		array(
			'global' => 'ヘッダーメニュー',
			'drawer' => 'ドロワーメニュー',
			'footer-menu' => 'フッターメニュー'
		)
	);
}
add_action('init', 'my_menu_init');

/*----------------------------------
		グローバルメニューのliのid,classを削除
-----------------------------------*/
add_filter('nav_menu_css_class', 'wp_navtag_remove', 100, 1); //liのclassを強制的に全削除
add_filter('nav_menu_item_id', 'wp_navtag_remove', 100, 1); //liのidを強制的に削除
add_filter('page_css_class', 'wp_navtag_remove', 100, 1); //これはテーマによって記述しなくても良い場合がありますが、念の為記述しておいてください
function wp_navtag_remove($var)
{
	return is_array($var) ? array_intersect($var, array('current-menu-item')) : '';
}

/*----------------------------------
		ダッシュボードの並び替え
-----------------------------------*/
function my_custom_menu_order($menu_order)
{
	if (!$menu_order) return true;
	return array(
		'index.php', //ダッシュボード
		'separator1', //セパレータ１
		'edit.php', //投稿
		'edit.php?post_type=news', //カスタムポスト
		'upload.php', //メディア
		'separator2', //セパレータ２
		'edit.php?post_type=page', //固定ページ
		'edit-comments.php', //コメント
		'separator-last', //最後のセパレータ
		'themes.php', //外観
		'plugins.php', //プラグイン
		'users.php', //ユーザー
		'tools.php', //ツール
		'options-general.php', //設定
	);
}
add_filter('custom_menu_order', 'my_custom_menu_order');
add_filter('menu_order', 'my_custom_menu_order');


// テーマの更新通知を非表示にする
add_filter('pre_site_transient_update_themes', '__return_null');


// =========================================
//  wp_head()出力内容を整理
// =========================================
/* WordPressのバージョン出力を排除する */
remove_action('wp_head', 'wp_generator');

/* JS, CSS要素のバージョン出力を排除する */
function remove_cssjs_ver2($src)
{
	if (strpos($src, 'ver='))
		$src = remove_query_arg('ver', $src);
	return $src;
}
add_filter('style_loader_src', 'remove_cssjs_ver2', 9999);
add_filter('script_loader_src', 'remove_cssjs_ver2', 9999);

/* テキストエディタの絵文字に対応する為の各種出力を排除する */
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('admin_print_scripts', 'print_emoji_detection_script');
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action('admin_print_styles', 'print_emoji_styles');

/* wlwmanifestの出力を排除する */
remove_action('wp_head', 'wlwmanifest_link');

/* 外部ブログツールからの投稿を行う為の出力を排除する */
remove_action('wp_head', 'rsd_link');

/* 短縮URLの出力を排除する */
remove_action('wp_head', 'wp_shortlink_wp_head');

/* DNS Prefetchingの出力を排除する */
remove_action('wp_head', 'wp_resource_hints', 2);

/* RSSフィードの出力を排除する */
remove_action('wp_head', 'feed_links', 2);
remove_action('wp_head', 'feed_links_extra', 3);

/* ブロックエディタ（Gutenberg）用CSSの出力を排除する */
function remove_editor_style()
{
	wp_dequeue_style('wp-block-library');
}

global $wp_rewrite;
$wp_rewrite->flush_rules();


//=====================================
//    ドロワーメニューにページ内リンクがある場合
//=====================================
// カスタムリンクのURLを動的に変更するフィルターフック
function custom_menu_link_attributes($atts, $item, $args)
{
	if ($args->theme_location === 'drawer') {
		// カスタムリンクのみを対象とする
		if ($item->type === 'custom') {
			// ここでカスタムリンクのURLを変更する
			$href = $item->url;
			$hash_position = strpos($href, '#');
			if ($hash_position !== false) {
				$hash = substr($href, $hash_position);
				if (is_front_page()) {
					$atts['href'] = $hash;
				} else {
					$atts['href'] = esc_url(home_url('/')) . $hash;
				}
			}
		}
	}
	return $atts;
}
add_filter('nav_menu_link_attributes', 'custom_menu_link_attributes', 10, 3);


//=====================================
//    シングルページに見出し番号をつける
//=====================================
function add_heading_numbers($content)
{
	if (is_singular()) { // シングルページのみ
		// DOMDocumentを使ってHTMLを操作
		$doc = new DOMDocument();
		libxml_use_internal_errors(true); // エラーを無視しない設定
		$doc->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'));
		libxml_clear_errors();

		// 見出し番号のカウンタ
		$heading_numbers = [
			'h2' => 0,
			'h3' => 0,
			'h4' => 0
		];
		$prev_level = '';

		// 見出しタグを取得
		$headings = $doc->getElementsByTagName('*');
		foreach ($headings as $heading) {
			if (in_array($heading->nodeName, array_keys($heading_numbers))) {
				$level = $heading->nodeName;

				// 現在のレベルのカウンタを増加
				$heading_numbers[$level]++;

				// 番号の生成
				$numbering = '';
				for ($i = 2; $i <= $level[1]; $i++) {
					$numbering .= ($heading_numbers['h' . $i] ?: '0') . '-';
				}
				$numbering = rtrim($numbering, '-'); // 最後のハイフンを削除
				$numbering .= '.'; // ピリオドを追加

				// 見出しの内容に番号を追加
				$heading->nodeValue = $numbering . ' ' . $heading->nodeValue;

				// レベルを下げたらカウンタをリセット
				foreach ($heading_numbers as $key => &$value) {
					if ($key > $level) {
						$value = 0;
					}
				}

				// 前のレベルのカウンタのリセット処理
				if ($prev_level && $level < $prev_level) {
					foreach ($heading_numbers as $key => &$value) {
						if ($key > $level) {
							$value = 0;
						}
					}
				}
				$prev_level = $level;
			}
		}

		// HTMLを再生成して内容を返す
		$content = $doc->saveHTML();
	}
	return $content;
}
add_filter('the_content', 'add_heading_numbers');


//=====================================
// 投稿ページで目次を挿入
//=====================================
function generate_article_toc($content)
{
	// 目次を自動生成する条件を指定（投稿のみ）
	if (is_singular('post')) {
		// 目次を生成するHTMLを格納する変数
		$toc_html = '';

		// 目次の見出しを抽出するための正規表現パターン
		$pattern = '/<h([2-3]).*?>(.*?)<\/h[2-6]>/';

		// 投稿本文から目次の見出しを抽出し、目次を生成する
		if (preg_match_all($pattern, $content, $matches)) {
			$toc_html .= '<div class="p-entry-toc">';
			$toc_html .= '<div class="p-entry-toc__ttl">目次</div>'; // 目次のタイトルを追加
			$toc_html .= '<ul>';

			foreach ($matches[2] as $i => $title) {
				$heading_level = intval($matches[1][$i]);
				$title = urldecode($title);
				$slug = 'chapter-' . ($i + 1);

				// タイトルにIDを付与する
				$title_with_id = '<h' . $heading_level . ' id="' . $slug . '" class="p-entry__anchor">' . $title . '</h' . $heading_level . '>';

				// 目次のリンク先にタイトルを追加
				$toc_html .= '<li><a href="#' . $slug . '" class="';
				$toc_html .= ($heading_level === 2) ? 'p-toc__h2' : (($heading_level === 3) ? 'p-toc__h3' : 'p-toc__h' . $heading_level); // H2, H3, それ以外の目次項目に異なるクラスを適用
				$toc_html .= '">' . $title . '</a></li>';

				// タイトルを置換する
				$content = str_replace($matches[0][$i], $title_with_id, $content);
			}

			$toc_html .= '</ul>';
			$toc_html .= '</div>';
		}

		// 最初の見出しの前に目次を挿入
		$content = preg_replace('/<h[2-3].*?>/', $toc_html . '$0', $content, 1);
	}

	return $content;
}
add_filter('the_content', 'generate_article_toc');


//=====================================
//    投稿ページの画像出し分け
//=====================================
function img_to_multi($text)
{
	// 画像タグを抽出する正規表現パターン
	$pattern = '/<img\s+(.*?)src=[\'"](.*?)(\.png|\.jpg)[\'"](.*?)>/i';

	// 画像タグをすべて抽出
	preg_match_all($pattern, $text, $result);

	// 置換対象とする配列
	$target = array();
	$replace = array();

	foreach ($result[2] as $i => $img_base) {
		$img = $img_base . $result[3][$i];

		// 画像URLからWP上のIDを取得
		$id = attachment_url_to_postid($img);

		// WPにアップロードされた画像であれば処理を行う
		if ($id) {
			$target[] = 'src="' . $img . '"';
			$target[] = "src='" . $img . "'";

			// 最大幅840pxに合わせて画像サイズを設定
			$medium_img = wp_get_attachment_image_src($id, 'medium');
			$large_img = wp_get_attachment_image_src($id, 'large');
			$large_img[0] = $medium_img[0] = 'http://daymaga.local/wp-content/uploads/2024/07/tips-04-1.png'; // 840pxの画像URL

			$replace[] = 'srcset="' . $img . ' 840w, ' . $large_img[0] . ' 840w, ' . $medium_img[0] . ' 600w" sizes="(max-width: 600px) 100vw, (min-width: 601px) 80vw, 840px"';
			$replace[] = "srcset='" . $img . " 840w, " . $large_img[0] . " 840w, " . $medium_img[0] . " 600w' sizes='(max-width: 600px) 100vw, (min-width: 601px) 80vw, 840px'";
		}
	}

	// 置換処理
	$text = str_replace($target, $replace, $text);
	return $text;
}

// シングルページのみにフィルターを適用
function apply_img_to_multi_on_single($content)
{
	if (is_single()) {
		return img_to_multi($content);
	}
	return $content;
}

add_filter("the_content", "apply_img_to_multi_on_single");


//=====================================
//    TOPページすべての記事セクションの記事表示数変更
//=====================================
function set_screen_width_cookie()
{
?>
	<script type="text/javascript">
		function setPostsPerPage() {
			var screenWidth = window.innerWidth;
			var postsPerPage = screenWidth >= 950 ? 9 : 6;
			document.cookie = "posts_per_page=" + postsPerPage + "; path=/";
		}

		// 初回ロード時に設定
		setPostsPerPage();

		// 画面サイズ変更時に再設定
		window.onresize = function() {
			setPostsPerPage();
		};
	</script>
<?php
}
add_action('wp_head', 'set_screen_width_cookie');


//headerのスクロール前のロゴを呼び出す
function img_logo($image_name, $class = '', $aria_label = '') {
	$img = '<a class="' . esc_attr($class) . '" href="' . esc_url(home_url()) . '" aria-label="Home">';
	$img .= '<img src="' . esc_url(get_template_directory_uri() . '/assets/img/common/' . esc_attr($image_name)) . '" alt="' . esc_attr($aria_label) . '">';
	$img .= '</a>';
	return $img;
}