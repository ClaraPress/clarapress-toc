<?php
$injected_args = $args;
$toc_content = '';
if (isset($injected_args['toc'])) {
    $toc_content = $injected_args['toc'];
}
?>
<div class="clarapress-toc-wrapper">
    <details>
        <summary>Open Table of contents</summary>
        <div class='clarapress-toc-items'>
            <?php echo $toc_content;?>
        </div>
    </details>
</div>
