<?php

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Infrastructure\Crawler;

use App\Infrastructure\Crawler\Model\Url;
use DateTime;
use RuntimeException;
use Symfony\Component\DomCrawler\Crawler;

final class DocumentParser
{
    private const NEWLINE = "\n";

    /**
     * Parses a url node.
     */
    public function parseUrl(Crawler $node, string $attr = 'href'): ?string
    {
        if (0 === $node->count()) {
            return null;
        }

        if ($url = $node->attr($attr)) {
            return $url;
        }

        return null;
    }

    /**
     * Parses an image node.
     */
    public function parseImage(Crawler $node): ?Url
    {
        $src = $this->parseUrl($node, 'src');

        if (!$src) {
            return null;
        }

        return Url::fromString($src);
    }

    /**
     * Parses a string node.
     */
    public function parseString(Crawler $node, bool $multiline = false): ?string
    {
        if (0 === $node->count()) {
            return null;
        }

        $content = $this->extractString($node, $multiline);

        if (!$multiline) {
            $content = preg_replace('/[\r\n]/', '', $content);
        }

        return $this->cleanString($content);
    }

    /**
     * @throws ParseException
     */
    public function parseDate(Crawler $node): DateTime
    {
        $text = $this->parseString($node);

        preg_match('/(\d+\.\d+.\d+).+(\d+:\d+)/', (string) $text, $match);

        if (3 !== \count($match)) {
            throw new ParseException(sprintf('Error parsing date string "%s"', $text));
        }

        $date = DateTime::createFromFormat('d.m.y H:i', $match[1].' '.$match[2]);

        if (false === $date) {
            throw new RuntimeException(sprintf('Could not convert %s to date object', $text));
        }

        return $date;
    }

    private function extractString(Crawler $node, bool $parseHtml = false): ?string
    {
        $content = $node->attr('content');

        if (null !== $content) {
            return $content;
        }

        if (!$parseHtml) {
            return $node->text();
        }

        $content = $node->html();
        $content = (string) preg_replace('/<p[^>]*?>/', '', $content);
        $content = (string) str_replace('</p>', static::NEWLINE, $content);

        return (string) preg_replace('/<br\s?\/?>/i', static::NEWLINE, $content);
    }

    private function cleanString(string $content): string
    {
        $content = strip_tags($content);
        $content = str_replace("''", '"', $content);
        $content = str_replace(' ,', ', ', $content);
        $content = preg_replace('/\s\s+/', ' ', $content);

        return trim($content);
    }
}
