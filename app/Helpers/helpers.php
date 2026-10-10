<?php

use App\Models\BasicControl;
use App\Models\Content;
use App\Models\ContentDetails;
use App\Models\ManageMenu;
use App\Models\Page;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Models\Language;
use App\Models\PageDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


if (!function_exists('getTheme')) {
    function getTheme()
    {
        return session('active_theme') ?? basicControl()->theme ?? 'light';
    }
}

if (!function_exists('template')) {
    function template($asset = false)
    {
        $theme = session('active_theme') ?? basicControl()->theme ;
        if ($asset) {
            return 'assets/themes/' . $theme . '/';
        }
        return 'themes.' . $theme . '.';
    }
}

if (!function_exists('footerData')) {
    function footerData()
    {
        $selectedTheme = session('active_theme') ?? basicControl()->theme;
        $extraInfo = Cache::remember("content_extra_info_{$selectedTheme}", now()->addMinutes(60),
            function () use ($selectedTheme) {
                return ContentDetails::whereHas('content', function ($query) use ($selectedTheme) {
                    return $query->whereIn('name', ['contact', 'social'])->where('theme', $selectedTheme);
                })->get()->groupBy('content.name');
            });
        $languages = Cache::remember('active_languages', now()->addMinutes(60), function () {
            return Language::query()->orderBy('default_status', 'desc')->where('status', 1)->get();
        });
        return [
            'extraInfo' => $extraInfo,
            'languages' => $languages,
        ];
    }
}

if (!function_exists('getThemesNames')) {
    function getThemesNames()
    {
        $directory = resource_path('views/themes');
        return File::isDirectory($directory) ? array_map('basename', File::directories($directory)) : [];
    }
}

if (!function_exists('stringToTitle')) {
    function stringToTitle($string)
    {
        return implode(' ', array_map('ucwords', explode(' ', preg_replace('/[^a-zA-Z0-9]+/', ' ', $string))));
    }
}

if (!function_exists('getTitle')) {
    function getTitle($title)
    {
        if ($title == "sms") {
            return strtoupper(preg_replace('/[^A-Za-z0-9]/', ' ', $title));
        }
        return ucwords(preg_replace('/[^A-Za-z0-9]/', ' ', $title));
    }
}

if (!function_exists('getRoute')) {
    function getRoute($route, $params = null)
    {
        return isset($params) ? route($route, $params) : route($route);
    }
}

if (!function_exists('getPageSections')) {
    function getPageSections()
    {
        $sectionsPath = resource_path('views/') . str_replace('.', '/', template()) . 'sections';
        $pattern = $sectionsPath . '/*';
        $files = glob($pattern);

        $fileBaseNames = [];

        foreach ($files as $file) {
            if (is_file($file)) {
                $basename = basename($file);
                $basenameWithoutExtension = str_replace('.blade.php', '', $basename);
                $contentImage = config('contents.'. getTheme() .'.'. $basenameWithoutExtension . '.preview');
                $fileBaseNames[$basenameWithoutExtension] = [
                    'basename' => $basenameWithoutExtension,
                    'preview' => $contentImage,
                    'optionText' => ucwords(stringToTitle(str_replace(basicControl()->theme, '', $basenameWithoutExtension))),
                ];
            }
        }
        return $fileBaseNames;
    }
}

if (!function_exists('hex2rgba')) {
    function hex2rgba($color, $opacity = false)
    {
        $default = 'rgb(0,0,0)';

        if (empty($color))
            return $default;

        if ($color[0] == '#') {
            $color = substr($color, 1);
        }

        if (strlen($color) == 6) {
            $hex = array($color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5]);
        } elseif (strlen($color) == 3) {
            $hex = array($color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2]);
        } else {
            return $default;
        }

        $rgb = array_map('hexdec', $hex);

        if ($opacity) {
            if (abs($opacity) > 1)
                $opacity = 1.0;
            $output = 'rgba(' . implode(",", $rgb) . ',' . $opacity . ')';
        } else {
            $output = 'rgb(' . implode(",", $rgb) . ')';
        }
        return $output;
    }
}


function customDate($date, $format = 'd M, Y')
{
    return date($format, strtotime($date));
}

if (!function_exists('customDateTime')) {
    function customDateTime($date, $format = 'F j, Y h:i A')
    {
        // You can customize the format if required
        $format = 'F j, Y h:i A'; // Format as "October 30, 2024 02:30 PM"

        return date($format, strtotime($date));
    }
}

