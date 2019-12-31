<!DOCTYPE html>
<html amp>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    @if(request()->get('page', 1) > 1)
        <meta name="robots" content="noindex, follow">@else
        <meta name="robots" content="index, follow">@endif
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">
    <title>@if(!empty($web_title)){{$web_title}}@else @yield('title')@endif</title>
    @if(!empty($web_description))
        <meta name="description" content="{{htmlspecialchars($web_description)}}">@endif
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script async custom-element="amp-form" src="https://cdn.ampproject.org/v0/amp-form-0.1.js"></script>
    <script async custom-template="amp-mustache" src="https://cdn.ampproject.org/v0/amp-mustache-0.2.js"></script>
	<?php
	$http = 'http://';
	if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
		$http = 'https://';
	} elseif (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' || !empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on') {
		$http = 'https://';
	}
	$canonical = request()->path() == '/' ? $http . request()->server('HTTP_HOST') : request()->url() ?>
    <link rel="canonical" href="{{ $canonical }}"/>
    <link rel="{!! !empty($web_title) ? htmlspecialchars($web_title) :  " " !!}"
          href="{!! !empty(config('domainInfo')['favicon']) ? config('domainInfo')['favicon'] : url('frontend/icon/favicon.png') !!}"/>
	<?php  $hotline = getHotLine(config('domainInfo')['hotlineList']);?>
    <style amp-boilerplate>body {
            -webkit-animation: -amp-start 8s steps(1, end) 0s 1 normal both;
            -moz-animation: -amp-start 8s steps(1, end) 0s 1 normal both;
            -ms-animation: -amp-start 8s steps(1, end) 0s 1 normal both;
            animation: -amp-start 8s steps(1, end) 0s 1 normal both
        }

        @-webkit-keyframes -amp-start {
            from {
                visibility: hidden
            }
            to {
                visibility: visible
            }
        }

        @-moz-keyframes -amp-start {
            from {
                visibility: hidden
            }
            to {
                visibility: visible
            }
        }

        @-ms-keyframes -amp-start {
            from {
                visibility: hidden
            }
            to {
                visibility: visible
            }
        }

        @-o-keyframes -amp-start {
            from {
                visibility: hidden
            }
            to {
                visibility: visible
            }
        }

        @keyframes -amp-start {
            from {
                visibility: hidden
            }
            to {
                visibility: visible
            }
        }</style>
    <noscript>
        <style amp-boilerplate>body {
                -webkit-animation: none;
                -moz-animation: none;
                -ms-animation: none;
                animation: none
            }</style>
    </noscript>
    <link href="https://fonts.googleapis.com/css?family=Special+Elite:400" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Special+Elite:400" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=PT+Sans:400,400i,700,700i" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=PT+Sans:400,400i,700,700i" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=PT+Sans:400,400i,700,700i" rel="stylesheet">

    <style amp-custom>
        div, span, h1, h2, h3, h4, h5, h6, p, blockquote, a, ol, ul, li, figcaption {
            font: inherit;
        }

        h3 {
            font-weight: bold;
        }

        b, strong {
            font-weight: 700;
        }

        section {
            background-color: #eeeeee;
        }


        a {
            font-style: normal;
            font-weight: 400;
            cursor: pointer;
        }

        a, a:hover {
            text-decoration: none;
        }

        figure {
            margin-bottom: 0;
        }

        h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6, .display-1, .display-2, .display-3, .display-4 {
            line-height: 1;
            word-break: break-word;
            word-wrap: break-word;
        }

        b, strong {
            font-weight: bold;
        }


        textarea[type="hidden"] {
            display: none;
        }

        body {
            position: relative;
        }

        section {
            background-position: 50% 50%;
            background-repeat: no-repeat;
            background-size: cover;
        }

        .row {
            margin-right: -15px;
            margin-left: -15px;
        }

        .row {
            display: -webkit-box;
            display: -webkit-flex;
            display: -ms-flexbox;
            display: flex;
            -webkit-flex-wrap: wrap;
            -ms-flex-wrap: wrap;
            flex-wrap: wrap;
            margin-right: -15px;
            margin-left: -15px;
        }

        @media (min-width: 576px) {
            .row {
                margin-right: -15px;
                margin-left: -15px;
            }
        }

        @media (min-width: 768px) {
            .row {
                margin-right: -15px;
                margin-left: -15px;
            }
        }

        @media (min-width: 992px) {
            .row {
                margin-right: -15px;
                margin-left: -15px;
            }
        }

        @media (min-width: 1200px) {
            .row {
                margin-right: -15px;
                margin-left: -15px;
            }
        }

        .align-left {
            text-align: left;
        }

        .align-center {
            text-align: center;
        }

        .align-right {
            text-align: right;
        }

        @media (max-width: 767px) {
        }

        .media-container > div {
            max-width: 100%;
        }

        @media (max-width: 991px) {
        }

        [type="submit"] {
            -webkit-appearance: none;
        }

        amp-img img {
            max-height: 100%;
            max-width: 100%;
        }

        .is-builder .nodisplay + img[async], .is-builder .nodisplay + img[decoding="async"], .is-builder amp-img > a + img[async], .is-builder amp-img > a + img[decoding="async"] {
            display: none;
        }

        html:not(.is-builder) amp-img > a {
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 1;
        }

        .is-builder .temp-amp-sizer {
            position: absolute;
        }

        .is-builder amp-youtube .temp-amp-sizer, .is-builder amp-vimeo .temp-amp-sizer {
            position: static;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
            font-style: normal;
            line-height: 1.5;
            font-size: 14px;

        }

        .mbr-text {
            font-style: normal;
            line-height: 1.6;
        }

        .txt-help {
            font-style: italic;
            float: left;
            margin-left: 10px;
            padding: 0;
        }

        .txt-help a {
            color: #fb0000;
        }
        @media screen and (max-width: 768px){
            #order_form .form-groups .txt-help {
                max-width: 60%;
                margin-top: 0;
                vertical-align: middle;
            }
        }

        p {
            margin: 0 0 10px;
        }

        .btn {
            font-weight: 400;
            border-width: 2px;
            border-style: solid;
            font-style: normal;
            letter-spacing: 2px;
            margin: .4rem .8rem;
            white-space: normal;
            transition-property: background-color, color, border-color, box-shadow;
            transition-duration: .3s, .3s, .3s, 2s;
            transition-timing-function: ease-in-out;
            padding: 1rem 2rem;
            border-radius: 0px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            word-break: break-word;
        }

        .btn-sm {
            border: 1px solid;
            font-weight: 400;
            letter-spacing: 2px;
            -webkit-transition: all 0.3s ease-in-out;
            -moz-transition: all 0.3s ease-in-out;
            transition: all 0.3s ease-in-out;
            padding: 0.6rem 0.8rem;
            border-radius: 0px;
        }

        .btn-md {
            font-weight: 600;
            letter-spacing: 2px;
            margin: .4rem .8rem;
            -webkit-transition: all 0.3s ease-in-out;
            -moz-transition: all 0.3s ease-in-out;
            transition: all 0.3s ease-in-out;
            padding: 1rem 2rem;
            border-radius: 0px;
        }


        .btn-primary, .btn-primary:active, .btn-primary.active {
            background-color: #4ea2e3;
            border-color: #4ea2e3;
            color: #ffffff;
        }

        .btn-primary:hover, .btn-primary:focus, .btn-primary.focus {
            color: #ffffff;
            background-color: #1f7dc5;
            border-color: #1f7dc5;
        }

        .btn-primary.disabled, .btn-primary:disabled {
            color: #ffffff;
            background-color: #1f7dc5;
            border-color: #1f7dc5;
        }


        .text-primary {
            color: #4ea2e3;
        }

        a, a:hover {
            color: #4ea2e3;
        }

        html, body {
            height: auto;
            min-height: 100vh;
        }

        .popover-content ul.show {
            min-height: 155px;
        }


        h1, h2, h3 {
            margin: auto;
        }

        h1, h3, p {
            padding: 10px 0;
            margin-bottom: 15px;
        }

        .col-md-6 {
            position: relative;
            min-height: 1px;
            padding-right: 15px;
            padding-left: 15px;
        }

        .col-mar-5 {
            margin-left: -5px;
            margin-right: -5px;
        }

        @media (max-width: 767px) {
            .container {
                max-width: 540px;
            }
        }

        @media (min-width: 768px) {
            .container {
                max-width: 720px;
            }
        }

        @media (min-width: 992px) {
            .container {
                max-width: 960px;
            }

            .col-md-6 {
                width: 50%;
            }

            .simhot .col-md-3, .simhot .col-md-6 {
                float: left;
                flex: none;
            }
        }

        header {
            background: #259d99;
            position: relative;
            height: 58px;
        }

        .search-groups form {
            float: right;
            margin-right: 10px;
        }

        .form-search {
            position: relative;
        }

        #search {
            height: 35px;
            width: 181px;
            border-radius: 4px;
            -moz-border-radius: 4px;
            -webkit-border-radius: 4px;
            padding: 5px;
            border: none;
            margin-top: 12px;
        }

        #go-search {
            height: 35px;
            border-radius: 4px;
            -webkit-border-radius: 4px;
            border: none;
        }

        #imagesubmit {
            position: absolute;
            top: 12px;
            right: 0;
            padding: 10px;
        }


        div.header-menu {
            float: right;
            height: 58px;
        }

        div.header-menu ul li:hover {
            background: #fff;
        }

        div.header-menu > ul {
            height: 100%;
            position: relative;
        }

        div.header-menu > ul > li {
            height: 100%;
            float: left;
            border-right: 1px solid #fafafa;
        }

        div.header-menu ul li:hover .dr-item {
            display: block;
        }

        .search-groups h1 a, .search-groups span a {
            float: left;
            color: #ffc637;
            font-size: 24px;
            line-height: 58px;
            font-weight: 700;
        }

        .sd {
            background-image: url(/frontend/icon/sim-home.png)
        }

        .vt {
            background-image: url(/frontend/icon/viettel2.png);
        }

        .vn {
            background-image: url(/frontend/icon/vina.png);
        }

        .mb2 {
            background-image: url(/frontend/icon/mobifone.png);
        }

        .gb {
            background-image: url(/frontend/icon/sim-gmobile.png);
        }

        .iinfo {
            background-image: url(/frontend/icon/info.png);
        }

        .icon {
            display: inline-block;
            background-repeat: no-repeat;
            background-position: top 5px center;
            background-size: 30px 30px;
            min-width: 70px;
            height: 100%;
            line-height: 90px;
            text-transform: uppercase;
            font-size: 11px;
            text-align: center;
            padding: 0 12px;
            color: #fff;
        }

        div.header-menu .dr-item {
            display: none;
            position: absolute;
            background: #fff;
            border: 1px solid #ddd;
            width: 100%;
            left: 0;
            z-index: 2;
        }

        ul.dr-item li {
            width: calc(100%/3);
            float: left;
        }

        ul.dr-item li a {
            padding: 10px;
            width: 100%;
            display: inline-block;
            color: #333;
        }

        ul.dr-item li a:hover {
            background-color: #ebebeb;
        }
        ul.dr-item li a {
            padding: 10px;
            width: 100%;
            display: inline-block;
            color: #333;
        }
        @media screen and (max-width: 768px){
            .search-groups {
                width: 100%;
                text-align: center;
            }
        }

        header .btn-close-menu, header .btn-open-menu, header .header-logo {
            display: none;
        }

        @media screen and (max-width: 768px) {
            header .btn-close-menu, header .btn-open-menu, header .header-logo {
                display: block;
                cursor: pointer;
            }
        }

        @media screen and (max-width: 768px){
            .header-logo {
                margin: 10px;
                z-index: 1;
                position: absolute;
                top: 2px;
                left: 0;
            }
        }

        @media screen and (max-width: 768px) {
            header {
                max-width: 100%;
                overflow-x: hidden;
                height: auto;
            }
        }

        @media screen and (max-width: 768px) {
            .icon-open-menu {
                background-position: -100px 0;
                width: 33px;
                height: 33px;
                display: block;
                margin: 2px auto;
            }

            [class*="icon-"], [class^="icon-"] {
                background-image: url(/frontend/icon/iconsprite.png);
                background-repeat: no-repeat;
                display: inline-block;
                height: 30px;
                width: 33px;
                line-height: 30px;
                vertical-align: middle;
                background-size: 285px 140px;
            }
        }
        @media screen and (max-width: 768px){
            .btn-open-menu {
                position: absolute;
                top: 0;
                right: 0;
                padding: 12px 10px 10px 6px;
                width: 50px;
                cursor: pointer;
                z-index: 10;
            }
        }

        .logo-header {
            background: url({{config('domainInfo')['logo_mobile'] != null
                    ? config('domainInfo')['logo_mobile'] : "frontend/icon/logo_mobile.png" }}) no-repeat;
            height: 35px;
            width: 35px;
            display: inline-block;
            background-size: contain;
        }
        @media screen and (max-width: 768px){
            .search-groups h1, .search-groups span {
                display: none;
            }
        }

        button, input {
            overflow: visible;
        }

        button, input, optgroup, select, textarea {
            font-family: inherit;
            font-size: 100%;
            line-height: 1.15;
            margin: 0;
        }

        @media (min-width: 1200px) {
            .container {
                max-width: 1140px;
            }
        }

        @media screen and (max-width: 768px) {
            .simhot .col-md-3 {
                width: 50%;
                float: left;
            }

            .simhot .simhotitem {
                padding: 10px 2px;
            }
        }

        @media screen and (max-width: 768px){
            div.header-menu>ul>li {
                height: auto;
                display: inline-block;
                border-right: none;
            }
        }

        .search-groups {
            float: left;
            height: 58px;
            width: 480px;
        }

        @media screen and (max-width: 480px) {
            .search-groups form {
                margin-right: 0;
            }
        }

        @media screen and (max-width: 768px) {
            .search-groups form {
                float: none;
                width: 65%;
                text-align: center;
                display: inline-block;
            }
        }

        @media screen and (max-width: 768px) {
            #imagesubmit {
                padding: 8px 20px;
                background: #fdd41e;
                border-top-right-radius: 4px;
                -webkit-border-top-right-radius: 4px;
                border-bottom-right-radius: 4px;
                -moz-border-radius-bottomright: 4px;
                -webkit-border-bottom-right-radius: 4px;
                margin: 1px;
            }
        }

        @media screen and (max-width: 768px) {
            .search-groups form {
                float: none;
                width: 65%;
                text-align: center;
                display: inline-block;
            }
        }


        @media screen and (max-width: 480px) {
            .search-groups form {
                margin-right: 0;
            }
        }

        @media screen and (max-width: 768px){
            #search {
                width: 80%;
            }
        }

        .container {
            max-width: 1015px;
            width: 100%;
            padding-right: 15px;
            padding-left: 15px;
            margin-right: auto;
            margin-left: auto;
        }

        .content {
            width: 720px;
            position: relative;
            margin: 20px 20px 20px 0;
            float: left;
            min-height: 720px;
        }

        @media screen and (max-width: 768px) {
            .content {
                width: 100%;
                margin: 0;
            }
        }

        label {
            display: inline-block;
            max-width: 100%;
            margin-bottom: 5px;
            font-weight: 700;
        }

        .simhot .simhotitem.first img {
            width: auto;
        }

        .simhotitem img {
            width: 30px;
        }

        section.sidebar-open:before {
            content: '';
            position: fixed;
            top: 0;
            bottom: 0;
            right: 0;
            left: 0;
            background-color: rgba(0, 0, 0, 0.2);
            z-index: 1040;
        }

        p {
            margin-top: 0;
        }

        .right-sidebar amp-sidebar {
            min-width: 260px;
            z-index: 1050;
            background-color: #ffffff;
        }

        .right-sidebar amp-sidebar.open:after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            bottom: 0;
            right: 0;
            background-color: red;
        }

        .right-sidebar .open {
            transform: translateX(0%);
            display: block;
        }

        .right-sidebar .builder-sidebar {
            background-color: #ffffff;
            position: relative;
            height: 100vh;
            z-index: 1030;
            padding: 1rem 2rem;
            max-width: 20rem;
        }

        .right-sidebar .headerbar {
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: .5rem 1rem;
            min-height: 70px;
            align-items: center;
            background: #ffffff;
        }

        .right-sidebar .headerbar.sticky-nav {
            position: fixed;
            z-index: 1000;
            width: 100%;
        }

        .right-sidebar button.sticky-but {
            position: fixed;
        }

        .right-sidebar .brand {
            display: flex;
            align-items: center;
            align-self: flex-start;
            padding-right: 30px;
        }

        .right-sidebar .brand p {
            margin: 0;
            padding: 0;
        }

        .right-sidebar .brand-name {
            color: #197bc6;
        }

        .right-sidebar .sidebar {
            padding: 1rem 0;
            margin: 0;
        }

        .right-sidebar .sidebar > li {
            list-style: none;
            display: flex;
            flex-direction: column;
        }

        .right-sidebar .sidebar a {
            display: block;
            text-decoration: none;
            margin-bottom: 10px;
        }

        .right-sidebar .close-sidebar {
            width: 30px;
            height: 30px;
            position: relative;
            cursor: pointer;
            background-color: transparent;
            border: none;
        }

        .right-sidebar .close-sidebar:focus {
            outline: 2px auto #4ea2e3;
        }

        .right-sidebar .close-sidebar span {
            position: absolute;
            left: 0;
            width: 30px;
            height: 2px;
            border-right: 5px;
            background-color: #197bc6;
        }

        .right-sidebar .close-sidebar span:nth-child(1) {
            transform: rotate(45deg);
        }

        .content .seo-box h1 {
            margin: 0;
            font-size: 20px;
        }

        h1 {
            font-size: 2em;
            margin: .67em 0;
            font-weight: bold;
        }

        .right-sidebar .close-sidebar span:nth-child(2) {
            transform: rotate(-45deg);
        }

        @media (min-width: 992px) {
            .right-sidebar .brand-name {
                min-width: 8rem;
            }

            .right-sidebar .builder-sidebar {
                margin-left: auto;
            }

            .right-sidebar .builder-sidebar .sidebar li {
                flex-direction: row;
                flex-wrap: wrap;
            }

            .right-sidebar .builder-sidebar .sidebar li a {
                padding: .5rem;
                margin: 0;
            }

            .right-sidebar .builder-overlay {
                display: none;
            }
        }

        .right-sidebar .hamburger {
            position: absolute;
            top: 25px;
            right: 20px;
            margin-left: auto;
            width: 30px;
            height: 20px;
            background: none;
            border: none;
            cursor: pointer;
            z-index: 1000;
        }

        @media (min-width: 768px) {
            .right-sidebar .hamburger {
                top: calc(0.5rem + 55 * 0.5px - 10px);
            }
        }

        .right-sidebar .hamburger:focus {
            outline: none;
        }

        .right-sidebar .hamburger span {
            position: absolute;
            right: 0;
            width: 30px;
            height: 2px;
            border-right: 5px;
            background-color: #197bc6;
        }

        .right-sidebar .hamburger span:nth-child(1) {
            top: 0;
            transition: all .2s;
        }

        .right-sidebar .hamburger span:nth-child(2) {
            top: 8px;
            transition: all .15s;
        }

        .right-sidebar .hamburger span:nth-child(3) {
            top: 8px;
            transition: all .15s;
        }

        .right-sidebar .hamburger span:nth-child(4) {
            top: 16px;
            transition: all .2s;
        }

        .right-sidebar amp-img {
            height: 55px;
            width: 55px;
            margin-right: 1rem;
            display: flex;
            align-items: center;
        }

        @media (max-width: 768px) {
            .right-sidebar amp-img {
                max-height: 55px;
                max-width: 55px;
            }
        }

        @media (min-width: 992px) {
            .col-md-3 {
                width: 25%;
            }
        }

        @media (min-width: 992px) {
            .simhot .col-md-3, .simhot .col-md-6 {
                float: left;
                flex: none;
            }
        }

        @media (min-width: 992px) {
            .simhot .col-md-3, .simhot .col-md-6 {
                float: left;
                flex: none;
            }
        }

        .col-mar-5 > [class*="col-"], .col-mar-5 > .col, .col-mar-5 > [class^="col-"] {
            padding-left: 5px;
            padding-right: 5px;
        }

        @media (min-width: 992px) {
            .simhot .row {
                display: block;
            }

            .col-mar-5 {
                margin-left: -5px;
                margin-right: -5px;
            }
        }

        .content .seo-box {
            background: #ffffff;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid transparent;
            -webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, .05);
            box-shadow: 0 1px 1px rgba(0, 0, 0, .05);
            -moz-box-shadow: 0 1px 1px rgba(0, 0, 0, .05);
        }

        .simhot {
            display: inline-block;
            background: #fff;
            width: 100%;
            padding: 0 10px;
            margin-bottom: 10px;
            border: 1px solid transparent;
            -webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, .05);
            box-shadow: 0 1px 1px rgba(0, 0, 0, .05);
            -moz-box-shadow: 0 1px 1px rgba(0, 0, 0, .05);
        }

        .simhot .head-title.no-icon {
            padding: 2px 0;
        }

        .simhot .head-title {
            font-size: 20px;
            color: #DB002D;
            position: relative;
            padding-left: 40px;
            min-height: 40px;
            line-height: 40px;
            margin: 0;
            font-weight: bold;
        }

        .simhot .simhotitem {
            background: #fff;
            margin-bottom: 10px;
            padding: 10px;
            color: #fff;
            height: 55px;
            overflow: hidden;
            position: relative;
            -webkit-transition: all 0.3s ease;
            -o-transition: all 0.3s ease;
            transition: all 0.3s ease;
            border-radius: 4px;
            border: 1px solid #eaeaea;
        }

        .simhot .simhotitem.first {
            height: 120px;
            display: flex;
            vertical-align: middle;
            align-items: center;
            justify-content: center;
        }

        .simhot .simhotitem a {
            display: inline-block;
            color: #525252;
        }

        @media screen and (max-width: 767.98px) {
            .simhot .simhotitem {
                padding: 10px 2px;
            }
            .col-md-6 {
                width: 100%;
            }
        }

        .simhot .simhotitem a.hv {
            position: absolute;
            left: 50%;
            top: 50%;
            -webkit-transform: translate(-50%, -50%);
            -ms-transform: translate(-50%, -50%);
            transform: translate(-50%, -50%);
            background: #EF5777;
            color: #fff;
            border-radius: 4px;
            padding: 6px 8px;
            line-height: 1.1;
            opacity: 0;
        }

        .simhot .simhotitem.first .ssim {
            font-size: 30px;
            font-weight: bold;
        }

        .simhot .simhotitem p .ssim {
            font-size: 18px;
            font-family: muli, sans-serif;
            color: #00599E;
        }

        .simhot .simhotitem p {
            float: right;
            display: inline-block;
            width: 75%;
            margin: 0;
            padding: 0;
            line-height: 1.1;
        }

        .simhot .simhotitem.viewmore {
            background: #259D99;
        }

        .simhot .simhotitem.viewmore a {
            font-size: 18px;
            display: flex;
            vertical-align: middle;
            align-items: center;
            justify-content: center;
            font-family: muli, sans-serif;
            color: #fff;
        }

        .menu.right-sidebar {
            position: relative;
            height: 58px;
        }


        .simhot .simhotitem p .ssim {
            font-size: 15px;
            font-family: muli, sans-serif;
            color: #00599E;
        }

        .simhot .simhotitem p .price {
            font-size: 14px;
        }

        div.sim-detail {
            background: #ffffff;
        }

        .sim-info-header {
            padding: 10px;
            text-transform: uppercase;
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            font-size: 16px;
            padding-bottom: 10px;
            color: #259d99;
            border-bottom: 1px solid #D2DAE2;
            font-weight: 600;
            margin-top: 5px;
        }

        .sim-header, .related-title {
            color: #259d99;
        }

        ul {
            padding: 0;
            margin: 0;
            list-style: none;
        }

        ul.sim-info {
            width: 55%;
            float: left;
        }

        ul.sim-info li {
            padding: 10px 0;
        }

        .sim-info li label {
            width: 130px;
            display: inline-block;
            clear: both;
            text-align: right;
        }

        @media screen and (max-width: 768px){
            .sim-detail ul.sim-info {
                width: 100%;
                padding: 0 15px;
            }
        }

        @media screen and (max-width: 768px){
            .sim-info li label {
                text-align: left;
                width: 100px;
            }
        }

        .sosim2 {
            font-size: 26px;
            font-weight: 700;
        }

        .red2 {
            color: #dc0707;
        }

        @media screen and (max-width: 768px){
            .sosim2 {
                font-size: 20px;
            }
        }

        .clearfix::after, .clearfix::before {
            content: " ";
            display: table;
            clear: both;
        }

        .sim-header {
            font-size: 1.17em;
            font-weight: 400;
            margin: 0;
        }

        .sim-detail .col-right {
            float: right;
            width: 45%;
            background: #fff;
            padding: 15px;
        }
        @media screen and (max-width: 768px){
            .sim-detail .col-right {
                display: none;
            }
        }

        .sim-header-title {
            display: inline;
        }

        .sim-detail .col-right img {
            max-width: 100%;
        }

        .sim-info-container {
            display: inline-block;
        }

        .form-order {
            background: #fff;
            margin-top: 20px;
            display: inline-block;
            width: 100%;
        }

        .form-groups {
            margin-bottom: 15px;
        }
        .group-submit {
            min-height: 30px;
        }
        .form-order form {
            padding: 15px;
        }

        #order_form {
            padding-top: 30px;
            padding-bottom: 30px;
        }

        #order_form label {
            margin-bottom: 2px;

        }

        .form-groups label {
            float: left;
            width: 174px;
            line-height: 32px;
        }

        .form-input {
            position: relative;
            padding-bottom: 5px;
        }

        .form-groups input[type="text"] {
            height: 38px;
        }

        .form-groups input[type="text"], .form-groups textarea {
            width: 70%;
            padding-left: 10px;
            border: 1px solid #ddd;
        }
        @media screen and (max-width: 768px){
            #order_form .form-groups label {
                display: none;
            }
            #order_form .form-groups label {
                width: 100%;
            }

        }

        @media screen and (max-width: 768px) {
            .form-order .form-groups input[type=text], .form-order .form-groups select {
                width: 100%;
            }
        }

        @media screen and (max-width: 768px) {
            .form-order .form-groups input[type=text], .form-order .form-groups textarea {
                width: 100%;
            }
        }

        input.submit-btn {
            border: none;
            background: #f60;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 4px;
            -webkit-border-radius: 4px;
            -moz-border-radius: 4px;
            font-weight: 700;
            color: #fff;
            font-size: 18px;
        }

        button, html input[type=button], input[type=reset], input[type=submit] {
            -webkit-appearance: button;
            cursor: pointer;
        }

        .group-submit .submit-btn {
            float: left;
        }

        .hd {
            background: #fff;
            padding: 15px;
            margin-top: 15px;
        }

        .hd h3 {
            margin: 0 0 10px 0;
        }

        .hd h3.h32 {
            margin-top: 25px;
        }

        .hd strong {
            color: red;
        }

        .text-center {
            text-align: center;
        }

        .pagination {
            display: inline-block;
            padding-left: 0;
            margin: 20px 0;
            border-radius: 4px;
        }

        .pagination {
            display: flex;
        }

        .pagination {
            margin: 20px 0;
            align-items: center;
            justify-content: center;
            vertical-align: middle;
            width: 100%;
        }

        .pagination > li {
            display: inline;
        }

        .pagination > li > a, .pagination > li > span {
            position: relative;
            float: left;
            padding: 6px 12px;
            margin-left: -1px;
            line-height: 1.42857143;
            color: #337ab7;
            text-decoration: none;
            background-color: #fff;
            border: 1px solid #ddd;
        }

        .pagination > li > a, .pagination > li > span {
            color: #259d99;
        }

        .pagination > li:first-child > a, .pagination > li:first-child > span {
            margin-left: 0;
            border-top-left-radius: 4px;
            border-bottom-left-radius: 4px;
        }

        .pagination a:first-child {
            border-left: 1px solid #eaeaea;
        }

        .pagination > .active > a, .pagination > .active > a:focus, .pagination > .active > a:hover, .pagination > .active > span, .pagination > .active > span:focus, .pagination > .active > span:hover {
            background-color: #259d99;
            border-color: #259d99;
        }

        .pagination > .active > a, .pagination > .active > a:focus, .pagination > .active > a:hover, .pagination > .active > span, .pagination > .active > span:focus, .pagination > .active > span:hover {
            z-index: 1;
        }

        .pagination > .active > a, .pagination > .active > a:focus, .pagination > .active > a:hover, .pagination > .active > span, .pagination > .active > span:focus, .pagination > .active > span:hover {
            z-index: 3;
            color: #fff;

        }

        .ajax-content {
            position: relative;
        }

        .box-filter {
            margin-bottom: 5px;
            background: #fff;
            border: 1px solid #3a83c1;
        }

        .box-filter a {
            position: relative;
        }

        .box-filter .h-title {
            display: inline-block;
            text-align: center;
            width: 100%;
            text-transform: uppercase;
            font-size: 20px;
            color: #db002d;
            margin: 10px 0 5px 0;
        }

        .boxgoiy {
            margin: 0;
            font-size: 14px;
            background: #fff;
            padding: 3px 4px 4px 10px;
        }

        .box-filter .stitle {
            font-size: 14px;
        }
        .box-filter a.remove_active:before {
            position: absolute;
            height: 14px;
            width: 14px;
            line-height: 11px;
            z-index: 2;
            background: #ff0303;
            top: -9px;
            right: -4px;
            content: "⨯";
            text-align: center;
            vertical-align: middle;
            border-radius: 50px;
            color: #fff;
        }
        .boxgoiy a {
            background: #ecf4f7;
            padding: 6px 10px;
            border-radius: 5px;
            border: 1px solid #337ab7;
            display: inline-block;
            margin-bottom: 8px;
            font-size: 15px;
            color: #00599e;
        }

        .divTable {
            display: table;
            width: 100%;
            background: #fff;
        }

        .divTableBody {
            display: table-row-group;
        }

        .divTableRow {
            display: table-row;
            background: #ffffff;
        }

        .divTableRow:first-child {
            height: 34px;
            line-height: 34px;
        }

        .divTableRow:nth-of-type(odd) .divTableCell {
            background-color: #f7f7f7;
        }

        .divTableRow:first-child .divTableCell {
            padding: 0;
            background-color: #cbcbcb;
        }

        .divTableCell:first-child {
            border-left: 1px solid #eaeaea;
        }

        .divTableCell, .divTableHead {
            border-bottom: 1px solid #eaeaea;
            display: table-cell;
            padding: 10px 6px;
            border-right: 1px solid #eaeaea;
            text-align: center;
            vertical-align: middle;
        }

        .divTableCell .stt {
            font-size: 12px;
        }

        .divTableCell .sosim {
            font-size: 19px;
            font-family: muli, sans-serif;
            font-weight: 400;
        }

        .green {
            color: #00599e;
        }

        .divTableCell.cell-price {
            padding-right: 10px;
            font-family: 'Titillium Web', sans-serif;
            font-size: 13px;
        }

        .divTableRow .divTableCell .order-btn {
            color: #259d99;
        }

        .font-size-12 {
            font-size: 12px;
        }
        #order_form.amp-form-submit-success > .form-groups {
            display: none
        }

        b, strong {
            font-weight: 700;
        }

        .success-order {
            display: inline-block;
            width: 100%;
            padding: 15px;
        }

        .success-order-content {
            background-color: #ff6;
            width: 400px;
            margin: 0 auto;
            border: 1px solid red;
            padding: 10px;
            text-align: center;
        }
        @media screen and (max-width: 768px) {
            .divTableCell, .divTableHead {
                padding-left: 15px;
            }

            .divTableCell.hidden-xs, .divTableHead.hidden-xs {
                display: none;
            }
        }

        .news-content {
            word-break: break-word;
            word-wrap: break-word;
        }
        .news-item-content .item-list {
            background: #fff;
            padding: 10px;
            margin-bottom: 15px;
            display: inline-block;
            width: 100%;
            clear: both;
        }
        .news-item-content .item-list:first-child {
            padding-top: 20px;
        }
        .news-item-content .item-list .item-list-img {
            width: 30%;
            float: left;
        }
        .news-content img {
            max-width: 100%;
            height: auto;
        }
        .news-item-content .item-list .item-list-info {
            float: left;
            margin-left: 2%;
            width: 67%;
        }
        .news-item-content .item-list .item-list-title {
            font-weight: bold;
            font-size: 16px;
            margin-top: 0;
            padding: 0;
        }
        .news-item-content .item-list .time-label {
            margin: 5px 0;
        }
        a {
            color: #259D99;
            text-decoration: none;
        }
        .content .news-wapper {
            background: #ffffff;
            padding: 10px;
        }
        amp-sidebar{
            background-color: #fff;
        }
        @media screen and (max-width: 768px){
            div.header-menu {
                position: relative;
                max-height: 0;
                z-index: 3;
                width: 100%;
                transition: all .8s ease;
                background: rgba(0,0,0,.8);
            }
        }

        @media screen and (max-width: 768px){
            .menu-slider-over div.header-menu {
                width: 100%;
                z-index: 9;
                visibility: visible;
                opacity: 1;
                max-height: none;
                height: 100%;
                padding-left: 10px;
            }
        }
        @media screen and (max-width: 768px){
            .header-menu ul li {
                width: 100%;
            }
        }

        div.header-menu>ul>li {
            height: 100%;
            float: left;
            border-right: 1px solid #fafafa;
        }

        @media screen and (max-width: 768px){
            div.header-menu>ul>li {
                height: auto;
                display: inline-block;
                border-right: none;
            }
        }
        @media screen and (max-width: 768px){
            .header-menu ul li a {
                padding: 10px 5px 10px 15px;
                line-height: inherit;
                width: 100%;
                text-align: left;
                font-size: 14px;
                color: #dadada;
                background-image: none;
            }
            ul.dr-item li a{
                color: #333;
            }
        }
        @media screen and (max-width: 768px){
            div.header-menu .dr-item {
                display: inline-block;
                border: none;
                position: relative;
            }
            .parent-menu > a{
                background-color: #000;
            }
        }
        .order-message {
            font-size: 26px;
            color: #ff6c04;
        }
        .carousel-inner>.item>a>img, .carousel-inner>.item>img, .img-responsive, .thumbnail a>img, .thumbnail>img {
            display: block;
            max-width: 100%;
            height: auto;
        }
        footer p {
            line-height: 1.5;
            margin-bottom: 5px;
        }
        .col-md-4, .col-md-8  {
            position: relative;
            min-height: 1px;
            padding-right: 15px;
            padding-left: 15px;
        }
        .hotlinebottom {
            position: fixed;
            display: block;
            bottom: 15px;
            left: 9px;
            z-index: 99999;
        }
        .hotlinebottom a {
            z-index: 9;
            line-height: 37px;
            color: #fff;
            font-weight: 700;
            font-size: 21px;
            position: fixed;
            bottom: 15px;
            left: 9px;
            padding: 0 10px 0 37px;
            height: 36px;
            background: #f6642f;
            box-shadow: 0 4px 5px -1px rgba(0, 0, 0, .5);
            border-radius: 50px;
        }
        #callnumber {
            position: absolute;
            top: 7px;
            left: 0;
        }
        #callnumber+div {
            border: 1px solid #fff;
            width: 34px;
            height: 34px;
            position: absolute;
            border-radius: 50px;
            left: 0;
            top: 0;
        }
        #callnumber span {
            display: inline-block;
            width: 36px;
            height: 36px;
            background: url(/frontend/icon/phone2.png) no-repeat top;
            -webkit-animation: Rotate 1.3s linear 1.3s 5;
            animation: Rotate 1.3s linear 1.3s 5;
            animation-iteration-count: infinite;
            -webkit-animation-iteration-count: infinite;
            -moz-animation-iteration-count: infinite;
            -o-animation-iteration-count: infinite;
        }
    @if(!empty(config('domainInfo')['main_color']))
        @php $mainColor = config('domainInfo')['main_color']; @endphp

         header{background: {{$mainColor}};}
        .panel-orange.panel-arrows .panel-header{color: {{$mainColor}};}
        .divTableRow .divTableCell .order-btn{color:{{$mainColor}}}
        .divTableRow:hover .order-btn{background:{{$mainColor}}}
        .pagination>.active>a, .pagination>.active>a:focus, .pagination>.active>a:hover, .pagination>.active>span, .pagination>.active>span:focus, .pagination>.active>span:hover{
            background-color: {{$mainColor}};
            border-color: {{$mainColor}};
        }
        .divTableRow:hover .divTableCell .order-btn{
            color:#fff;
        }
        .pagination>li>a, .pagination>li>span{color: {{$mainColor}}}
        ul.sidebar-filter a:before{
            background: {{$mainColor}};
        }
        .sim-header,.related-title{
            color: {{$mainColor}};
        }
        footer .hotline-ft.arrow_box{
            background:{{$mainColor}}
		}
        footer .hotline-ft.arrow_box:after{
            border-top-color: {{$mainColor}};
        }
        .simhot .simhotitem.viewmore{
            background:{{$mainColor}}
		}
        @endif
    </style>
    <script async src="https://cdn.ampproject.org/v0.js"></script>
    <script async custom-element="amp-sidebar" src="https://cdn.ampproject.org/v0/amp-sidebar-0.1.js"></script>
    <script async custom-element="amp-iframe" src="https://cdn.ampproject.org/v0/amp-iframe-0.1.js"></script>
    <script type="application/ld+json">
        {
            "@context":"https://schema.org",
            "@type":"LocalBusiness",
            "name":"@if(!empty($web_title)){{$web_title}}@else @yield('title')@endif",
            "image":"{!! !empty(config('domainInfo')['logo']) ? config('domainInfo')['logo'] : url('frontend/icon/logo.png') !!}",
            "@id":"",
            "url":"{{ $canonical }}",
            "telephone":"{{$hotline['hot']}}",
            "priceRange":"299000, 10000000000",
            "address":{
               "@type":"PostalAddress",
               "streetAddress":"22 Thành Công, Ba Đình",
               "addressLocality":"Hà Nội",
               "postalCode":"100000",
               "addressCountry":"VN",
               "geo":{
                    "@type":"GeoCoordinates",
                    "latitude":21.0191469,
                    "longitude":105.82817,
                    "openingHoursSpecification":{
                        "@type":"OpeningHoursSpecification",
                        "dayOfWeek":["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday"],
                        "opens":"@if((config('domainInfo')['hotline_open'])){{config('domainInfo')['hotline_open'].":00"}}@else{{"00:00"}}@endif",
                        "closes":"@if((config('domainInfo')['hotline_open'])){{config('domainInfo')['hotline_close'.":00"]}}@else{{"23:59"}}@endif"
                    },
                   "sameAs":[
                       "https://www.facebook.com/simgiaresimdoanhnhan/"
                   ]
               }
            }
        }
    </script>
    {!! !empty(config('domainInfo')['header_code']) ? config('domainInfo')['header_code'] : '' !!}
