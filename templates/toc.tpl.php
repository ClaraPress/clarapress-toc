<?php
$injected_args = $args;
$toc_content = $json_ld = '';
if (isset($injected_args['toc'])) {
    $toc_content = $injected_args['toc'];
}
if (isset($injected_args['json_ld'])) {
    $json_ld = $injected_args['json_ld'];
}

// Apply the filter to allow overriding the summary text
$summary_text = apply_filters('clarapress_toc_summary_text', __('Open Table of contents', 'clarapress-toc'));
?>
<div class="clarapress-toc-wrapper">
    <details>
        <summary><?php echo esc_html($summary_text); ?></summary>
        <div class='clarapress-toc-items'>
            <?php echo wp_kses_post($toc_content); ?>
        </div>
    </details>
</div>
<?php echo wp_kses_post($json_ld); ?>
