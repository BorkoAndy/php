<?php
class ContextBuilder {
    public function build($labels, $domain) {
        $labelText = implode(', ', $labels);
        return "Generate an SEO-friendly title and alt-text for an image from {$domain} with labels: {$labelText}.";
    }
}