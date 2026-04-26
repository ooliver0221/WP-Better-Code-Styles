<?php
/*
Plugin Name: WP-Better-Code-Styles
Plugin URI: https://github.com/ooliver0221/WP-Better-Code-Styles
Description: 洛谷风格内联代码美化 与 Code Block Pro 圆角优化。
Version: 1.3
Author: ooliver
*/

// 防止直接访问
if (!defined('ABSPATH')) {
    exit;
}

/**
 * 1. 创建后台菜单
 */
add_action('admin_menu', function() {
    add_options_page(
        'WP-Better-Code-Styles 代码样式设置',          // 页面标题
        'WP-Better-Code-Styles 代码样式设置',          // 菜单标题
        'manage_options',       // 权限
        'cbp-style-settings',   // 菜单别名
        'cbp_settings_page_html' // 回调函数
    );
});

/**
 * 2. 注册设置项
 */
add_action('admin_init', function() {
    register_setting('cbp_settings_group', 'cbp_inline_color');
    register_setting('cbp_settings_group', 'cbp_inline_bg');
    register_setting('cbp_settings_group', 'cbp_inline_radius'); // 新增：内联圆角
    register_setting('cbp_settings_group', 'cbp_block_radius');  // 改名：代码块圆角
});

/**
 * 3. 引入 WordPress 原生颜色选择器脚本
 */
add_action('admin_enqueue_scripts', function($hook) {
    if ($hook !== 'settings_page_cbp-style-settings') return;
    wp_enqueue_style('wp-color-picker');
    wp_enqueue_script('cbp-picker-js', '', array('wp-color-picker'), false, true);
    add_action('admin_footer', function() {
        echo '<script>jQuery(function($){ $(".color-picker").wpColorPicker(); });</script>';
    });
});

/**
 * 4. 设置页面 HTML 结构
 */
function cbp_settings_page_html() {
    // 获取保存的值或设定默认值
    $inline_color  = get_option('cbp_inline_color', '#e74c3c');
    $inline_bg     = get_option('cbp_inline_bg', 'rgba(231, 76, 60, 0.08)');
    $inline_radius = get_option('cbp_inline_radius', '6');  // 默认 6px
    $block_radius  = get_option('cbp_block_radius', '12'); // 默认 12px
    ?>
    <div class="wrap">
        <h1>代码样式优化设置</h1>
        <hr>
        <form method="post" action="options.php">
            <?php settings_fields('cbp_settings_group'); ?>
            
            <h2>1. 内联代码样式 (Inline Code)</h2>
            <table class="form-table">
                <tr>
                    <th scope="row">文字颜色</th>
                    <td><input type="text" name="cbp_inline_color" value="<?php echo esc_attr($inline_color); ?>" class="color-picker" /></td>
                </tr>
                <tr>
                    <th scope="row">背景颜色</th>
                    <td>
                        <input type="text" name="cbp_inline_bg" value="<?php echo esc_attr($inline_bg); ?>" class="color-picker" />
                        <p class="description">推荐使用带透明度的 RGBA 或极浅的 HEX 颜色。</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">内联圆角大小 (px)</th>
                    <td>
                        <input type="number" name="cbp_inline_radius" value="<?php echo esc_attr($inline_radius); ?>" /> px
                        <p class="description">建议设置在 4-8px 之间。</p>
                    </td>
                </tr>
            </table>

            <h2>2. 代码块样式 (Code Block Pro)</h2>
            <table class="form-table">
                <tr>
                    <th scope="row">代码块圆角大小 (px)</th>
                    <td>
                        <input type="number" name="cbp_block_radius" value="<?php echo esc_attr($block_radius); ?>" /> px
                        <p class="description">控制整体容器、标题栏和底部栏的圆角。</p>
                    </td>
                </tr>
            </table>

            <?php submit_button('保存设置'); ?>
        </form>
    </div>
    <?php
}

/**
 * 5. 前台输出动态生成的 CSS
 */
add_action('wp_head', function() {
    $i_color  = get_option('cbp_inline_color', '#e74c3c');
    $i_bg     = get_option('cbp_inline_bg', 'rgba(231, 76, 60, 0.08)');
    $i_radius = get_option('cbp_inline_radius', '6');
    $b_radius = get_option('cbp_block_radius', '12');
    ?>
    <style type="text/css">
        /* --- 内联代码美化 --- */
        html body :not(pre) > code {
            color: <?php echo $i_color; ?> !important; 
            background-color: <?php echo $i_bg; ?> !important;
            font-family: Consolas, Monaco, "Courier New", monospace !important;
            font-size: 0.9em !important;
            font-weight: bold !important;
            padding: 3px 8px !important;
            border-radius: <?php echo $i_radius; ?>px !important; /* 独立圆角 */
            margin: 0 4px !important;
            border: 1px solid <?php echo $i_color; ?>26 !important; /* 15% 透明度边框 */
            box-shadow: none !important;
            word-break: break-all !important;
        }

        /* --- Code Block Pro 自动圆角 --- */
        html body div[class*="code-block-pro"],
        html body .wp-block-kevin-batdorf-code-block-pro {
            border-radius: <?php echo $b_radius; ?>px !important;
            overflow: hidden !important;
            border: 1px solid rgba(0, 0, 0, 0.08) !important;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.05) !important;
            margin-bottom: 1.6em !important;
            transform: translateZ(0);
        }

        html body div[class*="code-block-pro"] pre {
            border-radius: <?php echo $b_radius; ?>px !important;
            margin: 0 !important;
        }

        html body div[class*="code-block-pro"] .cbp-header {
            border-radius: <?php echo $b_radius; ?>px <?php echo $b_radius; ?>px 0 0 !important;
        }

        html body div[class*="code-block-pro"] .cbp-footer {
            border-radius: 0 0 <?php echo $b_radius; ?>px <?php echo $b_radius; ?>px !important;
        }

        /* 防止内联样式污染代码块内部 */
        html body div[class*="code-block-pro"] pre code {
            background: transparent !important;
            color: inherit !important;
            padding: 0 !important;
            border-radius: 0 !important;
            font-weight: normal !important;
            border: none !important;
        }
    </style>
    <?php
}, 9999);
