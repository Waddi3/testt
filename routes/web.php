<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('home.index', []);
// })->name('home.index');

// Route::get('/contact',function(){
//     return view('home.contact');
// })->name('home.contact');

Route::view('/', 'home.index')
    ->name('home.index');

Route::view('/contact', 'home.contact')
    ->name('home.contact');

$posts = [
    1 => [
        'title' => 'Intro to Laravel',
        'content' => 'This is a short intro to Laravel',
        'is_new' => true,
        'has_comments' => false
    ],
    2 => [
        'title' => 'Intro to PHP',
        'content' => 'This is a short intro to PHP',
        'is_new' => false
    ],
    3 => [
        'title' => 'Intro to Golang',
        'content' => 'This is a short intro Golang',
        'is_new' => false
    ]
];

Route::get('/posts', function () use ($posts) {
   // dd(request()->all());
    dd((int)request()->query('page',1));
    return view('posts.index', ['posts' => $posts]);
});

Route::get('/posts/{id}', function ($id) use ($posts) {

    // abort_if(!array_key_exists($id, $posts), 404);

    abort_if(!isset($posts[$id]), 404);
    return view('posts.show', ['post' => $posts[$id]]);
})
    // ->where([
    //     'id' => '[0-9]+'
    // ])
    ->name('posts.show');

Route::get('/recent-posts/{days_ago?}', function ($daysAgo = 20) {
    return 'post from ' . $daysAgo . ' days ago';
})->name('posts.recent.index')->middleware('auth');

Route::prefix('/fun')->name('fun.')->group(function() use($posts){
    Route::get('responses', function () use ($posts) {
        return response($posts, 201)
            ->header('Content-Type', 'application/json')
            ->cookie('MY_COOKIE', 'waddia', 3600);
    })->name('responses');
    
    Route::get('redirect', function () {
        return redirect('/contact');
    })->name('redirect');
    
    Route::get('back', function () {
        return back();
    })->name('back');

    Route::get('named-route', function () {
        return redirect()->route('posts.show', ['id' => 1]);
    })->name('named-rout');
    
    Route::get('away', function () {
        return redirect()->away('https://google.com');
    })->name('away');
    
    Route::get('json', function () use ($posts) {
        return response()->json($posts);
    })->name('json');
    
    Route::get('download', function () use ($posts) {
        return response()->download(public_path('/Screenshot from 2024-08-25 13-59-44.png'), 'screenshot');
    })->name('download');
});

