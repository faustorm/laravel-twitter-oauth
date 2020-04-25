<?php

namespace App\Http\Controllers;
use Abraham\TwitterOAuth\TwitterOAuth;

use Illuminate\Support\Facades\Config;

use Illuminate\Http\Request;

class TwitterController extends Controller
{
    public function twitter_login(Request $request) {

    	$twitteroauth = new TwitterOAuth(config('twitter.CONSUMER_KEY'), config('twitter.CONSUMER_SECRET'));
 
		// request token of application
		$request_token = $twitteroauth->oauth(
		    'oauth/request_token', [
		        'oauth_callback' => config('twitter.URL_CALLBACK')
		    ]
		);
		 
		// throw exception if something gone wrong
		if($twitteroauth->getLastHttpCode() != 200) {
		    throw new \Exception('There was a problem performing this request');
		}
		 
		// save token of application to session
		$request->session()->put('oauth_token', $request_token['oauth_token']);
		$request->session()->put('oauth_token_secret', $request_token['oauth_token_secret']);
		 
		// generate the URL to make request to authorize our application
		$url = $twitteroauth->url(
		    'oauth/authorize', [
		        'oauth_token' => $request_token['oauth_token']
		    ]
		);
		 
		// and redirect
		header('Location: '. $url);
    }

    public function twitter_callback(Request $request) {
    	$oauth_verifier = filter_input(INPUT_GET, 'oauth_verifier');

 
		if (empty($oauth_verifier) ||
		    empty($request->session()->get('oauth_token')) ||
		    empty($request->session()->get('oauth_token_secret'))
		) {
		    // something's missing, go and login again
		    header('Location: ' . config('twitter.URL_LOGIN'));
		}



		//Si todo va bien
		// connect with application token
		$connection = new TwitterOAuth(
		    config('twitter.CONSUMER_KEY'),
		    config('twitter.CONSUMER_SECRET'),
		    $request->session()->get('oauth_token'),
		    $request->session()->get('oauth_token_secret')
		);
		 
		// request user token
		$token = $connection->oauth(
		    'oauth/access_token', [
		        'oauth_verifier' => $oauth_verifier
		    ]
		);

		//Me conecto a Twitter como el user
		$twitter = new TwitterOAuth(
		    config('twitter.CONSUMER_KEY'),
		    config('twitter.CONSUMER_SECRET'),
		    $token['oauth_token'],
		    $token['oauth_token_secret']
		);

		$retweet = $twitter->post('statuses/retweet/1253845229943341063');

		//Comprobamos si hay errores y los printamos
		if(isset($retweet->errors)) {
			print_r($retweet->errors);

		}
	}
}
