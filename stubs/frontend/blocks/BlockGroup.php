<?php

declare(strict_types=1);

namespace frontend\blocks;

use Yii;

/**
 * The groups the page editor sorts the blocks into when a block is added to a page. Every block belongs to
 * one of them, see {@see Block::group()}. The editor lists the groups in the order they are declared here.
 */
enum BlockGroup: string
{
    /**
     * The block the model of the assistant writes from what the user asks for (see
     * `frontend\blocks\generated\GeneratedBlock`). The editor lists it first, right under the search, without
     * the heading of a group.
     */
    case Generated = 'generated';

    case Text = 'text';
    case Media = 'media';

    /**
     * The sections a presentation page is built from: a hero, the features of the site, a gallery, ...
     */
    case Sections = 'sections';

    /**
     * The fields of a form (see the form module): a page of a form is built from them, and no other page.
     * Empty while the site has no module that brings any.
     */
    case Form = 'form';

    /**
     * What does not fit elsewhere: a piece of HTML, CSS or JavaScript of the site's own.
     */
    case Other = 'other';

    /**
     * The parts every page of the site shares, such as the header and the footer.
     */
    case Site = 'site';

    /**
     * The name of the group as shown in the page editor.
     */
    public function label(): string
    {
    }

    /**
     * The label of every group, keyed by its value, in the order the editor shows them; or of the groups given,
     * in their order (the groups a kind of page takes, see `Page::blockGroups()`).
     *
     * @param list<self>|null $groups
     * @return array<string, string>
     */
    public static function labels(array|null $groups = null): array
    {
    }
}
