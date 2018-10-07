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
 * Comment Section Title Model
 */
final class CommentSectionTitle implements Model
{
    private const FILTER_DATA = 'wordpressmodel.comments_section_title';

    /**
     * @return array
     */
    public function data(): array
    {
        if (!in_the_loop()) {
            return [];
        }

        /**
         * Comment Section Title
         *
         * @param array $data The data arguments for the template.
         */
        return apply_filters(self::FILTER_DATA, [
            'title' => $this->title(),
            'attributes' => [
                'heading' => (new ClassAttribute(new BemPrefixed('comments', 'title')))
                    ->value(),
                'commentsForTitle' => (new ClassAttribute(new BemPrefixed('comments-for')))
                    ->value(),
            ],
        ]);
    }

    /**
     * @return string
     */
    private function title(): string
    {
        $commentsNumber = $this->commentsNumber();
        $postTitle = get_the_title();

        $title = sprintf(
            '%1$s %2$s',
            esc_html(
                // translators: %d is the number of responses
                _n('%d response to', '%d responses to', $commentsNumber, 'wordpress-model')
            ),
            '<span class="{{ attributes.class.commentsForTitle }}">' . $postTitle . '</span>'
        );

        !$postTitle and $title = esc_html(
            // translators: %d is the number of responses for the post
            _n('%d response', '%d responses', $commentsNumber, 'wordpress-model')
        );

        return $title;
    }

    /**
     * @return string
     */
    private function commentsNumber(): string
    {
        // Filter is applied to the returned value, we cannot be sure a string is returned.
        return (string)number_format_i18n(get_comments_number());
    }
}