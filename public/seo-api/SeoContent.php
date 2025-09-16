<?php

class SeoContent {
    private string $title;
    private string $altText;

    public function __construct(string $title, string $altText) {
        $this->title = $title;
        $this->altText = $altText;
    }

    public function toArray(): array {
        return [
            'title' => $this->title,
            'altText' => $this->altText
        ];
    }
}