<?php
function shortcode_product_attributes($atts) {
    $atts = shortcode_atts(array(
        'id' => '', // Optional: truyền ID sản phẩm
    ), $atts);

    $product = null;

    // Nếu có ID thì lấy theo ID, ngược lại lấy sản phẩm hiện tại
    if (!empty($atts['id'])) {
        $product = wc_get_product($atts['id']);
    } else {
        global $product;
        if (!$product && is_product()) {
            $product = wc_get_product(get_the_ID());
        }
    }

    if (!$product) {
        return '<p>Không tìm thấy sản phẩm.</p>';
    }

    $attributes = $product->get_attributes();
    if (empty($attributes)) {
        return '<p>Sản phẩm không có thuộc tính hiển thị.</p>';
    }

    ob_start();
    echo '<ul class="product-attributes">';
    foreach ($attributes as $attribute) {
        if (!$attribute->get_visible()) continue;

        $label = wc_attribute_label($attribute->get_name());
        $value = '';

        if ($attribute->is_taxonomy()) {
            $terms = wp_get_post_terms($product->get_id(), $attribute->get_name(), array('fields' => 'names'));
            $value = implode(', ', $terms);
        } else {
            $value = implode(', ', $attribute->get_options());
        }

        echo '<li><strong>' . esc_html($label) . ':</strong> ' . esc_html($value) . '</li>';
    }
    echo '</ul>';

    return ob_get_clean();
}
add_shortcode('product_attrs', 'shortcode_product_attributes');
