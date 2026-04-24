<?php
/*
Plugin Name: WP-Better-Code-Styles
Plugin URI: https://github.com/ooliver0221/WP-Better-Code-Styles
Description: 洛谷风格内联代码美化 与 Code Block Pro 圆角优化。
Version: 1.1
Author: ooliver
*/

if (!defined('ABSPATH')) exit;

add_action('wp_head', function() {
    ?>
    <style>
        /* 1. 内联代码美化 - 固定颜色版 */
        :not(pre) > code {
            /* 颜色修改区：这里改成了固定的深灰色和浅灰背景 */
            color: #476582 !important; 
            background-color: rgba(27, 31, 35, 0.05) !important;
            
            font-family: Consolas, Monaco, "Courier New", monospace !important;
            font-size: 0.9em !important;
            font-weight: 600 !important;
            word-break: break-all !important;
            
            /* 核心修改：加大背景 */
            padding: 4px 8px !important;
            border-radius: 6px !important;
            margin: 0 4px !important;
            
            box-shadow: none !important;          
            border: none !important;              
        }

        /* 2. Code Block Pro 整体圆角优化 */
        .code-block-round {
            border-radius: 12px !important;
            overflow: hidden !important;
            border: 1px solid rgba(0, 0, 0, 0.1) !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
        }

        .code-block-round pre {
            border-radius: 12px !important;
        }

        /* 顶部标题栏圆角 */
        .code-block-round .cbp-header {
            border-top-left-radius: 12px !important;
            border-top-right-radius: 12px !important;
        }

        /* 底部栏圆角 */
        .code-block-round .cbp-footer {
            border-bottom-left-radius: 12px !important;
            border-bottom-right-radius: 12px !important;
        }
    </style>
    <?php
}, 100);