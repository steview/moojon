<h1>Test</h1>
<?php
moojon_routes::get_rest_route('posts');
moojon_routes::get_rest_route('users');
$model = post::read_by_id(3);
echo user_uri($model).'<br />';
$model = user::read_by_id(30);
echo post_uri($model).'<br />';
echo post_uri($model).'<br />';
moojon_routes::get_rest_route('comments');
moojon_routes::get_rest_route('cars');
moojon_routes::get_rest_route('car_users');
?>