<?php

namespace ClaraPressToc;

use TOC\MarkupFixer;
use TOC\TocGenerator;

class TableOfContents
{
    const TOC_SHORTCODE = 'clarapresstoc';

    /**
     * Build the table of contents and output it, using the WordPress load_template() method
     *
     * @param array $attributes Shortcode attributes.
     * @param string|null $content Content inside the shortcode (if any).
     * @param string $shortcode_tag Shortcode tag (name).
     *
     * @return string The table of contents HTML.
     */
    public static function load_toc(array $attributes, ?string $content, string $shortcode_tag): string
    {
        if (empty($content)) {
            $content = get_the_content();
        }

        if (empty($content)) {
            return '';
        }

        // This ensures that all header tags have `id` attributes so they can be used as anchor links
        $fixer = new MarkupFixer();
        $content = $fixer->fix($content);

        /** @var TocGenerator $toc_content */
        $generator = new TocGenerator();
        $toc_html = $generator->getHtmlMenu($content);

        ob_start();
        $tpl_args = ['toc' => $toc_html];
        load_template(CLARAPRESS_TOC_PLUGIN_DIR . 'templates/toc.tpl.php', false, $tpl_args);

        return ob_get_clean();
    }

    public static function fix_content_by_adding_anchors(): string
    {
        $post_content = get_the_content();

        if (has_shortcode($post_content, self::TOC_SHORTCODE)) {
            // This ensures that all header tags have `id` attributes so they can be used as anchor links
            $fixer = new MarkupFixer();
            $post_content = $fixer->fix($post_content);

            unset($fixer);
        }

        return $post_content;
    }
}
