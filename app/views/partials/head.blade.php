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

</head>
<body>
    
