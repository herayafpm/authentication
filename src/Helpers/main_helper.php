<?php

use Raydragneel\Authentication\Libraries\Gate;

if (!function_exists('main_url')) {
    function main_url($url)
    {
        return base_url('main/' . $url);
    }
}
if (!function_exists('asset')) {
    function asset($filePath = '')
    {
        return base_url('assets/' . $filePath);
    }
}
if (!function_exists('storage')) {
    function storage($filePath = '')
    {
        return base_url('storage/' . $filePath);
    }
}
if (!function_exists('renderView')) {
    function renderView($view, $data = [])
    {
        $request = service('request');
        $data = array_merge($request->dataPass, $data);
        return view($request->dataPass['_root_view'] . $view, $data);
    }
}

if (!function_exists('abort')) {
    function abort($code, $message = '', $type = 'web')
    {
        if ($type === 'web') {
            $page_title = $message;
            $response = service("response");
            $request = service("request");
            $response->setBody(view('error', array_merge($request->dataPass, compact('message','page_title','code'))));
            $response->setStatusCode($code);
            $response->send();
            die();
        } else {
            header('Content-Type', 'application/json');
            $response = service("response");
            $response->setJSON([
                'status' => false,
                'message' => $message,
                'data' => [],
            ]);
            $response->setStatusCode($code);
            $response->send();
            die();
        }
    }
}

if (!function_exists('abort_if')) {
    function abort_if($allowed = false, $code = 404, $message = '', $type = 'web')
    {
        if (!$allowed) {
            abort($code, $message, $type);
        }
    }
}
if (!function_exists('can')) {
    function can($permissions)
    {
        return Gate::allow($permissions, 'web');
    }
}
if (!function_exists('array_filter_datatable')) {
    function array_filter_datatable($key){
		return strpos($key, '_') !== 0;
	}
}
if (!function_exists('url_main_is')) {
    function url_main_is($path){
		return url_is('main/'.$path);
	}
}
if(!function_exists('formatMessage')){
    function formatMessage($str, $args = [])
    {
        return MessageFormatter::formatMessage('id',$str, $args);
    }
}