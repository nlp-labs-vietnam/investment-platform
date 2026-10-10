@if(isset($pageSeo))
    <meta name="title" content="{{ $pageSeo['meta_title'] . ' | ' . basicControl()->site_title }}">
    <meta name="description" content="{{ isset($pageSeo['meta_description']) ? $pageSeo['meta_description'] : '' }}">
    <meta name="keywords"
          content="{{ is_array(@$pageSeo['meta_keywords']) ? implode(', ', @$pageSeo['meta_keywords']) : @$pageSeo['meta_keywords'] }}">

    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ isset(basicControl()->site_title) ? basicControl()->site_title : '' }}">
    <meta property="og:title" content="{{ isset($pageSeo['meta_title']) ? $pageSeo['meta_title'] : '' }}">
    <meta property="og:description" content="{{ isset($pageSeo['og_description']) ? $pageSeo['og_description'] : '' }}">
    <meta property="og:image" content="{{ @$pageSeo['meta_image'] }}">

    <meta name="twitter:card" content="{{ isset($pageSeo['meta_title']) ? $pageSeo['meta_title'] : '' }}">
    <meta name="twitter:title" content="{{ isset($pageSeo['meta_title']) ? $pageSeo['meta_title'] : '' }}">
    <meta name="twitter:description"
          content="{{ isset($pageSeo['meta_description']) ? $pageSeo['meta_description'] : '' }}">
    <meta name="twitter:image" content="{{ @$pageSeo['meta_image'] }}">

    <meta name="theme-color" content="{{ basicControl()->primary_color }}">
    <meta name="author" content="{{basicControl()->site_title}}">
    <meta name="robots" content="{{ isset($pageSeo['meta_robots']) ? $pageSeo['meta_robots'] : '' }}">
@endif



