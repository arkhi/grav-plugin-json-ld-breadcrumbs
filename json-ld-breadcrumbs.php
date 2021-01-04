<?php
namespace Grav\Plugin;

use Composer\Autoload\ClassLoader;
use Grav\Common\Page\Page;
use Grav\Common\Plugin;
use RocketTheme\Toolbox\Event\Event;

/**
 * Class JSONLDBreadcrumbsPlugin
 * @package Grav\Plugin
 */
class JSONLDBreadcrumbsPlugin extends Plugin
{
    /**
     * @return array
     *
     * The getSubscribedEvents() gives the core a list of events
     *     that the plugin wants to listen to. The key of each
     *     array section is the event that the plugin listens to
     *     and the value (in the form of an array) contains the
     *     callable (or function) as well as the priority. The
     *     higher the number the higher the priority.
     */
    public static function getSubscribedEvents()
    {
        return [
            'onPluginsInitialized' => [
                ['autoload', 100000], // @todo Remove when plugin requires Grav >=1.7
                ['onPluginsInitialized', 0],
            ],
        ];
    }

    /**
    * Composer autoload.
    *
    * @return ClassLoader
    */
    public function autoload(): ClassLoader
    {
        return require __DIR__ . '/vendor/autoload.php';
    }

    /**
     * Handle restrictions and initialization of the plugin as early as possible.
     *
     * @param  Event  $event
     *
     * @return null
     */
    public function onPluginsInitialized(Event $event)
    {
        if ($this->isAdmin()) {
            return;
        }

        $this->enable([
            'onPageInitialized' => [
                [ 'addJsonLdBreadcrumbsToHead', 0 ],
            ],
        ]);
    }


    /**
     * Get pages in the branch of the current page, up to the root.
     *
     * @param  Page        $page   Current page
     * @param  array       $branch Array of path:Page items
     *
     * @return (Page)array         Array of path:Page items
     */
    public function getBranchUp(Page $page, $branch = []): array
    {
        if (!$page) {
            return [];
        }

        $branch[$page->path()] = $page;

        $parent = $page->parent();

        if ($parent) {
            $branch = $this->getBranchUp($parent, $branch);
        }

        return $branch;
    }

    /**
     * Get the breadcrumbs structure as a JSON-LD formatted string.
     *
     * @param  Page   $page The current page
     * @return string       JSON-LD encoded string for breadcrumbs of the current page
     */
    public function getJsonBreadcrumbs(Page $page): string
    {
        $pages       = $this->getBranchUp($page);
        $position    = 0;
        $breadcrumbs = [
            '@context'        => 'https://schema.org',
            '@type'           => 'BreadcrumbList',
            'itemListElement' => [],
        ];

        ksort($pages);

        foreach ($pages as $path => $page) {
            $IAmRoot = $page->url() === '/';

            if ($IAmRoot || $page->routable()) {
                $name = $IAmRoot
                    ? 'Home'
                    : $page->title();

                $breadcrumbs['itemListElement'][] = [
                    "@type"    => "ListItem",
                    "position" => $position++,
                    "item"     => [
                        "@type" => "WebPage",
                        "@id"   => $page->url(true),
                        "name"  => $name,
                    ]
                ];
            }
        }

        return json_encode($breadcrumbs, JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT );
    }

    /**
     * [onPageInitialized]
     * Inject breadcrumbs into the head of the page.
     *
     * @param Event $event
     */
    public function addJsonLdBreadcrumbsToHead( Event $event )
    {
        $page   = $event['page'];
        $assets = $this->grav['assets'];

        $assets->addInlineJs(
            $this->getJsonBreadcrumbs($page),
            ['type' => 'application/ld+json']
        );
    }
}
