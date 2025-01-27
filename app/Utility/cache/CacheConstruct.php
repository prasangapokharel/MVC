<?php

namespace Godsu\Mvc\Utility\Cache;

use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class CacheConstruct
{
    private static $cache;

    /**
     * Initialize or return the cache instance.
     */
    public static function createCache()
    {
        if (!self::$cache) {
            $cachePath = __DIR__ . '/../../cache';

            // Ensure the cache directory exists
            if (!is_dir($cachePath)) {
                mkdir($cachePath, 0777, true);
            }

            self::$cache = new FilesystemAdapter(
                'static_cache',        // Namespace for cache items
                3600,                  // Default lifetime (in seconds)
                $cachePath             // Path where cache files will be stored
            );
        }
        return self::$cache;
    }

    /**
     * Cache a page's output or retrieve it if already cached.
     *
     * @param string $cacheKey Unique key for the page cache.
     * @param callable $contentGenerator A callback function to generate the content if not cached.
     * @return string Cached or generated content.
     */
    public static function cachePage(string $cacheKey, callable $contentGenerator): string
    {
        $cache = self::createCache();
        $cacheItem = $cache->getItem($cacheKey);

        if (!$cacheItem->isHit()) {
            // Start output buffering
            ob_start();

            // Generate the content by invoking the callback
            $contentGenerator();

            // Get the output content and stop buffering
            $content = ob_get_clean();

            // Save the content to the cache
            $cacheItem->set($content);
            $cache->save($cacheItem);
        } else {
            // Retrieve cached content
            $content = $cacheItem->get();
        }

        return $content;
    }
}
