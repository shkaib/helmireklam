<?php namespace Watson\Sitemap;

use Watson\Sitemap\Tags\Tag;
use Watson\Sitemap\Tags\ExpiredTag;
use Watson\Sitemap\Tags\Sitemap as SitemapTag;

use DateTime;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Contracts\Cache\Repository as Cache;

class Sitemap
{
    /**
     * Collection of sitemaps being used.
     *
     * @var array
     */
    protected $sitemaps = [];

    /**
     * Collection of tags being used in a sitemap.
     *
     * @var array
     */
    protected $tags = [];

    /**
     * Laravel cache repository.
     *
     * @var \Illuminate\Cache\Repository
     */
    protected $cache;

    /**
     * Laravel request instance.
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * Construct the sitemap manager.
     *
     * @param  \Illuminate\Contracts\Cache\Repository  $cache
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function __construct(Cache $cache, Request $request)
    {
        $this->cache = $cache;
        $this->request = $request;
    }

    /**
     * Add new sitemap to the sitemaps index.
     *
     * @param  \Watson\Sitemap\Tags\Sitemap|string  $location
     * @param  string  $lastModified
     * @return void
     */
    public function addSitemap($location, $lastModified = null)
    {
        $sitemap = $location instanceof SitemapTag ? $location : new SitemapTag($location, $lastModified);

        $this->sitemaps[] = $sitemap;
    }

    /**
     * Retrieve the array of sitemaps.
     *
     * @return array
     */
    public function getSitemaps()
    {
        return $this->sitemaps;
    }

    /**
     * Render an index of of sitemaps.
     *
     * @return Illuminate\Http\Response
     */
    public function index()
    {
        if ($cachedView = $this->getCachedView()) {
            return response()->make($cachedView, 200, ['Content-type' => 'text/xml']);
        }

        $sitemapIndex = response()->view('sitemap::sitemaps', ['sitemaps' => $this->getSitemaps()], 200, ['Content-type' => 'text/xml']);

        $this->saveCachedView($sitemapIndex);

        return $sitemapIndex;
    }

    /**
     * Render an index of of sitemaps.
     *
     * @return Illuminate\Http\Response
     */
    public function renderSitemapIndex()
    {
        return $this->index();
    }

    /**
     * Add a new sitemap tag to the sitemap.
     *
     * @param  \Watson\Sitemap\Tags\Tag|string  $location
     * @param  \DateTime|string  $lastModified
     * @param  string  $changeFrequency
     * @param  string  $priority
     * @return void
     */
    public function addTag($location, $lastModified = null, $changeFrequency = null, $priority = null)
    {
        $tag = $location instanceof Tag ? $location : new Tag($location, $lastModified, $changeFrequency, $priority);

        $this->tags[] = $tag;
    }

    /**
     * Add a new expired tag to the sitemap.
     *
     * @param  string  $location
     * @param  \DateTime|string  $expired
     * @return void
     */    
    public function addExpiredTag($location, $expired = null)
    {
        $tag = $location instanceof ExpiredTag ? $location : new ExpiredTag($location, $expired);

        $this->tags[] = $tag;
    }

    /**
     * Retrieve the array of tags.
     *
     * @return array
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Get the formatted sitemap.
     *
     * @return string
     */
    public function xml()
    {
        return $this->render()->getOriginalContent();
    }

    /**
     * Get the formatted sitemap index.
     *
     * @return string
     */
    public function xmlIndex()
    {
        return $this->index()->getOriginalContent();
    }

    /**
     * Render a sitemap.
     *
     * @erturn \Illuminate\Http\Response
     */
    public function render()
    {
        if ($cachedView = $this->getCachedView()) {
            return response()->make($cachedView, 200, ['Content-type' => 'text/xml']);
        }

        $sitemap = response()->view('sitemap::sitemap', ['tags' => $this->getTags()], 200, ['Content-type' => 'text/xml']);

        $this->saveCachedView($sitemap);

        return $sitemap;
    }

    /**
     * Render a sitemap.
     *
     * @return \Illuminate\Http\Response
     */
    public function renderSitemap()
    {
        return $this->render();
    }

    /**
     * Clear all the existing sitemaps and tags.
     *
     * @return void
     */
    public function clear()
    {
        $this->sitemaps = $this->tags = [];
    }

    /**
     * Remove all the existing sitemaps.
     *
     * @return void
     */
    public function clearSitemaps()
    {
        $this->sitemaps = [];
    }

    /**
     * Remove all the existing tags.
     *
     * @return void
     */
    public function clearTags()
    {
        $this->tags = [];
    }

    /**
     * Check to see whether a view has already been cached for the current
     * route and if so, return it.
     *
     * @return mixed
     */
    protected function getCachedView()
    {
        if (config('sitemap.cache_enabled')) {
            $key = $this->getCacheKey();

            if ($this->cache->has($key)) {
                return $this->cache->get($key);
            }
        }

        return false;
    }

    /**
     * Save a cached view if caching is enabled.
     *
     * @param  \Illuminate\Http\Response  $response
     * @return void
     */
    protected function saveCachedView(Response $response)
    {
        if (config('sitemap.cache_enabled')) {
            $key = $this->getCacheKey();

            $content = $response->getOriginalContent()->render();

            if (!$this->cache->get($key)) {
                $this->cache->put($key, $content, config('sitemap.cache_length'));
            }
        }
    }

    /**
     * Get the cache key that will be used for saving cached sitemaps
     * to storage.
     *
     * @return string
     */
    protected function getCacheKey()
    {
        return 'sitemap_' . str_slug($this->request->url());
    }
}
