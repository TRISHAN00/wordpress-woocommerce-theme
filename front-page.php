<?php
get_header();

get_template_part('template-parts/home/banner');

render_product_section('Top Sell Products', 'top-sell-products', 8);
render_product_section('Al Fakher', 'al-fakher', 8);
render_product_section('Myle', 'myle-dubai', 8);
render_product_section('Heets & Tera', 'heets-tera', 8);
render_product_section('Disposable Vape', 'disposable-vape', 8);
// render_product_section('E-Juice', 'e-juice', 8);
render_product_section('Device & Pods System', 'device-pods-system', 8);

get_footer();
?>
