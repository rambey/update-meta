<?php

    $begin_time = array_sum(explode(' ', microtime()));
    @ini_set('max_execution_time', -1);
    @ini_set('memory_limit', '256M');
    @set_time_limit(0);

    require 'config/config.inc.php';
    require 'init.php';
    
    $id_lang = (int)Context::getContext()->language->id;
    $start = 0;
    $limit = 1000000;
    $order_by = 'id_product';
    $order_way = 'DESC';
    $id_category = false;
    $only_active = true;
    $context = null;

    $all_products = Product::getProducts($id_lang, $start, $limit, $order_by, $order_way, $id_category,
            $only_active, $context);
    foreach ($all_products as $product) {
        $product_obj = new Product($product["id_product"]);
        $current_catgeorie = new Category($product_obj->id_category_default);
		$product_title = ucwords(strtolower($product_obj->name[$id_lang]));
        $manufacturer_name = (Manufacturer::getNameById($product_obj->id_manufacturer))? Manufacturer::getNameById($product_obj->id_manufacturer):'Qiriness';
        $meta_description_fixed_content = ($id_lang == 1)?"Adoptez les soins Qiriness, des produits et des rituels beauté qui révéleront toute la beauté de votre peau." : "Adopt Qiriness treatments, beauty products and rituals that will reveal all the beauty of your skin.";
        $product_obj->meta_title = $product_title.', '.$current_catgeorie->getName($id_lang).' | Qiriness';
        $product_obj->meta_description = $current_catgeorie->getName($id_lang).' '. $manufacturer_name .": ".$product_title.'. '.$meta_description_fixed_content;
		$product_obj->update();
    }
    
    $end_time = array_sum(explode(' ', microtime()));
    echo 'Le temps d\'exécution est '.($end_time - $begin_time);
    die();