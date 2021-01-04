# JSON-LD Breadcrumbs Plugin

The **JSON-LD Breadcrumbs** Plugin is an extension for [Grav CMS](http://github.com/getgrav/grav).

It will add a [JSON-LD] [Breadcrumbs definition][schema BreadcrumbList] in the `<head>` of the current page. **Only the routable pages will be listed.**

## Installation

You can installl this plugin by following any of the [options available][install plugins] with the _NAME_ `json-ld-breadcrumbs`:

- The **GPM** (Grav Package Manager) with `bin/gpm install json-ld-breadcrumbs`.
- The **manual** method by unzipping the content of the [zip file](https://github.com/arkhi/grav-plugin-json-ld-breadcrumbs/archive/master.zip) into `user/plugins/json-ld-breadcrumbs`.

## Configuration

No configuration is needed, the plugin will add the script automatically.

Here is an example, with a subsection that is not routable (_section_1_1_1_).

```json
{
    "@context": "https://schema.org",
    "@type": "BreadcrumbList",
    "itemListElement": [
        {
            "@type": "ListItem",
            "position": 0,
            "item": {
                "@type": "WebPage",
                "@id": "http://192.168.19.5",
                "name": "Home"
            }
        },
        {
            "@type": "ListItem",
            "position": 1,
            "item": {
                "@type": "WebPage",
                "@id": "http://192.168.19.5/section_1",
                "name": "Section 1"
            }
        },
        {
            "@type": "ListItem",
            "position": 2,
            "item": {
                "@type": "WebPage",
                "@id": "http://192.168.19.5/section_1/section_1_1",
                "name": "Section 1.1"
            }
        },
        {
            "@type": "ListItem",
            "position": 3,
            "item": {
                "@type": "WebPage",
                "@id": "http://192.168.19.5/section_1/section_1_1/section_1_1_1/page",
                "name": "Page"
            }
        }
    ]
}
```

[JSON-LD]: https://en.wikipedia.org/wiki/JSON-LD
[schema BreadcrumbList]: https://schema.org/BreadcrumbList
[install plugins]: https://learn.getgrav.org/16/plugins/plugin-install
