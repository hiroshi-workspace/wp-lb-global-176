<?php
function shortcode_product_attributes_full($atts)
{
    $atts = shortcode_atts(array(
        'id' => '',
    ), $atts);

    $product = null;

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

    $product_id = $product->get_id();
    ob_start();

    echo '<ul class="product-attributes">';

    // ✅ Mã sản phẩm (SKU)
    $sku = $product->get_sku();
    $sku_display = $sku ? esc_html($sku) : 'Đang cập nhật...';
    echo '<li><strong>Mã sản phẩm:</strong> ' . $sku_display . '</li>';

    // ✅ Danh mục
    $categories = wp_get_post_terms($product_id, 'product_cat', array('fields' => 'names'));
    if (!empty($categories)) {
        echo '<li><strong>Danh mục:</strong> ' . esc_html(implode(', ', $categories)) . '</li>';
    }

    // ✅ Thương hiệu (đổi taxonomy nếu khác)
    $brands = wp_get_post_terms($product_id, 'product_brand', array('fields' => 'names'));
    if (!empty($brands)) {
        echo '<li><strong>Thương hiệu:</strong> ' . esc_html(implode(', ', $brands)) . '</li>';
    }

    // ✅ Thuộc tính hiển thị
    $attributes = $product->get_attributes();
    if (!empty($attributes)) {
        foreach ($attributes as $attribute) {
            if (!$attribute->get_visible()) continue;

            $label = wc_attribute_label($attribute->get_name()) == wc_attribute_label($attribute->get_name()) ? 'Phân khúc' : 'Loại sản phẩm';
            $value = '';

            if ($attribute->is_taxonomy()) {
                $terms = wp_get_post_terms($product_id, $attribute->get_name(), array('fields' => 'names'));
                $value = implode(', ', $terms);
            } else {
                $value = implode(', ', $attribute->get_options());
            }

            echo '<li><strong>' . esc_html($label) . ':</strong> ' . esc_html($value) . '</li>';
        }
    }

    echo '</ul>';
    return ob_get_clean();
}
add_shortcode('product_attrs_full', 'shortcode_product_attributes_full');
