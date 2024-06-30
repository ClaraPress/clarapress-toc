<?php
$injected_args = $args;
$toc_content = '';
if (isset($injected_args['toc'])) {
    $toc_content = $injected_args['toc'];
}

// Apply the filter to allow overriding the summary text
$summary_text = apply_filters('clarapress_toc_summary_text', __('Open Table of contents', 'clarapress-toc'));
?>
<div class="clarapress-toc-wrapper">
    <details>
        <summary><?php echo esc_html($summary_text); ?></summary>
        <div class='clarapress-toc-items'>
            <?php echo $toc_content; ?>
        </div>
    </details>
</div>
