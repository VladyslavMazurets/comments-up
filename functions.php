<?php
function enqueue_vue_comments_app() {
    // Перевіряємо, чи використовуються шаблон для коментарного додатку
    if (is_page_template('comments-page-template.php')) {
        
        // Підключаємо JavaScript файл Vue
        wp_enqueue_script(
            'vue-comments-app',
            'http://localhost:5173/assets/index.js', // Шлях до JavaScript файлу, що створюється Vite
            array(),
            null,
            true
        );

        // Підключаємо CSS файл Vue
        wp_enqueue_style(
            'vue-comments-style',
            'http://localhost:5173/assets/index.css', // Шлях до CSS файлу, що створюється Vite
            array(),
            null
        );
    }
}
add_action('wp_enqueue_scripts', 'enqueue_vue_comments_app');

