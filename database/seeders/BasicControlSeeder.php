<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class BasicControlSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function emailDescription()
    {
        return '<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width">
<style type="text/css">
    @media only screen and (min-width: 620px) {
        * [lang=x-wrapper] h1 {
        }

        * [lang=x-wrapper] h1 {
            font-size: 26px !important;
            line-height: 34px !important
        }

        * [lang=x-wrapper] h2 {
        }

        * [lang=x-wrapper] h2 {
            font-size: 20px !important;
            line-height: 28px !important
        }

        * [lang=x-wrapper] h3 {
        }

        * [lang=x-layout__inner] p,
        * [lang=x-layout__inner] ol,
        * [lang=x-layout__inner] ul {
        }

        * div [lang=x-size-8] {
            font-size: 8px !important;
            line-height: 14px !important
        }

        * div [lang=x-size-9] {
            font-size: 9px !important;
            line-height: 16px !important
        }

        * div [lang=x-size-10] {
            font-size: 10px !important;
            line-height: 18px !important
        }

        * div [lang=x-size-11] {
            font-size: 11px !important;
            line-height: 19px !important
        }

        * div [lang=x-size-12] {
            font-size: 12px !important;
            line-height: 19px !important
        }

        * div [lang=x-size-13] {
            font-size: 13px !important;
            line-height: 21px !important
        }

        * div [lang=x-size-14] {
            font-size: 14px !important;
            line-height: 21px !important
        }

        * div [lang=x-size-15] {
            font-size: 15px !important;
            line-height: 23px !important
        }

        * div [lang=x-size-16] {
            font-size: 16px !important;
            line-height: 24px !important
        }

        * div [lang=x-size-17] {
            font-size: 17px !important;
            line-height: 26px !important
        }

        * div [lang=x-size-18] {
            font-size: 18px !important;
            line-height: 26px !important
        }

        * div [lang=x-size-18] {
            font-size: 18px !important;
            line-height: 26px !important
        }

        * div [lang=x-size-20] {
            font-size: 20px !important;
            line-height: 28px !important
        }

        * div [lang=x-size-22] {
            font-size: 22px !important;
            line-height: 31px !important
        }

        * div [lang=x-size-24] {
            font-size: 24px !important;
            line-height: 32px !important
        }

        * div [lang=x-size-26] {
            font-size: 26px !important;
            line-height: 34px !important
        }

        * div [lang=x-size-28] {
            font-size: 28px !important;
            line-height: 36px !important
        }

        * div [lang=x-size-30] {
            font-size: 30px !important;
            line-height: 38px !important
        }

        * div [lang=x-size-32] {
            font-size: 32px !important;
            line-height: 40px !important
        }

        * div [lang=x-size-34] {
            font-size: 34px !important;
            line-height: 43px !important
        }

        * div [lang=x-size-36] {
            font-size: 36px !important;
            line-height: 43px !important
        }

        * div [lang=x-size-40] {
            font-size: 40px !important;
            line-height: 47px !important
        }

        * div [lang=x-size-44] {
            font-size: 44px !important;
            line-height: 50px !important
        }

        * div [lang=x-size-48] {
            font-size: 48px !important;
            line-height: 54px !important
        }

        * div [lang=x-size-56] {
            font-size: 56px !important;
            line-height: 60px !important
        }

        * div [lang=x-size-64] {
            font-size: 64px !important;
            line-height: 63px !important
        }
    }
</style>
<style type="text/css">
    body {
        margin: 0;
        padding: 0;
    }

    table {
        border-collapse: collapse;
        table-layout: fixed;
    }

    * {
        line-height: inherit;
    }

    [x-apple-data-detectors],
    [href^="tel"],
    [href^="sms"] {
        color: inherit !important;
        text-decoration: none !important;
    }

    .wrapper .footer__share-button a:hover,
    .wrapper .footer__share-button a:focus {
        color: #ffffff !important;
    }

    .btn a:hover,
    .btn a:focus,
    .footer__share-button a:hover,
    .footer__share-button a:focus,
    .email-footer__links a:hover,
    .email-footer__links a:focus {
        opacity: 0.8;
    }

    .preheader,
    .header,
    .layout,
    .column {
        transition: width 0.25s ease-in-out, max-width 0.25s ease-in-out;
    }

    .layout,
    .header {
        max-width: 400px !important;
        -fallback-width: 95% !important;
        width: calc(100% - 20px) !important;
    }

    div.preheader {
        max-width: 360px !important;
        -fallback-width: 90% !important;
        width: calc(100% - 60px) !important;
    }

    .snippet,
    .webversion {
        Float: none !important;
    }

    .column {
        max-width: 400px !important;
        width: 100% !important;
    }

    .fixed-width.has-border {
        max-width: 402px !important;
    }

    .fixed-width.has-border .layout__inner {
        box-sizing: border-box;
    }

    .snippet,
    .webversion {
        width: 50% !important;
    }

    .ie .btn {
        width: 100%;
    }

    .ie .column,
    [owa] .column,
    .ie .gutter,
    [owa] .gutter {
        display: table-cell;
        float: none !important;
        vertical-align: top;
    }

    .ie div.preheader,
    [owa] div.preheader,
    .ie .email-footer,
    [owa] .email-footer {
        max-width: 560px !important;
        width: 560px !important;
    }

    .ie .snippet,
    [owa] .snippet,
    .ie .webversion,
    [owa] .webversion {
        width: 280px !important;
    }

    .ie .header,
    [owa] .header,
    .ie .layout,
    [owa] .layout,
    .ie .one-col .column,
    [owa] .one-col .column {
        max-width: 600px !important;
        width: 600px !important;
    }

    .ie .fixed-width.has-border,
    [owa] .fixed-width.has-border,
    .ie .has-gutter.has-border,
    [owa] .has-gutter.has-border {
        max-width: 602px !important;
        width: 602px !important;
    }

    .ie .two-col .column,
    [owa] .two-col .column {
        width: 300px !important;
    }

    .ie .three-col .column,
    [owa] .three-col .column,
    .ie .narrow,
    [owa] .narrow {
        width: 200px !important;
    }

    .ie .wide,
    [owa] .wide {
        width: 400px !important;
    }

    .ie .two-col.has-gutter .column,
    [owa] .two-col.x_has-gutter .column {
        width: 290px !important;
    }

    .ie .three-col.has-gutter .column,
    [owa] .three-col.x_has-gutter .column,
    .ie .has-gutter .narrow,
    [owa] .has-gutter .narrow {
        width: 188px !important;
    }

    .ie .has-gutter .wide,
    [owa] .has-gutter .wide {
        width: 394px !important;
    }

    .ie .two-col.has-gutter.has-border .column,
    [owa] .two-col.x_has-gutter.x_has-border .column {
        width: 292px !important;
    }

    .ie .three-col.has-gutter.has-border .column,
    [owa] .three-col.x_has-gutter.x_has-border .column,
    .ie .has-gutter.has-border .narrow,
    [owa] .has-gutter.x_has-border .narrow {
        width: 190px !important;
    }

    .ie .has-gutter.has-border .wide,
    [owa] .has-gutter.x_has-border .wide {
        width: 396px !important;
    }

    .ie .fixed-width .layout__inner {
        border-left: 0 none white !important;
        border-right: 0 none white !important;
    }

    .ie .layout__edges {
        display: none;
    }

    .mso .layout__edges {
        font-size: 0;
    }

    .layout-fixed-width,
    .mso .layout-full-width {
        background-color: #ffffff;
    }

    @media only screen and (min-width: 620px) {

        .column,
        .gutter {
            display: table-cell;
            Float: none !important;
            vertical-align: top;
        }

        div.preheader,
        .email-footer {
            max-width: 560px !important;
            width: 560px !important;
        }

        .snippet,
        .webversion {
            width: 280px !important;
        }

        .header,
        .layout,
        .one-col .column {
            max-width: 600px !important;
            width: 600px !important;
        }

        .fixed-width.has-border,
        .fixed-width.ecxhas-border,
        .has-gutter.has-border,
        .has-gutter.ecxhas-border {
            max-width: 602px !important;
            width: 602px !important;
        }

        .two-col .column {
            width: 300px !important;
        }

        .three-col .column,
        .column.narrow {
            width: 200px !important;
        }

        .column.wide {
            width: 400px !important;
        }

        .two-col.has-gutter .column,
        .two-col.ecxhas-gutter .column {
            width: 290px !important;
        }

        .three-col.has-gutter .column,
        .three-col.ecxhas-gutter .column,
        .has-gutter .narrow {
            width: 188px !important;
        }

        .has-gutter .wide {
            width: 394px !important;
        }

        .two-col.has-gutter.has-border .column,
        .two-col.ecxhas-gutter.ecxhas-border .column {
            width: 292px !important;
        }

        .three-col.has-gutter.has-border .column,
        .three-col.ecxhas-gutter.ecxhas-border .column,
        .has-gutter.has-border .narrow,
        .has-gutter.ecxhas-border .narrow {
            width: 190px !important;
        }

        .has-gutter.has-border .wide,
        .has-gutter.ecxhas-border .wide {
            width: 396px !important;
        }
    }

    @media only screen and (-webkit-min-device-pixel-ratio: 2), only screen and (min--moz-device-pixel-ratio: 2), only screen and (-o-min-device-pixel-ratio: 2/1), only screen and (min-device-pixel-ratio: 2), only screen and (min-resolution: 192dpi), only screen and (min-resolution: 2dppx) {
        .fblike {
            background-image: url(https://i3.createsend1.com/static/eb/customise/13-the-blueprint-3/images/fblike@2x.png) !important;
        }

        .tweet {
            background-image: url(https://i4.createsend1.com/static/eb/customise/13-the-blueprint-3/images/tweet@2x.png) !important;
        }

        .linkedinshare {
            background-image: url(https://i6.createsend1.com/static/eb/customise/13-the-blueprint-3/images/lishare@2x.png) !important;
        }

        .forwardtoafriend {
            background-image: url(https://i5.createsend1.com/static/eb/customise/13-the-blueprint-3/images/forward@2x.png) !important;
        }
    }

    @media (max-width: 321px) {
        .fixed-width.has-border .layout__inner {
            border-width: 1px 0 !important;
        }

        .layout,
        .column {
            min-width: 320px !important;
            width: 320px !important;
        }

        .border {
            display: none;
        }
    }

    .mso div {
        border: 0 none white !important;
    }

    .mso .w560 .divider {
        margin-left: 260px !important;
        margin-right: 260px !important;
    }

    .mso .w360 .divider {
        margin-left: 160px !important;
        margin-right: 160px !important;
    }

    .mso .w260 .divider {
        margin-left: 110px !important;
        margin-right: 110px !important;
    }

    .mso .w160 .divider {
        margin-left: 60px !important;
        margin-right: 60px !important;
    }

    .mso .w354 .divider {
        margin-left: 157px !important;
        margin-right: 157px !important;
    }

    .mso .w250 .divider {
        margin-left: 105px !important;
        margin-right: 105px !important;
    }

    .mso .w148 .divider {
        margin-left: 54px !important;
        margin-right: 54px !important;
    }

    .mso .font-avenir,
    .mso .font-cabin,
    .mso .font-open-sans,
    .mso .font-ubuntu {
        font-family: sans-serif !important;
    }

    .mso .font-bitter,
    .mso .font-merriweather,
    .mso .font-pt-serif {
        font-family: Georgia, serif !important;
    }

    .mso .font-lato,
    .mso .font-roboto {
        font-family: Tahoma, sans-serif !important;
    }

    .mso .font-pt-sans {
        font-family: "Trebuchet MS", sans-serif !important;
    }

    .mso .footer__share-button p {
        margin: 0;
    }

    @media only screen and (min-width: 620px) {
        .wrapper .size-8 {
            font-size: 8px !important;
            line-height: 14px !important;
        }

        .wrapper .size-9 {
            font-size: 9px !important;
            line-height: 16px !important;
        }

        .wrapper .size-10 {
            font-size: 10px !important;
            line-height: 18px !important;
        }

        .wrapper .size-11 {
            font-size: 11px !important;
            line-height: 19px !important;
        }

        .wrapper .size-12 {
            font-size: 12px !important;
            line-height: 19px !important;
        }

        .wrapper .size-13 {
            font-size: 13px !important;
            line-height: 21px !important;
        }

        .wrapper .size-14 {
            font-size: 14px !important;
            line-height: 21px !important;
        }

        .wrapper .size-15 {
            font-size: 15px !important;
            line-height: 23px !important;
        }

        .wrapper .size-16 {
            font-size: 16px !important;
            line-height: 24px !important;
        }

        .wrapper .size-17 {
            font-size: 17px !important;
            line-height: 26px !important;
        }

        .wrapper .size-18 {
            font-size: 18px !important;
            line-height: 26px !important;
        }

        .wrapper .size-20 {
            font-size: 20px !important;
            line-height: 28px !important;
        }

        .wrapper .size-22 {
            font-size: 22px !important;
            line-height: 31px !important;
        }

        .wrapper .size-24 {
            font-size: 24px !important;
            line-height: 32px !important;
        }

        .wrapper .size-26 {
            font-size: 26px !important;
            line-height: 34px !important;
        }

        .wrapper .size-28 {
            font-size: 28px !important;
            line-height: 36px !important;
        }

        .wrapper .size-30 {
            font-size: 30px !important;
            line-height: 38px !important;
        }

        .wrapper .size-32 {
            font-size: 32px !important;
            line-height: 40px !important;
        }

        .wrapper .size-34 {
            font-size: 34px !important;
            line-height: 43px !important;
        }

        .wrapper .size-36 {
            font-size: 36px !important;
            line-height: 43px !important;
        }

        .wrapper .size-40 {
            font-size: 40px !important;
            line-height: 47px !important;
        }

        .wrapper .size-44 {
            font-size: 44px !important;
            line-height: 50px !important;
        }

        .wrapper .size-48 {
            font-size: 48px !important;
            line-height: 54px !important;
        }

        .wrapper .size-56 {
            font-size: 56px !important;
            line-height: 60px !important;
        }

        .wrapper .size-64 {
            font-size: 64px !important;
            line-height: 63px !important;
        }
    }

    .mso .size-8,
    .ie .size-8 {
        font-size: 8px !important;
        line-height: 14px !important;
    }

    .mso .size-9,
    .ie .size-9 {
        font-size: 9px !important;
        line-height: 16px !important;
    }

    .mso .size-10,
    .ie .size-10 {
        font-size: 10px !important;
        line-height: 18px !important;
    }

    .mso .size-11,
    .ie .size-11 {
        font-size: 11px !important;
        line-height: 19px !important;
    }

    .mso .size-12,
    .ie .size-12 {
        font-size: 12px !important;
        line-height: 19px !important;
    }

    .mso .size-13,
    .ie .size-13 {
        font-size: 13px !important;
        line-height: 21px !important;
    }

    .mso .size-14,
    .ie .size-14 {
        font-size: 14px !important;
        line-height: 21px !important;
    }

    .mso .size-15,
    .ie .size-15 {
        font-size: 15px !important;
        line-height: 23px !important;
    }

    .mso .size-16,
    .ie .size-16 {
        font-size: 16px !important;
        line-height: 24px !important;
    }

    .mso .size-17,
    .ie .size-17 {
        font-size: 17px !important;
        line-height: 26px !important;
    }

    .mso .size-18,
    .ie .size-18 {
        font-size: 18px !important;
        line-height: 26px !important;
    }

    .mso .size-20,
    .ie .size-20 {
        font-size: 20px !important;
        line-height: 28px !important;
    }

    .mso .size-22,
    .ie .size-22 {
        font-size: 22px !important;
        line-height: 31px !important;
    }

    .mso .size-24,
    .ie .size-24 {
        font-size: 24px !important;
        line-height: 32px !important;
    }

    .mso .size-26,
    .ie .size-26 {
        font-size: 26px !important;
        line-height: 34px !important;
    }

    .mso .size-28,
    .ie .size-28 {
        font-size: 28px !important;
        line-height: 36px !important;
    }

    .mso .size-30,
    .ie .size-30 {
        font-size: 30px !important;
        line-height: 38px !important;
    }

    .mso .size-32,
    .ie .size-32 {
        font-size: 32px !important;
        line-height: 40px !important;
    }

    .mso .size-34,
    .ie .size-34 {
        font-size: 34px !important;
        line-height: 43px !important;
    }

    .mso .size-36,
    .ie .size-36 {
        font-size: 36px !important;
        line-height: 43px !important;
    }

    .mso .size-40,
    .ie .size-40 {
        font-size: 40px !important;
        line-height: 47px !important;
    }

    .mso .size-44,
    .ie .size-44 {
        font-size: 44px !important;
        line-height: 50px !important;
    }

    .mso .size-48,
    .ie .size-48 {
        font-size: 48px !important;
        line-height: 54px !important;
    }

    .mso .size-56,
    .ie .size-56 {
        font-size: 56px !important;
        line-height: 60px !important;
    }

    .mso .size-64,
    .ie .size-64 {
        font-size: 64px !important;
        line-height: 63px !important;
    }

    .footer__share-button p {
        margin: 0;
    }
</style>

<title></title>
<!--[if !mso]><!-->
<style type="text/css">
    @import url(https://fonts.googleapis.com/css?family=Bitter:400,700,400italic|Cabin:400,700,400italic,700italic|Open+Sans:400italic,700italic,700,400);
</style>
<link href="https://fonts.googleapis.com/css?family=Bitter:400,700,400italic|Cabin:400,700,400italic,700italic|Open+Sans:400italic,700italic,700,400" rel="stylesheet" type="text/css">
<!--<![endif]-->
<style type="text/css">
    body {
        background-color: #f5f7fa
    }

    .mso h1 {
    }

    .mso h1 {
        font-family: sans-serif !important
    }

    .mso h2 {
    }

    .mso h3 {
    }

    .mso .column,
    .mso .column__background td {
    }

    .mso .column,
    .mso .column__background td {
        font-family: sans-serif !important
    }

    .mso .btn a {
    }

    .mso .btn a {
        font-family: sans-serif !important
    }

    .mso .webversion,
    .mso .snippet,
    .mso .layout-email-footer td,
    .mso .footer__share-button p {
    }

    .mso .webversion,
    .mso .snippet,
    .mso .layout-email-footer td,
    .mso .footer__share-button p {
        font-family: sans-serif !important
    }

    .mso .logo {
    }

    .mso .logo {
        font-family: Tahoma, sans-serif !important
    }

    .logo a:hover,
    .logo a:focus {
        color: #859bb1 !important
    }

    .mso .layout-has-border {
        border-top: 1px solid #b1c1d8;
        border-bottom: 1px solid #b1c1d8
    }

    .mso .layout-has-bottom-border {
        border-bottom: 1px solid #b1c1d8
    }

    .mso .border,
    .ie .border {
        background-color: #b1c1d8
    }

    @media only screen and (min-width: 620px) {
        .wrapper h1 {
        }

        .wrapper h1 {
            font-size: 26px !important;
            line-height: 34px !important
        }

        .wrapper h2 {
        }

        .wrapper h2 {
            font-size: 20px !important;
            line-height: 28px !important
        }

        .wrapper h3 {
        }

        .column p,
        .column ol,
        .column ul {
        }
    }

    .mso h1,
    .ie h1 {
    }

    .mso h1,
    .ie h1 {
        font-size: 26px !important;
        line-height: 34px !important
    }

    .mso h2,
    .ie h2 {
    }

    .mso h2,
    .ie h2 {
        font-size: 20px !important;
        line-height: 28px !important
    }

    .mso h3,
    .ie h3 {
    }

    .mso .layout__inner p,
    .ie .layout__inner p,
    .mso .layout__inner ol,
    .ie .layout__inner ol,
    .mso .layout__inner ul,
    .ie .layout__inner ul {
    }
</style>
<meta name="robots" content="noindex,nofollow">

<meta property="og:title" content="Just One More Step">

<link href="https://css.createsend1.com/css/social.min.css?h=0ED47CE120160920" media="screen,projection" rel="stylesheet" type="text/css">


<div class="wrapper" style="min-width: 320px;background-color: #f5f7fa;" lang="x-wrapper">
    <div class="preheader" style="margin: 0 auto;max-width: 560px;min-width: 280px; width: 280px;">
        <div style="border-collapse: collapse;display: table;width: 100%;">
            <div class="snippet" style="display: table-cell;Float: left;font-size: 12px;line-height: 19px;max-width: 280px;min-width: 140px; width: 140px;padding: 10px 0 5px 0;color: #b9b9b9;">
            </div>
            <div class="webversion" style="display: table-cell;Float: left;font-size: 12px;line-height: 19px;max-width: 280px;min-width: 139px; width: 139px;padding: 10px 0 5px 0;text-align: right;color: #b9b9b9;">
            </div>
        </div>

        <div class="layout one-col fixed-width" style="margin: 0 auto;max-width: 600px;min-width: 320px; width: 320px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;">
            <div class="layout__inner" style="border-collapse: collapse;display: table;width: 100%;background-color: #c4e5dc;" lang="x-layout__inner">
                <div class="column" style="text-align: left;color: #60666d;font-size: 14px;line-height: 21px;max-width:600px;min-width:320px;">
                    <div style="margin-left: 20px;margin-right: 20px;margin-top: 24px;margin-bottom: 24px;">
                        <h1 style="margin-top: 0;margin-bottom: 0;font-style: normal;font-weight: normal;color: #44a8c7;font-size: 36px;line-height: 43px;font-family: bitter,georgia,serif;text-align: center;">
                            <img style="width: 200px;" src="https://bug-finder.s3.ap-southeast-1.amazonaws.com/assets/logo/header-logo.svg" data-filename="imageedit_76_3542310111.png"></h1>
                    </div>
                </div>
            </div>

            <div class="layout one-col fixed-width" style="margin: 0 auto;max-width: 600px;min-width: 320px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;">
                <div class="layout__inner" style="border-collapse: collapse;display: table;width: 100%;background-color: #ffffff;" lang="x-layout__inner">
                    <div class="column" style="text-align: left; background: rgb(237, 241, 235); line-height: 21px; max-width: 600px; min-width: 320px; width: 320px;">

                        <div style="color: rgb(96, 102, 109); font-size: 14px; margin-left: 20px; margin-right: 20px; margin-top: 24px;">
                            <div style="line-height:10px;font-size:1px">&nbsp;</div>
                        </div>

                        <div style="margin-left: 20px; margin-right: 20px;">

                            <p style="color: rgb(96, 102, 109); font-size: 14px; margin-top: 16px; margin-bottom: 0px;"><strong>Hello [[name]],</strong></p>
                            <p style="color: rgb(96, 102, 109); font-size: 14px; margin-top: 20px; margin-bottom: 20px;"><strong>[[message]]</strong></p>
                            <p style="margin-top: 20px; margin-bottom: 20px;"><strong style="color: rgb(96, 102, 109); font-size: 14px;">Sincerely,<br>Team&nbsp;</strong><font color="#60666d"><b>Pay Secure</b></font></p>
                        </div>

                    </div>
                </div>
            </div>

            <div class="layout__inner" style="border-collapse: collapse;display: table;width: 100%;background-color: #2c3262; margin-bottom: 20px" lang="x-layout__inner">
                <div class="column" style="text-align: left;color: #60666d;font-size: 14px;line-height: 21px;max-width:600px;min-width:320px;">
                    <div style="margin-top: 5px;margin-bottom: 5px;">
                        <p style="margin-top: 0;margin-bottom: 0;font-style: normal;font-weight: normal;color: #ffffff;font-size: 16px;line-height: 35px;font-family: bitter,georgia,serif;text-align: center;">
                            2022 ©  All Right Reserved</p>
                    </div>
                </div>
            </div>

        </div>


        <div style="border-collapse: collapse;display: table;width: 100%;">
            <div class="snippet" style="display: table-cell;Float: left;font-size: 12px;line-height: 19px;max-width: 280px;min-width: 140px; width: 140px;padding: 10px 0 5px 0;color: #b9b9b9;">
            </div>
            <div class="webversion" style="display: table-cell;Float: left;font-size: 12px;line-height: 19px;max-width: 280px;min-width: 139px; width: 139px;padding: 10px 0 5px 0;text-align: right;color: #b9b9b9;">
            </div>
        </div>
    </div>
</div>';
    }
    public function run(): void
    {

        DB::table('basic_controls')->insert([
            'theme' => 'light',
            'site_title' => 'BugFinder',
            'primary_color' => '#ffc800',
            'secondary_color' => '#000000',
            'time_zone' => 'Africa/Addis_Ababa',
            'base_currency' => 'BDT',
            'currency_symbol' => 'TK',
            'admin_prefix' => 'admin',
            'is_currency_position' => 'left',
            'has_space_between_currency_and_amount' => 0,
            'is_force_ssl' => 0,
            'is_maintenance_mode' => 0,
            'paginate' => 20,
            'strong_password' => 0,
            'registration' => 1,
            'fraction_number' => 2,
            'sender_email' => 'support@achi.com',
            'sender_email_name' => 'Bug Admin',
            'email_description' => $this->emailDescription(),
            'push_notification' => 0,
            'in_app_notification' => 1,
            'email_notification' => 1,
            'email_verification' => 0,
            'sms_notification' => 0,
            'sms_verification' => 0,
            'tawk_id' => 'OSLDSF465',
            'tawk_status' => 0,
            'fb_messenger_status' => 0,
            'fb_app_id' => 'KLSDKF789',
            'fb_page_id' => '654646977',
            'manual_recaptcha' => 0,
            'google_recaptcha' => 0,
            'google_reCaptcha_admin_login' => 0,
            'google_reCaptcha_user_login' => 0,
            'google_recaptcha_user_registration' => 0,
            'manual_recaptcha_admin_login' => 0,
            'manual_recaptcha_user_login' => 0,
            'manual_recaptcha_user_registration' => 0,
            'measurement_id' => 'aaaaaa',
            'analytic_status' => 0,
            'error_log' => 0,
            'is_active_cron_notification' => 1,
            'logo' => 'logo/3vfg4JUW69BzbVwgCSiyTT51UAAdCl1tM8R5vqsh.png',
            'logo_driver' => 'local',
            'favicon' => 'logo/EwqbNbuOqFpDcLbXpvCAUNXdZeO5UGLF20mKolXQ.jpg',
            'favicon_driver' => 'local',
            'admin_logo' => 'logo/v60rNnTRYGSMvavNZLihKHQzoRV3bgQ7h9em22EW.png',
            'admin_logo_driver' => 'local',
            'admin_dark_mode_logo' => 'logo/d5KCYnDyidcnoJ3F1Kxl91YaDMtsriFjW4ViZFBr.png',
            'admin_dark_mode_logo_driver' => 'local',
            'currency_layer_access_key' => 'c4d1082c39633125a67a2b9dd979f7ce',
            'currency_layer_auto_update_at' => 'everyMinute',
            'currency_layer_auto_update' => 1,
            'coin_market_cap_app_key' => '726ffba5-8523-4071-92d4-1775dbc481c4',
            'coin_market_cap_auto_update_at' => 'everyMinute',
            'coin_market_cap_auto_update' => 1,
            'automatic_payout_permission' => 0,
            'date_time_format' => 'd M Y',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
