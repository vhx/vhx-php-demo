<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function() {
  $collections = array();

  /* VHX > List Collections
  .....................................
  a call to vhx.collections to get back our collections
  http://dev.vhx.tv/docs/api/?php#collection-list
  ..................................... */
  $parent_collections = \VHX\Collections::all()['_embedded']['collections'];

  foreach ($parent_collections as $parent):

    /* VHX > List Collection Items
    .....................................
    We then interate over each collection and use its href to get
    back our collection items.
    http://dev.vhx.tv/docs/api/?php#collection-items-list
    ..................................... */
    $sub_collections = \VHX\Collections::items(array(
      'collection' => $parent['_links']['self']['href'],
      'product' => 'https://api.vhx.tv/products/14444'
    ));

    array_push($collections, array(
      'name' => $parent['name'],
      'items' => $sub_collections['_embedded']['items']
    ));
  endforeach;

  return view('home', [
    'collections' => $collections,
    'hasBack' => false,
    'loggedIn' => Session::get('user') ? true : false,
    'hasFooter' => true
  ]);
});

Route::post('/join', function(Request $request) {

  // maybe prevent some spam (field should be empty)
  if (strlen($request::input('important')) > 0) {
    return redirect('/');
  }

  /* DEMO > Payment and Account Authorization
  .....................................
  For the sake of this demo site, no actual payment information is
  is collected or authorized. For payments, we recommend using Stripe's
  payment api (http://stripe.com). We also mock login the user by
  creating a session with the customer vhx href. You'd save this href in
  your DB, along with any other user account info (i.e. username/password).
  You can authorize users using Laravel's built in authentication
  https://laravel.com/docs/5.1/authentication
  ..................................... */

  if ($request::input('customer')) {

    /* VHX > Create Customer
    .....................................
    On post of the join form we can create a VHX Customer, associating
    the customer with our product, which gives the customer access to
    that project by enabling us to make authorization calls (see below)
    on line 165 in the /watch route
    http://http://dev.vhx.tv/docs/api/?php#customer-create
    ..................................... */
    $customer = \VHX\Customers::create(array(
      'name' => $request::input('customer')['name'],
      'email' => $request::input('customer')['email'],
      'product' => 'https://api.vhx.tv/products/14444'
    ));

    Session::put('user', array(
      'customer_href' => 'https://api.vhx.tv/customers/' + $customer['id'],
      'customer_email' => $request::input('customer')['email']
    ));
  }
});

Route::post('/login', function(Request $request) {
  $redirect = $request::input('redirect') ? $request::input('redirect') : '/';

  // maybe prevent some spam (field should be empty)
  if (strlen($request::input('important')) > 0) {
    return redirect('/');
  }

  /* DEMO > User Account Authorization
  .....................................
  For the sake of this demo site, no actual user authorization
  is implemented. You can authorize users using Laravel's built in
  authentication https://laravel.com/docs/5.1/authentication

  On POST to this route (/login), we mock login the user
  by creating a user session with the customer vhx href. The customer
  href would be something you store in your own DB on account creation.
  ..................................... */
  Session::put('user', array(
    'customer_href' => 'https://api.vhx.tv/customers/2041092',
    'customer_email' => $request::input('customer')['email']
  ));

  return redirect($redirect);
});

Route::get('/logout', function(Request $request) {
  Session::forget('user');
  return redirect('/');
});

Route::get('/watch/{id}', function($id) {
  if (!$id) {
    abort(404, 'Not found.');
  }

  if (!Session::get('user')['customer_href']) {
    return view('watch/unauthorized', [
      'hasBack' => true,
      'hasFooter' => false,
      'loggedIn' => Session::get('user') ? true : false
    ]);
  }

  /* VHX > Authorize Customer
  .....................................
  a call to \VHX\Authorizations to get back our authorized player
  http://dev.vhx.tv/docs/api/?php#authorizations-create
  ..................................... */
  try {
    $authorization = \VHX\Authorizations::create(array(
      'customer' => Session::get('user')['customer_href'],
      'video' => 'https://api.vhx.tv/videos/' + $id
    ));
  }
  catch (Exception $e) {
    return view('watch/unauthorized', [
      'hasBack' => true,
      'hasFooter' => false,
      'loggedIn' => Session::get('user') ? true : false
    ]);
  }

  return view('watch/player', [
    'hasBack' => true,
    'loggedIn' => Session::get('user') ? true : false,
    'hasFooter' => true,
    'authorization' => $authorization
  ]);
});