</head>
<body>

<header>
    <div class="container">
        <div class="row">
            <amp-sidebar id="sidebar"
                         class="sample-sidebar"
                         layout="nodisplay"
                         side="right">
                <div class="header-menu">
                    <ul>
                        <li class="has-child parent-menu">
                            <a href="/" class="hd-icon icon sd">Danh mục sim</a>
                            <ul class="dr-item">
                                <li><a href="/sim-vip">Sim VIP</a></li>
                                <li><a href="/sim-luc-quy">Sim Lục quý</a></li>
                                <li><a href="/sim-luc-quy-giua">Sim Lục quý giữa</a></li>
                                <li><a href="/sim-ngu-quy">Sim ngũ quý</a></li>
                                <li><a href="/sim-ngu-quy-giua">Sim ngũ quý giữa</a></li>
                                <li><a href="/sim-tu-quy">Sim Tứ quý</a></li>
                                <li><a href="/sim-tu-quy-giua">Sim tứ quý giữa</a></li>
                                <li><a href="/sim-tam-hoa">Sim Tam hoa</a></li>
                                <li><a href="/sim-tam-hoa-kep">Sim tam hoa kép</a></li>
                                <li><a href="/sim-taxi">Sim Taxi</a></li>
                                <li><a href="/sim-loc-phat">Sim Lộc phát</a></li>
                                <li><a href="/sim-than-tai">Sim Thần tài</a></li>
                                <li><a href="/sim-ong-dia">Sim Ông địa</a></li>
                                <li><a href="/sim-lap-kep">Sim Lặp kép</a></li>
                                <li><a href="/sim-ganh-dao">Sim Gánh đảo</a></li>
                                <li><a href="/sim-tien-len">Sim Tiến lên</a></li>
                                <li><a href="/sim-de-nho">Sim Dễ nhớ</a></li>
                                <li><a href="/sim-nam-sinh">Sim Năm sinh</a></li>
                                <li><a href="/sim-so-doc">Sim Số độc</a></li>
                                <li><a href="/sim-dau-so-co">Sim đầu số cổ</a></li>
                                <li><a href="/sim-tra-gop">Sim Trả Góp</a></li>
                            </ul>
                        </li>
                        <li class="parent-menu"><a href="/sim-viettel" class="hd-icon icon vt">Viettel</a></li>
                        <li class="parent-menu"><a href="/sim-vinaphone" class="hd-icon icon vn">VinaPhone</a></li>
                        <li class="parent-menu"><a href="/sim-mobifone" class="hd-icon icon mb2">Mobifone</a></li>
                        <li class="parent-menu"><a href="/sim-gmobile" class="hd-icon icon gb">Gmobile</a></li>
                        <li class="parent-menu"><a href="{{route('frontend.news.list')}}" class="hd-icon icon iinfo">Kiến thức sim</a></li>
                    </ul>
                </div>
            </amp-sidebar>

            <a href="/"><span class="header-logo"><i class="logo-header"></i></span></a>
            <span class="btn-open-menu" on="tap:sidebar.open" role="button" tabindex="0"><i class="icon-open-menu"></i></span>
            <div class="search-groups">
                <span><a href="/">
                        <amp-img src="{!! !empty(config('domainInfo')['logo']) ?
                config('domainInfo')['logo'] : 'frontend/icon/logo.png' !!}" height="60"
                                   width="245" alt="@if(!empty($web_title))
                        {{$web_title}}@else @yield('title')@endif" class="img-logo-header">
                        </amp-img>
                    </a>
                </span>
                <form id="timkiem" name="timkiem" method="get" action="/" action-xhr="/" target="_top">
                    <div class="form-search">
                        <input type="text" autocomplete="off" id="search"
                               name="search" required value="" placeholder="Bạn tìm sim gì hôm nay"/>
                        <input type="submit" value="Tìm" id="go-search">
                    </div>

                </form>
            </div>
            <div class="header-menu">
                <ul>
                    <li class="has-child">
                        <a href="/" class="hd-icon icon sd">Danh mục sim</a>
                        <ul class="dr-item">
                            <li><a href="/sim-vip">Sim VIP</a></li>
                            <li><a href="/sim-luc-quy">Sim Lục quý</a></li>
                            <li><a href="/sim-luc-quy-giua">Sim Lục quý giữa</a></li>
                            <li><a href="/sim-ngu-quy">Sim ngũ quý</a></li>
                            <li><a href="/sim-ngu-quy-giua">Sim ngũ quý giữa</a></li>
                            <li><a href="/sim-tu-quy">Sim Tứ quý</a></li>
                            <li><a href="/sim-tu-quy-giua">Sim tứ quý giữa</a></li>
                            <li><a href="/sim-tam-hoa">Sim Tam hoa</a></li>
                            <li><a href="/sim-tam-hoa-kep">Sim tam hoa kép</a></li>
                            <li><a href="/sim-taxi">Sim Taxi</a></li>
                            <li><a href="/sim-loc-phat">Sim Lộc phát</a></li>
                            <li><a href="/sim-than-tai">Sim Thần tài</a></li>
                            <li><a href="/sim-ong-dia">Sim Ông địa</a></li>
                            <li><a href="/sim-lap-kep">Sim Lặp kép</a></li>
                            <li><a href="/sim-ganh-dao">Sim Gánh đảo</a></li>
                            <li><a href="/sim-tien-len">Sim Tiến lên</a></li>
                            <li><a href="/sim-de-nho">Sim Dễ nhớ</a></li>
                            <li><a href="/sim-nam-sinh">Sim Năm sinh</a></li>
                            <li><a href="/sim-so-doc">Sim Số độc</a></li>
                            <li><a href="/sim-dau-so-co">Sim đầu số cổ</a></li>
                            <li><a href="/sim-tra-gop">Sim Trả Góp</a></li>
                        </ul>
                    </li>
                    <li><a href="/sim-viettel" class="hd-icon icon vt">Viettel</a></li>
                    <li><a href="/sim-vinaphone" class="hd-icon icon vn">VinaPhone</a></li>
                    <li><a href="/sim-mobifone" class="hd-icon icon mb2">Mobifone</a></li>
                    <li><a href="/sim-gmobile" class="hd-icon icon gb">Gmobile</a></li>
                    <li><a href="{{route('frontend.news.list')}}" class="hd-icon icon iinfo">Kiến thức sim</a></li>
                </ul>
            </div>
        </div>
    </div>
