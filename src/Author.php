<?php # -*- coding: utf-8 -*-

/*
 * This file is part of the WordPress Theme Model Theme package.
 *
 * (c) Guido Scialfa <dev@guidoscialfa.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace WordPressModel;

use WordPressModel\Attribute\ClassAttribute;
use Widoz\Bem\BemPrefixed;

/**
 * Author Model
 */
final class Author implements Model
{
    private const FILTER_DATA = 'wordpressmodel.post_author';

    /**
     * @var \WP_User
     */
    private $user;

    /**
     * Author constructor.
     *
     * @param \WP_User $user
     */
    public function __construct(\WP_User $user)
    {
        $this->user = $user;
    }

    /**
     * @return array
     */
    public function data(): array
    {
        if (! $this->user->exists()) {
            return [];
        }

        $authorPostsUrl = get_author_posts_url($this->user->ID);

        /**
         * Author
         *
         * @param array $data The data arguments for the template.
         */
        return apply_filters(self::FILTER_DATA, [
            'name' => $this->user->display_name,
            'attributes' => [
                'class' => (new ClassAttribute(new BemPrefixed('author')))->value(),
            ],
            'link' => [
                'attributes' => [
                    'href' => $authorPostsUrl,
                    'class' => (new BemPrefixed('author', 'posts-page'))->value(),
                ],
            ],
        ]);
    }
}