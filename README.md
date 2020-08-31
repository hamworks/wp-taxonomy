# wp-taxonomy

```php
$builder = new HAMWORKS\WP\Taxonomy\Builder( 'slug', 'name', [ 'post' ] );
$builder->set_options( 
    [
        'public'      => true,
        'description' => '',
        'has_archive' => true,
    ]
);
$builder->create();
```
