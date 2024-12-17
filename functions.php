<?php

function add_cors_headers() {
    // Перевірка, чи є заголовок Origin у запиті
    if (isset($_SERVER['HTTP_ORIGIN']) && $_SERVER['HTTP_ORIGIN'] === 'http://localhost:5173') {
        header("Access-Control-Allow-Origin: http://localhost:5173");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type");
    }

    // Для запитів типу OPTIONS (для попереднього запиту CORS)
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type');
        exit(0); // Виходимо після обробки OPTIONS запиту
    }
}

add_action('init', 'add_cors_headers');

// Функція для підключення скриптів і стилів Vue.js
function enqueue_vue_comments_app() {
    if (is_page_template('comments-page-template.php')) {
        
        wp_enqueue_script(
            'vue-comments-app',
            'http://localhost:5173/assets/index.js',
            array(),
            null,
            true
        );

        wp_enqueue_style(
            'vue-comments-style',
            'http://localhost:5173/assets/index.css',
            array(),
            null
        );
    }
}

add_action('wp_enqueue_scripts', 'enqueue_vue_comments_app');

// Функція для отримання коментарів до поста
function get_comments_for_post() {
    if (isset($_GET['post_id'])) {
        $post_id = intval($_GET['post_id']);
        if (!get_post($post_id)) {
            wp_send_json_error(array('message' => 'Post not found.'));
            return;
        }

        $comments = get_comments(array(
            'post_id' => $post_id,
            'status'  => 'approve',
        ));

        if (!empty($comments)) {
            $comments_data = array();
            foreach ($comments as $comment) {
                $comments_data[] = array(
                    'id'      => $comment->comment_ID,
                    'content' => $comment->comment_content,
                    'author'  => $comment->comment_author,
                    'email'   => $comment->comment_author_email,
                    'date'    => $comment->comment_date,
                );
            }
            wp_send_json_success($comments_data);
        } else {
            wp_send_json_error(array('message' => 'No comments found.'));
        }
    } else {
        wp_send_json_error(array('message' => 'Post ID not provided.'));
    }
}

add_action('wp_ajax_get_comments', 'get_comments_for_post');
add_action('wp_ajax_nopriv_get_comments', 'get_comments_for_post');

function delete_comment() {
    // Перевіряємо, чи є ID коментаря
    if (!isset($_POST['comment_id']) || empty($_POST['comment_id'])) {
        wp_send_json_error(array('message' => 'Comment ID is required.'));
        return;
    }

    $comment_id = intval($_POST['comment_id']); // Приведення до цілого числа

    // Перевіряємо, чи існує коментар
    $comment = get_comment($comment_id);
    if (!$comment) {
        wp_send_json_error(array('message' => 'Comment not found.'));
        return;
    }

    // Видаляємо коментар
    $result = wp_delete_comment($comment_id, true); // true означає примусове видалення
    if ($result) {
        wp_send_json_success(array('message' => 'Comment deleted successfully.'));
    } else {
        wp_send_json_error(array('message' => 'Error occurred while deleting the comment.'));
    }
}

// Реєструємо обробник AJAX для авторизованих і неавторизованих користувачів
add_action('wp_ajax_delete_comment', 'delete_comment');
add_action('wp_ajax_nopriv_delete_comment', 'delete_comment');



