<?php

Route::apiResources([
    'authors'    => 'AuthorController',
    'categories' => 'CategoryController',
    'editorials' => 'EditorialController',
    'books'      => 'BookController',
    'formats'    => 'FormatController',
]);