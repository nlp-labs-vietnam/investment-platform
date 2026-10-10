<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class ManageMenu extends Model
{
    use HasFactory;

    protected $fillable = ['menu_section', 'menu_items', 'template_name'];

    protected $casts = ['menu_items' => 'array'];

    protected static function boot()
    {
        parent::boot();
        static::saved(function () {
            $theme = \basicControl()->theme;
            $h_menu = "header_manage_menu_$theme";
            $f_menu = "footer_manage_menu_$theme";
            Cache::forget('header_footer_menus');
            Cache::forget($f_menu);
            Cache::forget($h_menu);
        });
    }

}
