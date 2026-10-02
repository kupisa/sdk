<?php

declare(strict_types=1);

namespace themes\starter\blocks\banner;

use frontend\blocks\Block;
use frontend\blocks\BlockGroup;
use frontend\blocks\Link;
use Yii;

/**
 * One line of text on the accent colour across the width of the page, e.g. "Free delivery on orders over 50 €",
 * with a link after it when one is filled in. The block of the starter theme: it shows how a theme brings a
 * block of its own, built like any block of the site (see {@see Block}) and styled in the stylesheet of the
 * theme (`public/theme.css`).
 */
class BannerBlock extends Block
{
    /**
     * The text of the banner.
     */
    public string $text = '';

    /**
     * A link after the text, shown when it is filled in (see {@see Link}).
     */
    public array $link = Link::EMPTY;

    /**
     * {@inheritdoc}
     */
    public static function label(): string
    {
        return Yii::t('starter', 'Banner');
    }

    /**
     * {@inheritdoc}
     */
    public static function group(): BlockGroup
    {
        return BlockGroup::Sections;
    }

    /**
     * {@inheritdoc}
     */
    public static function icon(): string
    {
        return 'bullhorn';
    }

    /**
     * {@inheritdoc}
     */
    public static function controls(): array
    {
        return [
            ['name' => 'text', 'type' => 'text', 'label' => Yii::t('starter', 'Text')],
            ['name' => 'link', 'type' => 'link', 'label' => Yii::t('starter', 'Link')],
        ];
    }

    /**
     * {@inheritdoc}
     *
     * The banner sits tight against what is around it.
     */
    public static function spacing(): string
    {
        return 'none';
    }

    /**
     * {@inheritdoc}
     */
    public static function padding(): string
    {
        return 'none';
    }

    /**
     * {@inheritdoc}
     */
    public function run(): string
    {
        return $this->render('banner', [
            'text' => $this->text,
            'link' => Link::html($this->link),
        ]);
    }
}