</header>
<section id="main-content">
    <div class="container">
        <div class="row">
            <section class="content">
                @yield('content')
            </section>
        </div>
    </div>
</section>
<!-- Footer -->
<div class="clearfix"></div>
<footer>
    <div class="container">
        <div class="hotlinebottom" id="hlbt">
            <a class="hotline" href="tel:{{$hotline['hot']}}">
                <div id="callnumber">
                    <span>&nbsp;</span>
                </div>
                <div>
                </div>{{$hotline['hot']}}
            </a>
        </div>
        <div class="row row-f-30">
            <div class="col-md-4">
                <div class="logo-footer">
                    <amp-img src="{!! !empty(config('domainInfo')['logo']) ? config('domainInfo')['logo'] : url('frontend/icon/logo.png') !!}" class="img-responsive" alt="{{config('domainInfo')['domain_name']}}" width="308" height="74"></amp-img>
                </div>
                <br>
                {!! !empty(config('domainInfo')['footer_box_1']) ? config('domainInfo')['footer_box_1'] : '' !!}
                <p class="rcopyright">Copyright {{date('Y')}} &copy; {{config('domainInfo')['domain_name']}}</p>
            </div>
            <div class="col-md-8">
                <div class="list-company row clearfix">
                    {!! !empty(config('domainInfo')['footer_box_2']) ? config('domainInfo')['footer_box_2'] : '' !!}
                    {!! !empty(config('domainInfo')['footer_box_3']) ? config('domainInfo')['footer_box_3'] : '' !!}
                </div>
            </div>
        </div>
    </div>
</footer>
</body>
</html>
