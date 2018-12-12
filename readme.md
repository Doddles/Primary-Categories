# Primary Category

Select a Primary Category for each post and view those posts from the frontend

## Getting Started

Once the plugin is activated - the primary category can be selected on each post via a dropdown (on the post editing page). Once set, you will be able to view the posts allocated to the primary category, for example: https://www.example.com/primary-category/uncategorized

You will also see the Primary Category appear in a new Admin Column on the `All Posts` page - this link will click through to each Primary Category page

You can access the primary within the WordPress loop in a number of ways:

### Display the Primary Category (with a link)
```
<?php primary_category(); ?>
```
output: `<a class="primary-category-link" href="[CATEGORY LINK]"><strong>[CATEGORY NAME]</strong></a>`

### Get the Primary Category (with a link)
```
<?php get_primary_category(); ?>
```
returned: `<a class="primary-category-link" href="[CATEGORY LINK]"><strong>[CATEGORY NAME]</strong></a>`

### Display the Primary Category Name
```
<?php primary_category_name(); ?>
```
output: `[CATEGORY NAME]`

### Get the Primary Category Name
```
<?php get_primary_category_name(); ?>
```
returned: `[CATEGORY NAME]`

### Display the Primary Category Link
```
<?php primary_category_link(); ?>
```
output: `[CATEGORY LINK]`

### Get the Primary Category Link
```
<?php get_primary_category_link(); ?>
```
returned: `[CATEGORY LINK]`

### Get the Primary Category Object
```
<?php get_primary_category_name(); ?>
```
returned: `[OBJECT]` returns the standard WordPress term object


### Prerequisites

A basic WordPress install - this plugin is not currently WordPress 5.0 compatable

### Installing

1. Upload the entire `/primary-category` directory to the `/wp-content/plugins/` directory.
2. Activate Primary Category through the 'Plugins' menu in WordPress.

## Built With

* [10up Plugin Scaffolding](https://github.com/10up/plugin-scaffold)

## Versioning

We use [SemVer](http://semver.org/) for versioning. For the versions available, see the [tags on this repository](https://github.com/your/project/tags).

## Authors

* **Doodles** - *Initial work* - [Doodles](https://github.com/doodles)

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details

## Acknowledgments

Thanks to 10up to inviting me to do this challenge :)
