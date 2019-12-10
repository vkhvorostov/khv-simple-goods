<?php
/*
Plugin Name: Простые товары
Description: Плагин добавляет простые товары и их категории
Version: 0.1
Author: Vladimir Khvorostov
Author URI: http://khvorostov.ru
*/

$khvSimpleGoods = new KhvSimpleGoods();

class KhvSimpleGoods
{


    function __construct()
    {
        add_action( 'init', array($this, 'registerPostTypes') );
        add_filter( 'post_type_link', array($this, 'catalogPermalink'), 1, 2 );
    }


    public function registerPostTypes() {

        register_taxonomy('productcat', array('product'), array(
            'labels'                => array(
                'name'              => 'Категории товаров',
                'singular_name'     => 'Категория товара',
                'search_items'      => 'Искать категории товаров',
                'all_items'         => 'Все категории товаров',
                'parent_item'       => 'Родит. категория товара',
                'parent_item_colon' => 'Родит. категория товара:',
                'edit_item'         => 'Ред. категорию товара',
                'update_item'       => 'Обновить категорию товара',
                'add_new_item'      => 'Добавить категорию товара',
                'new_item_name'     => 'Новая категория товара',
                'menu_name'         => 'Категории товаров',
            ),
            'public'                => true,
            'show_in_nav_menus'     => false,
            'show_ui'               => true,
            'show_tagcloud'         => false,
            'hierarchical'          => true,
            'rewrite'               => array('slug'=>'catalog', 'hierarchical'=>false, 'with_front'=>false, 'feed'=>false ),
            'show_admin_column'     => true,
        ) );

        $args = array(
            'labels' => array(
                'name' => 'Товар',
                'singular_name' => 'Товар',
                'add_new' => 'Добавить товар',
                'add_new_item' => 'Добавление товара',
                'edit_item' => 'Редактирование товара',
                'new_item' => 'Новый товар',
                'view_item' => 'Смотреть товар',
                'search_items' => 'Искать товары',
                'not_found' => 'Товары не найдены',
                'not_found_in_trash' => 'Товары не найденыв корзине',
                'parent_item_colon' => '',
                'menu_name' => 'Товары',
            ),
            'description' => '',
            'public' => true,
            'show_in_menu' => true,
            'menu_position' => 6,
            'menu_icon' => 'dashicons-cart',
            'hierarchical' => false,
            'supports' => array( 'title', 'editor', 'thumbnail' ),
            'taxonomies' => array( 'productcat' ),
            'has_archive' => 'catalog',
            'rewrite' => array( 'slug'=>'catalog/%productcat%', 'with_front'=>false, 'pages'=>false, 'feeds'=>false, 'feed'=>false )
        );
        register_post_type( 'product', $args );
    }


    public function catalogPermalink($permalink, $post) {
        // выходим если это не наш тип записи: без холдера %productcat%
        if( strpos($permalink, '%productcat%') === false )
            return $permalink;

        // Получаем элементы таксономии
        $terms = get_the_terms($post, 'productcat');
        // если есть элемент заменим холдер
        if( ! is_wp_error($terms) && !empty($terms) && is_object($terms[0]) )
            $term_slug = array_pop($terms)->slug;
        // элемента нет, а должен быть...
        else
            $term_slug = 'no-productcat';

        return str_replace('%productcat%', $term_slug, $permalink );
    }


}