if (!function_exists('dateTime')) {
    function dateTime($date, $format = 'd/m/Y H:i')
    {
        $format = basicControl()->date_time_format;
        return date($format, strtotime($date));
    }
}

if (!function_exists('basicControl')) {
    function basicControl()
    {
        // Skip session operations in CLI (artisan) context
        if (app()->runningInConsole() === false) {
            if (session()->get('themeMode') == null) {
                session()->put('themeMode', 'auto');
            }
        }

        try {
            DB::connection()->getPdo();
            $configure = \Cache::get('ConfigureSetting');
            if (!$configure) {
                $configure = BasicControl::firstOrCreate();
                \Cache::put('ConfigureSetting', $configure);
            }

            return $configure;
        } catch (\Exception $e) {
            // In CLI context throw the exception; in web context die with message
            if (app()->runningInConsole()) {
                throw $e;
            }
            die("Unable to establish a connection to the database. Please check your connection settings and try again later");
        }
    }
}

if (!function_exists('checkTo')) {
    function checkTo($currencies, $selectedCurrency = 'USD')
    {
        foreach ($currencies as $key => $currency) {
            if (property_exists($currency, strtoupper($selectedCurrency))) {
                return $key;
            }
        }
    }
}


if (!function_exists('controlPanelRoutes')) {
    function controlPanelRoutes()
    {
        $listRoutes = collect([]);
        $listRoutes->push(config('generalsettings.settings'));
        $listRoutes->push(config('generalsettings.plugin'));
        $listRoutes->push(config('generalsettings.in-app-notification'));
        $listRoutes->push(config('generalsettings.push-notification'));
        $listRoutes->push(config('generalsettings.email'));
        $listRoutes->push(config('generalsettings.sms'));
        $list = $listRoutes->collapse()->map(function ($item) {
            return $item['route'];
        })->values()->push('admin.settings')->unique();
        return $list;
    }
}


if (!function_exists('menuActive')) {
    function menuActive($routeName, $type = null)
    {
        $class = 'active';
        if ($type == 3) {
            $class = 'active collapsed';
        } elseif ($type == 2) {
            $class = 'show';
        }

        if (is_array($routeName)) {
            foreach ($routeName as $key => $value) {
                if (request()->routeIs($value)) {
                    return $class;
                }
            }
        } elseif (request()->routeIs($routeName)) {
            return $class;
        }
    }
}

function userMenuActive($routeName, $type = null)
{
    $class = 'active';
    if ($type == 3) {
        $class = 'selected';
    } elseif ($type == 2) {
        $class = 'has-arrow active';
    } elseif ($type == 1) {
        $class = 'in';
    } elseif ($type == 4) {
        $class = 'show';
    }
    if (is_array($routeName)) {
        foreach ($routeName as $key => $value) {
            if (request()->routeIs($value)) {
                return $class;
            }
        }
    } elseif (request()->routeIs($routeName)) {
        return $class;
    }
}

if (!function_exists('isMenuActive')) {
    function isMenuActive($routes, $type = 0)
    {
        $class = [
            '0' => 'active',
            '1' => 'style=display:block',
            '2' => true
        ];

        if (is_array($routes)) {
            foreach ($routes as $key => $route) {
                if (request()->routeIs($route)) {
                    return $class[$type];
                }
            }
        } elseif (request()->routeIs($routes)) {
            return $class[$type];
        }

        if ($type == 1) {
            return 'style=display:none';
        } else {
            return false;
        }
    }
}


