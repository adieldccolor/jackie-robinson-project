<?php

Route::controller('/', 'PagesController@home');

//impact pages
Route::controller('impact', 'PagesController@impact');

//about us pages
Route::controller('about-us', 'PagesController@about');
Route::controller('scholars', 'PagesController@scholars');
Route::controller('scholars.bio', 'PagesController@scholarsBio');
Route::controller('alumni', 'PagesController@alumni');
Route::controller('board-of-directors', 'PagesController@boardDirectors');
Route::controller('support-network', 'PagesController@supportNetwork');

//apply pages
Route::controller('apply', 'PagesController@apply');


//apply pages
Route::controller('timeline', 'PagesController@timeline');

//Support pages
Route::controller('support', 'PagesController@support');
Route::controller('donate', 'PagesController@donate');

Route::controller('faqs', 'PagesController@faqs');
Route::controller('terms', 'PagesController@terms');
Route::controller('media-kit', 'PagesController@mediakit');
Route::controller('sitemap', 'PagesController@sitemap');
Route::controller('press', 'PagesController@press');
Route::controller('press_post', 'PagesController@press_post');

//apply pages
Route::controller('gallery', 'PagesController@gallery');
Route::controller('gallery.albums', 'PagesController@galleryAlbums');
Route::controller('gallery.videos', 'PagesController@galleryVideos');
Route::controller('gallery.video', 'PagesController@galleryVideo');
Route::controller('gallery.photos', 'PagesController@galleryPhotos');


Route::controller('our-work', 'PagesController@work');
Route::controller('services', 'PagesController@services');
Route::controller('testimonials', 'PagesController@testimonials');
Route::controller('contact-us', 'PagesController@contact');