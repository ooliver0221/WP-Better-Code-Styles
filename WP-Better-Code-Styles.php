<?php
/*
Plugin Name: WP-Better-Code-Styles
Plugin URI: https://github.com/ooliver0221/WP-Better-Code-Styles
Description: 洛谷风格内联代码美化 与 Code Block Pro 圆角优化。
Version: 1.2
Author: ooliver
*/

// 防止直接访问
if (!defined('ABSPATH')) {
    exit;
}

/**
 * 将样式强行注入到页面头部，优先级设为 9999 以确保覆盖主题样式
 */
add_action('wp_head', function() {
    ?>
    <style type="text/css">
        /* ============================================================
           1. 内联代码美化
           ============================================================ */
        html body :not(pre) > code {
            /* 固定颜色：洛谷风格深灰色/红色 */
            color: #e74c3c !important; 
            background-color: rgba(231, 76, 60, 0.08) !important;
            
            /* 字体排版 */
            font-family: Consolas, Monaco, "Courier New", monospace !important;
            font-size: 0.9em !important;
            font-weight: bold !important;
            word-break: break-all !important;
            
            /* 核心修改：加大背景 */
            padding: 3px 8px !important;
            border-radius: 6px !important;
            margin: 0 4px !important;
            
            /* 清除默认样式并增加极细边框 */
            box-shadow: none !important;
            border: 1px solid rgba(231, 76, 60, 0.15) !important;
            display: inline !important;
        }

        /* ============================================================
           2. Code Block Pro 自动圆角
           ============================================================ */
        html body div[class*="code-block-pro"],
        html body .wp-block-kevin-batdorf-code-block-pro {
            border-radius: 12px !important;
            overflow: hidden !important;
            border: 1px solid rgba(0, 0, 0, 0.08) !important;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.05) !important;
            margin-bottom: 1.6em !important;
            transform: translateZ(0);
        }

        /* 内部 pre 标签圆角 */
        html body div[class*="code-block-pro"] pre {
            border-radius: 12px !important;
            border: none !important;
            margin: 0 !important;
        }

        /* 标题栏圆角 */
        html body div[class*="code-block-pro"] .cbp-header {
            border-radius: 12px 12px 0 0 !important;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05) !important;
        }

        /* 底部栏圆角 */
        html body div[class*="code-block-pro"] .cbp-footer {
            border-radius: 0 0 12px 12px !important;
            border-top: 1px solid rgba(0, 0, 0, 0.05) !important;
        }

        /* ============================================================
           3. 冲突修复 (防止内联样式影响代码块内部)
           ============================================================ */
        html body div[class*="code-block-pro"] pre code {
            background: transparent !important;
            color: inherit !important;
            padding: 0 !important;
            border-radius: 0 !important;
            margin: 0 !important;
            border: none !important;
            font-weight: normal !important;
            display: inline !important;
        }
    </style>
    <?php
}, 9999); // 9999 优先级确保它是最后一个加载的样式
