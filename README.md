# wp-post-type

```php
$builder = new HAMWORKS\WP\Taxonomy\Builder( 'slug', 'name', [ 'post' ] );
$builder->set_options( 
    [
        'public'      => true,
        'description' => '',
        'has_archive' => true,
        'rest_base'   => 'interview',
        'rewrite'     => [
            'with_front' => false,
            'slug'       => 'recruit/interview',
            'walk_dirs'  => false,
        ],
    ] 
);
$builder->create();
```
