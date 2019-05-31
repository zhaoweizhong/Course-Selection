<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

$api = app('Dingo\Api\Routing\Router');
$api->version('v1', [
    'namespace'  => 'App\Http\Controllers\Api',
    'middleware' => ['serializer:array', 'bindings']
], function($api) {
    /* 主要API */
    $api->get('', function () {
        return response()->json([
            'message'     => 'Welcome to SUSTech Course Selection System API.',
            'status_code' => 200
        ])->setStatusCode(200);
    })->name('api.home.show');
    //用户注册
    $api->post('users', 'UserController@store')
        ->name('api.users.store');
    //用户登录
    $api->post('auth', 'AuthorizationsController@store')
        ->name('api.auth.store');
    //刷新token
    $api->put('auth/current', 'AuthorizationsController@update')
        ->name('api.auth.update');
    //删除token
    $api->delete('auth/current', 'AuthorizationsController@destroy')
        ->name('api.auth.destroy');
    //需要登录验证
    $api->group(['middleware' => 'api.auth'], function($api) {
        /* 用户 */
        //获取当前登录用户信息
        $api->get('user', 'UserController@me')
            ->name('api.user.me');
        //编辑当前登录用户信息
        $api->patch('user', 'UserController@update')
            ->name('api.user.update');
        //获取某用户信息
        $api->get('user/{user}', 'UserController@show')
            ->name('api.user.show');
        //上传图片
        $api->post('images', 'ImagesController@store')
            ->name('api.images.store');
        /* 课程 */
        //创建课程
        $api->post('courses', 'CourseController@store')
            ->name('api.courses.store');
        //修改课程
        $api->patch('course/{course}', 'CourseController@update')
            ->name('api.course.update');
        //查看课程
        $api->get('course/{course}', 'CourseController@show')
            ->name('api.course.show');
        //搜索课程
        $api->post('courses/search', 'CourseController@search')
            ->name('api.course.search');
        //查看老师创建的课程
        $api->get('user/{user}/courses', 'CourseController@userIndex')
            ->name('api.courses.userIndex');
        //删除课程
        $api->delete('course/{course}', 'CourseController@delete')
        ->name('api.course.delete');
        /* 选课 */
        //创建选课
        $api->post('selections', 'SelectionController@store')
            ->name('api.selections.store');
        //查看选课
        $api->get('selection/{selection}', 'SelectionController@show')
            ->name('api.selection.show');
        //查看某学生的选课
        $api->get('user/{user}/selections', 'SelectionController@userIndex')
            ->name('api.selection.userIndex');
        //删除选课
        $api->delete('selection/{selection}', 'SelectionController@delete')
            ->name('api.selection.delete');
    });
});