if (!function_exists('strRandom')) {
    function strRandom($length = 12)
    {
        $characters = 'ABCDEFGHJKMNOPQRSTUVWXYZ123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}


if (!function_exists('getFile')) {
    function getFile($disk = 'local', $image = '', $upload = false)
    {
        $default = ($upload == true) ? asset(config('filelocation.default2')) : asset(config('filelocation.default'));
        try {
            if ($disk == 'local') {
                $localImage = asset('/assets/upload') . '/' . $image;
                return !empty($image) && Storage::disk($disk)->exists($image) ? $localImage : $default;
            } else {
                return !empty($image) && Storage::disk($disk)->exists($image) ? Storage::disk($disk)->url($image) : $default;
            }
        } catch (Exception $e) {
            return $default;
        }
    }
}

if (!function_exists('getFileForEdit')) {
    function getFileForEdit($disk = 'local', $image = null)
    {
        try {
            if ($disk == 'local') {
                $localImage = asset('/assets/upload') . '/' . $image;
                return !empty($image) && Storage::disk($disk)->exists($image) ? $localImage : null;
            } else {
                return !empty($image) && Storage::disk($disk)->exists($image) ? Storage::disk($disk)->url($image) : asset(config('location.default'));
            }
        } catch (Exception $e) {
            return null;
        }
    }
}

if (!function_exists('title2snake')) {
    function title2snake($string)
    {
        return Str::title(str_replace(' ', '_', $string));
    }
}

if (!function_exists('snake2Title')) {
    function snake2Title($string)
    {
        return Str::title(str_replace('_', ' ', $string));
    }
}

if (!function_exists('kebab2Title')) {
    function kebab2Title($string)
    {
        return Str::title(str_replace('-', ' ', $string));
    }
}

if (!function_exists('getMethodCurrency')) {
    function getMethodCurrency($gateway)
    {
        foreach ($gateway->currencies as $key => $currency) {
            if (property_exists($currency, $gateway->currency)) {
                if ($key == 0) {
                    return $gateway->currency;
                } else {
                    return 'USD';
                }
            }
        }
    }
}

if (!function_exists('twoStepPrevious')) {
    function twoStepPrevious($deposit)
    {
        if ($deposit->depositable_type == \App\Models\Fund::class) {
            return route('fund.initialize');
        }
    }
}


if (!function_exists('slug')) {
    function slug($title)
    {
        return Str::slug($title);
    }
}

if (!function_exists('clean')) {
    function clean($string)
    {
        $string = str_replace(' ', '_', $string); // Replaces all spaces with hyphens.
        return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
    }
}

if (!function_exists('diffForHumans')) {
    function diffForHumans($date)
    {
        $lang = session()->get('lang');
        \Carbon\Carbon::setlocale($lang);
        return \Carbon\Carbon::parse($date)->diffForHumans();
    }
}

if (!function_exists('loopIndex')) {
    function loopIndex($object)
    {
        return ($object->currentPage() - 1) * $object->perPage() + 1;
    }
}


if (!function_exists('getProjectDirectory')) {
    function getProjectDirectory()
    {
        return str_replace((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]", "", url("/"));
    }
}

if (!function_exists('defaultLang')) {
    function defaultLang()
    {
        return Language::where('default_status', true)->first();
    }
}

if (!function_exists('removeHyphenInString')) {
    function removeHyphenInString($string)
    {
        return str_replace("_", " ", $string);
    }
}

function updateBalance($user_id, $amount, $action = 0)
{
    $user = User::where('id', $user_id)->firstOr(function () {
        throw new \Exception('User not found!');
    });
    if ($amount > $user->balance) {
        return back()->with('error', 'Insufficient Balance to deducted.');
    }
    if ($action == 1) { //add money
        $balance = $user->balance + $amount;
        $user->balance = $balance;
    } elseif ($action == 0) { //deduct money
        $balance = $user->balance - $amount;
        $user->balance = $balance;
    }
    $user->save();
}




if (!function_exists('currencyPosition')) {
    function currencyPosition($amount)
    {
        $basic = basicControl();
        $amount = fractionNumber($amount);
        return $basic->is_currency_position == 'left' && $basic->has_space_between_currency_and_amount ? "{$basic->currency_symbol} {$amount}" :
            ($basic->is_currency_position == 'left' && !$basic->has_space_between_currency_and_amount ? "{$basic->currency_symbol}{$amount}" :
                ($basic->is_currency_position == 'right' && $basic->has_space_between_currency_and_amount ? "{$amount} {$basic->base_currency} " :
                    "{$amount}{$basic->base_currency}"));
    }
}


if (!function_exists('fractionNumber')) {
    function fractionNumber($amount, $afterComma = true)
    {
        $basic = basicControl();
        if (!$afterComma) {
            return number_format($amount+0);
        }
        $formattedAmount  =  number_format($amount, $basic->fraction_number ?? 2);

        return rtrim(rtrim($formattedAmount, '0'), '.');

    }
}
function getAmount($amount, $length = 0)
{
    if ($amount == 0) {
        return 0;
    }
    if ($length == 0) {
        preg_match("#^([\+\-]|)([0-9]*)(\.([0-9]*?)|)(0*)$#", trim($amount), $o);
        return $o[1] . sprintf('%d', $o[2]) . ($o[3] != '.' ? $o[3] : '');
    }

    return round($amount, $length);
}


function hextorgb($hexstring)
{
    $integar = hexdec($hexstring);
    return array("red" => 0xFF & ($integar >> 0x10),
        "green" => 0xFF & ($integar >> 0x8),
        "blue" => 0xFF & $integar);
}

function renderCaptCha($rand)
{
//    session_start();
    $captcha_code = '';
    $captcha_image_height = 50;
    $captcha_image_width = 130;
    $total_characters_on_image = 6;

    $possible_captcha_letters = 'bcdfghjkmnpqrstvwxyz23456789';
    $captcha_font = 'assets/monofont.ttf';

    $random_captcha_dots = 50;
    $random_captcha_lines = 25;
    $captcha_text_color = "0x142864";
    $captcha_noise_color = "0x142864";


    $count = 0;
    while ($count < $total_characters_on_image) {
        $captcha_code .= substr(
            $possible_captcha_letters,
            mt_rand(0, strlen($possible_captcha_letters) - 1),
            1);
        $count++;
    }


    $captcha_font_size = $captcha_image_height * 0.65;
    $captcha_image = @imagecreate(
        $captcha_image_width,
        $captcha_image_height
    );

    /* setting the background, text and noise colours here */
    $background_color = imagecolorallocate(
        $captcha_image,
        255,
        255,
        255
    );

    $array_text_color = hextorgb($captcha_text_color);
    $captcha_text_color = imagecolorallocate(
        $captcha_image,
        $array_text_color['red'],
        $array_text_color['green'],
        $array_text_color['blue']
    );

    $array_noise_color = hextorgb($captcha_noise_color);
    $image_noise_color = imagecolorallocate(
        $captcha_image,
        $array_noise_color['red'],
        $array_noise_color['green'],
        $array_noise_color['blue']
    );

    /* Generate random dots in background of the captcha image */
    for ($count = 0; $count < $random_captcha_dots; $count++) {
        imagefilledellipse(
            $captcha_image,
            mt_rand(0, $captcha_image_width),
            mt_rand(0, $captcha_image_height),
            2,
            3,
            $image_noise_color
        );
    }

    /* Generate random lines in background of the captcha image */
    for ($count = 0; $count < $random_captcha_lines; $count++) {
        imageline(
            $captcha_image,
            mt_rand(0, $captcha_image_width),
            mt_rand(0, $captcha_image_height),
            mt_rand(0, $captcha_image_width),
            mt_rand(0, $captcha_image_height),
            $image_noise_color
        );
    }

    /* Create a text box and add 6 captcha letters code in it */
    $text_box = imagettfbbox(
        $captcha_font_size,
        0,
        $captcha_font,
        $captcha_code
    );
    $x = ($captcha_image_width - $text_box[4]) / 2;
    $y = ($captcha_image_height - $text_box[5]) / 2;
    imagettftext(
        $captcha_image,
        $captcha_font_size,
        0,
        $x,
        $y,
        $captcha_text_color,
        $captcha_font,
        $captcha_code
    );

    /* Show captcha image in the html page */
// defining the image type to be shown in browser widow
    header('Content-Type: image/jpeg');
    imagejpeg($captcha_image); //showing the image
    imagedestroy($captcha_image); //destroying the image instance
//    $_SESSION['captcha'] = $captcha_code;

    session()->put('captcha', $captcha_code);
}

function getIpInfo()
{
//	$ip = '210.1.246.42';
    $ip = null;
    $deep_detect = TRUE;

    if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
        $ip = $_SERVER["REMOTE_ADDR"];
        if ($deep_detect) {
            if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
                $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
    }
    $xml = @simplexml_load_file("http://www.geoplugin.net/xml.gp?ip=" . $ip);

    $country = @$xml->geoplugin_countryName;
    $city = @$xml->geoplugin_city;
    $area = @$xml->geoplugin_areaCode;
    $code = @$xml->geoplugin_countryCode;
    $long = @$xml->geoplugin_longitude;
    $lat = @$xml->geoplugin_latitude;


    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    $os_platform = "Unknown OS Platform";
    $os_array = array(
        '/windows nt 10/i' => 'Windows 10',
        '/windows nt 6.3/i' => 'Windows 8.1',
        '/windows nt 6.2/i' => 'Windows 8',
        '/windows nt 6.1/i' => 'Windows 7',
        '/windows nt 6.0/i' => 'Windows Vista',
        '/windows nt 5.2/i' => 'Windows Server 2003/XP x64',
        '/windows nt 5.1/i' => 'Windows XP',
        '/windows xp/i' => 'Windows XP',
        '/windows nt 5.0/i' => 'Windows 2000',
        '/windows me/i' => 'Windows ME',
        '/win98/i' => 'Windows 98',
        '/win95/i' => 'Windows 95',
        '/win16/i' => 'Windows 3.11',
        '/macintosh|mac os x/i' => 'Mac OS X',
        '/mac_powerpc/i' => 'Mac OS 9',
        '/linux/i' => 'Linux',
        '/ubuntu/i' => 'Ubuntu',
        '/iphone/i' => 'iPhone',
        '/ipod/i' => 'iPod',
        '/ipad/i' => 'iPad',
        '/android/i' => 'Android',
        '/blackberry/i' => 'BlackBerry',
        '/webos/i' => 'Mobile'
    );
    foreach ($os_array as $regex => $value) {
        if (preg_match($regex, $user_agent)) {
            $os_platform = $value;
        }
    }
    $browser = "Unknown Browser";
    $browser_array = array(
        '/msie/i' => 'Internet Explorer',
        '/firefox/i' => 'Firefox',
        '/safari/i' => 'Safari',
        '/chrome/i' => 'Chrome',
        '/edge/i' => 'Edge',
        '/opera/i' => 'Opera',
        '/netscape/i' => 'Netscape',
        '/maxthon/i' => 'Maxthon',
        '/konqueror/i' => 'Konqueror',
        '/mobile/i' => 'Handheld Browser'
    );
    foreach ($browser_array as $regex => $value) {
        if (preg_match($regex, $user_agent)) {
            $browser = $value;
        }
    }

    $data['country'] = $country;
    $data['city'] = $city;
    $data['area'] = $area;
    $data['code'] = $code;
    $data['long'] = $long;
    $data['lat'] = $lat;
    $data['os_platform'] = $os_platform;
    $data['browser'] = $browser;
    $data['ip'] = request()->ip();
    $data['time'] = date('d-m-Y h:i:s A');

    return $data;
}


if (!function_exists('convertRate')) {
    function convertRate($currencyCode, $payout)
    {
        $convertRate = 0;
        $rate = optional($payout->method)->convert_rate;
        if ($rate) {
            $convertRate = $rate->$currencyCode;
        }
        return (float)$convertRate;
    }
}
if (!function_exists('stringToRouteName')) {
    function stringToRouteName($string)
    {
        $result = preg_replace('/[^a-zA-Z0-9]+/', '.', $string);
        return empty($result) || $result == '.' ? 'home' : $result;
    }
}
function browserIcon($string)
{
    $list = [
        "Unknown Browser" => "unknown",
        'Internet Explorer' => 'internetExplorer',
        'Firefox' => 'firefox',
        'Safari' => 'safari',
        'Chrome' => 'chrome',
        'Edge' => 'edge',
        'Opera' => 'opera',
        'Netscape' => 'netscape',
        'Maxthon' => 'maxthon',
        'Konqueror' => 'unknown',
        'UC Browser' => 'ucBrowser',
        'Safari Browser' => 'safari'];
    return $list[$string] ?? 'unknown';

}


function deviceIcon($string)
{
    $list = [
        'Tablet' => 'bi-laptop',
        'Mobile' => 'bi-phone',
        'Computer' => 'bi-display'];
    return $list[$string] ?? '';

}

if (!function_exists('timeAgo')) {
    function timeAgo($timestamp)
    {
        //$time_now = mktime(date('h')+0,date('i')+30,date('s'));
        $datetime1 = new DateTime("now");
        $datetime2 = date_create($timestamp);
        $diff = date_diff($datetime1, $datetime2);
        $timemsg = '';
        if ($diff->y > 0) {
            $timemsg = $diff->y . ' year' . ($diff->y > 1 ? "s" : '');

        } else if ($diff->m > 0) {
            $timemsg = $diff->m . ' month' . ($diff->m > 1 ? "s" : '');
        } else if ($diff->d > 0) {
            $timemsg = $diff->d . ' day' . ($diff->d > 1 ? "s" : '');
        } else if ($diff->h > 0) {
            $timemsg = $diff->h . ' hour' . ($diff->h > 1 ? "s" : '');
        } else if ($diff->i > 0) {
            $timemsg = $diff->i . ' minute' . ($diff->i > 1 ? "s" : '');
        } else if ($diff->s > 0) {
            $timemsg = $diff->s . ' second' . ($diff->s > 1 ? "s" : '');
        }
        if ($timemsg == "")
            $timemsg = "Just now";
        else
            $timemsg = $timemsg . ' ago';

        return $timemsg;
    }
}

if (!function_exists('code')) {
    function code($length)
    {
        if ($length == 0) return 0;
        $min = pow(10, $length - 1);
        $max = 0;
        while ($length > 0 && $length--) {
            $max = ($max * 10) + 9;
        }
        return random_int($min, $max);
    }
}


if (!function_exists('recursive_array_replace')) {
    function recursive_array_replace($find, $replace, $array)
    {
        if (!is_array($array)) {
            return str_ireplace($find, $replace, $array);
        }
        $newArray = [];
        foreach ($array as $key => $value) {
            $newArray[$key] = recursive_array_replace($find, $replace, $value);
        }
        return $newArray;
    }
}

if (!function_exists('getHeaderMenuData')) {
    function getHeaderMenuData()
    {
        $selectedTheme = \basicControl()->theme;
        $menu = Cache::remember("header_manage_menu_{$selectedTheme}", now()->addMinutes(30), function () use ($selectedTheme) {
            return  ManageMenu::where('menu_section', 'header')->where('template_name', $selectedTheme)->first();
        });
        $menuData = [];
        if ($menu && $menu->menu_items){
            foreach ($menu->menu_items as $key => $menuItem) {
                if (is_numeric($key)) {
                    $pageDetails = getPageDetails($menuItem);
                    $menuIDetails = [
                        'name' => $pageDetails->page_name ?? $pageDetails->name ?? $menuItem,
                        'route' => isset($pageDetails->slug) ? route('page', $pageDetails->slug) : ($pageDetails->custom_link ?? staticPagesAndRoutes($menuItem)),
                    ];
                } elseif (is_array($menuItem)) {
                    $pageDetails = getPageDetails($key);
                    $child = getHeaderChildMenu($menuItem);
                    $menuIDetails = [
                        'name' => $pageDetails->page_name ?? $pageDetails->name,
                        'route' => isset($pageDetails->slug) ? route('page', $pageDetails->slug) : ($pageDetails->custom_link ?? staticPagesAndRoutes($key)),
                        'child' => $child
                    ];
                }
                $menuData[] = $menuIDetails;
            }

            return $menuData;
        }

    }
}

if (!function_exists('staticPagesAndRoutes')) {
    function staticPagesAndRoutes($name)
    {
        return [
            'blog' => 'blog',
        ][$name] ?? $name;
    }
}


if (!function_exists('getHeaderChildMenu')) {
    function getHeaderChildMenu($menuItem, $menuData = [])
    {
        foreach ($menuItem as $key => $item) {
            if (is_numeric($key)) {
                $pageDetails = getPageDetails($item);
                $menuData[] = [
                    'name' => $pageDetails->page_name ?? $pageDetails->name ?? $item,
                    'route' => isset($pageDetails->slug) ? route('page', $pageDetails->slug) : ($pageDetails->custom_link ?? staticPagesAndRoutes($item)),
                ];
            } elseif (is_array($item)) {
                $pageDetails = getPageDetails($key);
                $child = getHeaderChildMenu($item);
                $menuData[] = [
                    'name' => $pageDetails->page_name ?? $pageDetails->name ?? $key,
                    'route' => isset($pageDetails->slug) ? route('page', $pageDetails->slug) : ($pageDetails->custom_link ?? staticPagesAndRoutes($key)),
                    'child' => $child
                ];
            } else {
                $pageDetails = getPageDetails($key);
                $child = getHeaderChildMenu([$item]);
                $menuData[] = [
                    'name' => $pageDetails->page_name ?? $pageDetails->name ?? $key,
                    'route' => isset($pageDetails->slug) ? route('page', $pageDetails->slug) : ($pageDetails->custom_link ?? staticPagesAndRoutes($key)),
                    'child' => $child
                ];
            }
        }
        return $menuData;
    }
}

if (!function_exists('getPageDetails')) {
    function getPageDetails($name)
    {
        try {
            DB::connection()->getPdo();
            $lang = session('lang');
            return Cache::remember("page_details_{$name}_{$lang}", now()->addMinutes(30),
                function () use ($name,$lang) {
                    return Page::select('id', 'name', 'slug', 'custom_link')
                        ->where('name', $name)
                        ->addSelect([
                            'page_name' => PageDetail::with('language')
                                ->select('name')
                                ->whereHas('language', function ($query) use ($lang) {
                                    $query->where('short_name', $lang);
                                })
                                ->whereColumn('page_id', 'pages.id')
                                ->limit(1)
                        ])
                        ->first();
                });
        } catch (\Exception $e) {
            \Log::error("Error fetching page details: " . $e->getMessage());
            return null;
        }
    }
}


if (!function_exists('renderHeaderMenu')) {
    function renderHeaderMenu($menuItems)
    {
        if ($menuItems){
            foreach ($menuItems as $menuItem) {

                if (isset($menuItem['child'])) {
                    echo '<li class="nav-item">';
                    echo '<a href="javascript:void(0)" class="nav-link"><span>' . $menuItem['name'] .
                        '</span> <i class="fal fa-angle-down icon"></i></a>';

                    echo "<ul class='dropdown-list'>";
                    renderHeaderMenu($menuItem['child']);
                    echo "</ul>";

                } else {
                    echo '<li class="nav-item">';
                    echo '<a class="nav-link ' . (ucfirst(getLastSegment()) == $menuItem['name'] ? 'active' : '') . '" href="' . $menuItem['route'] . '">' . $menuItem['name'] . '</a>';
                }
                echo '</li>';
            }
        }
        return '';
    }
}

function getLastSegment()
{
    $currentUrl = url()->current();
    $lastSegment = last(explode('/', $currentUrl));
    return $lastSegment;
}



if (!function_exists('getFooterMenuData')) {
    function getFooterMenuData($type)
    {
        $selectedTheme = \basicControl()->theme;
        $menu = Cache::remember("footer_manage_menu_{$selectedTheme}", now()->addMinutes(30), function () use ($selectedTheme) {
            return ManageMenu::where('menu_section', 'footer')->where('template_name', $selectedTheme)->first();
        });
        $menuData = [];

        if (isset($menu->menu_items[$type])) {
            foreach ($menu->menu_items[$type] as $key => $menuItem) {
                $pageDetails = getPageDetails($menuItem);
                $menuIDetails = [
                    'name' => $pageDetails->page_name ?? $pageDetails->name ?? $menuItem,
                    'route' => isset($pageDetails->slug) ? route('page', $pageDetails->slug) : ($pageDetails->custom_link ?? staticPagesAndRoutes($menuItem)),
                ];
                $menuData[] = $menuIDetails;
            }
            foreach ($menuData as $item) {
                $che = '<li><a class="text-capitalize widget-link" href="' . $item['route'] . '">' . $item['name'] . '</a></li>';
                $flattenedMenuData[] = $che;
            }
            return $flattenedMenuData;
        }
    }
}

function getPageName($name)
{
    try {
        DB::connection()->getPdo();
        $defaultLanguage = Cache::remember('default_language',now()->addMinutes(30), function () {
            return Language::query()->where('default_status', true)->first();
        });

        $pageDetails = Cache::remember("page_details_{$defaultLanguage->id}_{$name}", now()->addMinutes(30),
            function () use ($defaultLanguage, $name) {
                return PageDetail::select('id', 'page_id', 'name')
                    ->with('page:id,name,slug')
                    ->where('language_id', $defaultLanguage->id)
                    ->whereHas('page', function ($query) use ($name) {
                        $query->where('name', $name);
                    })
                    ->first();
            });
        return $pageDetails->name ?? $pageDetails->page->name ?? $name;
    } catch (\Exception $e) {

    }
}


if (!function_exists('stringToTitle2')) {
    function stringToTitle2($string)
    {
        return implode(' ', array_map('ucwords', explode(' ', preg_replace('/[^a-zA-Z0-9]+/', ' ', $string))));
    }
}


function filterCustomLinkRecursive($collection, $lookingKey = '')
{

    $filterCustomLinkRecursive = function ($array) use (&$filterCustomLinkRecursive, $lookingKey) {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $array[$key] = $filterCustomLinkRecursive($value);
            } elseif ($value === $lookingKey || $key === $lookingKey) {
                unset($array[$key]);
            }
        }
        return $array;
    };
    $filteredCollection = $filterCustomLinkRecursive($collection);

    return $filteredCollection;
}

if (!function_exists('maskString')) {
    function maskString($input)
    {
        $length = strlen($input);
        $visibleCharacters = 2;
        $maskedString = '<span class="masked ms-2">' . substr($input, 0, $visibleCharacters) . '<span class="highlight">' . str_repeat('*', $length - 2 * $visibleCharacters) . '</span>' . substr($input, -$visibleCharacters) . '</span>';
        return $maskedString;
    }
}

if (!function_exists('maskEmail')) {
    function maskEmail($email)
    {
        list($username, $domain) = explode('@', $email);
        $usernameLength = strlen($username);
        $visibleCharacters = 2;
        $maskedUsername = substr($username, 0, $visibleCharacters) . str_repeat('*', $usernameLength - 2 * $visibleCharacters) . substr($username, -$visibleCharacters);
        $maskedEmail = $maskedUsername . '@' . $domain;
        return $maskedEmail;
    }
}

if (!function_exists('removeValue')) {
    function removeValue(&$array, $value)
    {
        foreach ($array as $key => &$subArray) {
            if (is_array($subArray)) {
                removeValue($subArray, $value);
            } else {
                if ($subArray === $value) {
                    unset($array[$key]);
                }
            }
        }
    }
}


if (!function_exists('strRandomNum')) {
    function strRandomNum($length = 15)
    {
        $characters = '1234567890';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}

function adminAccessRouteOld($search)
{

    $list = collect(config('role'))->pluck('access')->flatten()->intersect(auth()->guard('admin')->user()->admin_access);


    if (is_array($search)) {
        $list2 = $list->intersect($search);
        if (0 < count($list2)) {
            return true;
        }
        return false;
    } else {

        return $list->search(function ($item) use ($search) {
            if ($search == $item) {
                return true;
            }
            return false;
        });
    }
}

function adminAccessRoute($search)
{
    $user = auth()->guard('admin')->user();

    if (isset($user) && $user->role_id == null) {
        return true;
    }

    if (isset($user)) {
        foreach (optional($user->role)->permission as $permission) {
            if (in_array($permission, $search)) {
                return true;
            }
        }
    }

    return false;
}

function in_array_any($needles, $haystack)
{
    return (bool)array_intersect($needles, $haystack);
}


function getLevelUser($id)
{
    $ussss = new \App\Models\User();
    return $ussss->referralUsers([$id]);
}

if (!function_exists('paginate')) {
    function paginate($items, $perPage = 15, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
}


function getDirectReferralUsers($userId)
{
    $directReferralUsers = User::where('referral_id', $userId)->get(['id', 'firstname', 'lastname', 'username', 'email', 'phone_code', 'phone', 'referral_id', 'total_invest', 'created_at']);
    return $directReferralUsers;
}

if (!function_exists('getFirebaseFileName')) {
    function getFirebaseFileName()
    {
        return 'firebase-service.json';
    }
}


if (!function_exists('updateWallet')) {
    function updateWallet($user_id, $amount, $action = 0)
    {
        $user = \App\Models\User::find($user_id);
        $balance = 0;

        if ($action == 1) { //add money
            $balance = $user->balance + $amount;
            $user->balance = $balance;
        } elseif ($action == 0) { //deduct money
            $balance = $user->balance - $amount;
            $user->balance = $balance;
        }

        $user->save();
        return $balance;
    }
}


if (!function_exists('nullAmount')) {
    function nullAmount($amount): string
    {
        return $amount != null ? getAmount($amount) : '';
    }
}


if (!function_exists('checkQuery')) {

    function checkQuery($startTime, $startMemory, $taskName = 'Task', array $metadata = []): string
    {
        $endTime = microtime(true);
        $endMemory = memory_get_usage(true);

        $executionTime = $endTime - $startTime;
        $memoryUsedMB = ($endMemory - $startMemory) / 1024 / 1024;

        $logMessage = "[{$taskName}] Execution completed in {$executionTime} seconds. Memory used: {$memoryUsedMB} MB.";

        // if extra
        if (!empty($metadata)) {
            $logMessage .= " Metadata: " . json_encode($metadata);
        }
        info($logMessage);

        /*for web check*/
        $webMessage = "<div style='font-family: Arial, sans-serif;'>";
        $webMessage .= "<strong style='color: #4CAF50;'>[{$taskName}]</strong> ";
        $webMessage .= "<span>Execution completed in <strong>{$executionTime}</strong> seconds.</span><br>";
        $webMessage .= "<span>Memory used: <strong>{$memoryUsedMB} MB</strong>.</span><br>";
        if (!empty($metadata)) {
            $webMessage .= "<span style='color: #007BFF;'>Metadata:</span> " . json_encode($metadata) . "<br>";
        }
        $webMessage .= "</div>";
        return $webMessage;
    }
}
