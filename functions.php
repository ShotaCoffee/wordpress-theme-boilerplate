<?php
// NOTE デフォルトの不要なjsファイル cssファイルの読み込みを消す
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');
// NOTE CSS読み込み
function theme_style() {
  if ( ! is_admin() ) {
    wp_enqueue_style( 'main-style', get_template_directory_uri() . '/build/styles/main.css' );
  }
}

add_action( 'wp_enqueue_scripts', 'theme_style', 11 );
// NOTE JS読み込み
function theme_script()
{
  if (!is_admin()) {
    wp_deregister_script('jquery'); // NOTE jquery消す
    // FIXME jsのパス変更
    wp_enqueue_script('my_script', get_template_directory_uri() . '/build/scripts/main.js', '', '', true);
  }
}

add_action('wp_enqueue_scripts', 'theme_script');
// NOTE 投稿にサムネイルを追加できるようにする
add_theme_support('post-thumbnails');
// NOTE タイトルタグいい感じに生成
add_theme_support('title-tag');

// NOTE ナビゲーション定義
if (!function_exists('custom_menu')):
  function custom_menu()
  {
    register_nav_menus(array(
      'global_navigation' => 'グローバルナビゲーション',
    ));
  }
endif;
add_action('after_setup_theme', 'custom_menu');

// NOTE 画像のsrcset属性を削除する
add_filter('wp_calculate_image_srcset_meta', '__return_null');


// NOTE metaタグの生成
function get_meta_description()
{
  global $post;
  $description = "";
  if (is_home() || is_category()) {
    // ホームでは、ブログの説明文を取得
    $description = get_bloginfo('description');
  } elseif (is_single()) {
    if ($post->post_excerpt) {
      // 記事ページでは、記事本文から抜粋を取得
      $description = $post->post_excerpt;
    } else {
      // post_excerpt で取れない時は、自力で記事の冒頭100文字を抜粋して取得
      $description = strip_tags($post->post_content);
      $description = str_replace("\n", "", $description);
      $description = str_replace("\r", "", $description);
      $description = mb_substr($description, 0, 100) . "...";
    }
  }
  return $description;
}

// echo meta description tag
function echo_meta_description_tag()
{
  if (is_home() || is_category() || is_single()) {
    echo '<meta name="description" content="' . get_meta_description() . '" />' . "\n";
  }
}