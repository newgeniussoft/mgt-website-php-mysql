@import('app/utils/helpers/helper.php')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <meta name="description" content="{{isset($metaDescription) ? $metaDescription : ''}}">
    <meta name="keywords" content="{{isset($metaKeywords) ? $metaKeywords : ''}}">
    <meta name="robots" content="index,follow">
    <link rel="canonical" href="{{$_ENV['APP_URL'] . $_SERVER['REQUEST_URI']}}">
    <meta property="og:title" content="{{isset($metaTitle) ? $metaTitle : ''}}" />
    <meta property="og:description" content="{{isset($metaDescription) ? $metaDescription : ''}}" />
    <meta property="og:url" content="{{$_ENV['APP_URL'] . $_SERVER['REQUEST_URI']}}" />
    
		<link rel="stylesheet" href="{{ assets('css/timeline.css') }}">
		<link rel="stylesheet" href="{{ assets('plugins/gallery/css/app.css') }}">
		<link rel="stylesheet" href="{{ assets('plugins/gallery/css/theme.css') }}">
		<link rel="stylesheet" href="{{ assets('css/amination.css?ver=6.6.1') }}" media="all">
		<link rel="stylesheet" href="{{ assets('css/style.min.css?ver=6.6.1') }}" media="all">
		<link rel="stylesheet" href="{{ assets('css/font-awesome.minb6a4.css?ver=6.6.1') }}" media="all">
		<link rel="stylesheet" href="{{ assets('css/lineicons.css') }}" media="all">
		<link rel="stylesheet" href="{{ assets('css/bootstrap.minb6a4.css?ver=6.6.1') }}" media="all">
		<link rel="stylesheet" href="{{ assets('css/flaticonb6a4.css?ver=6.6.1') }}" media="all">
		<link rel="stylesheet" href="{{ assets('css/owl.carousel.minb6a4.css?ver=6.6.1') }}" media="all">
		<link rel="stylesheet" href="{{ assets('css/owl.theme.minb6a4.css?ver=6.6.1') }}" media="all">
		<link rel="stylesheet" href="{{ assets('css/owl.transitionsb6a4.css?ver=6.6.1') }}" media="all">
		<link rel="stylesheet" href="{{ assets('css/perfect-scrollbar.minb6a4.css?ver=6.6.1') }}" media="all">
		<link rel="stylesheet" href="{{ assets('css/style.min.6.4.css?ver=6.6.1') }}" media="all">
		<link rel="stylesheet" href="{{ assets('css/style.css') }}" media="screen" type="text/css">
		<link rel="stylesheet" href="{{ assets('css/vc-customize.min.css?ver=6.6.1') }}" media="all">
		<link rel="stylesheet" href="{{ assets('plugins/light-gallery/css/lightgallery.css') }}">
		<link rel="stylesheet" href="{{ assets('css/material.textfield.css') }}">
		<link rel="stylesheet" href="{{ assets('css/iview.css') }}">
		<link rel="stylesheet" href="{{ assets('css/style.mgt.css') }}">
		<link rel="icon" type="image/png" sizes="16x16" href="{{ assets('img/logo/favicon.png') }}">
	

</head>
<body>
    
