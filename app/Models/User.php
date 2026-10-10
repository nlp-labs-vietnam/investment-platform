<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\Notify;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, Notify;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'username',
        'email',
        'password',
        'referral_id',
        'language_id',
        'email',
        'country_code',
        'country',
        'phone_code',
        'phone',
        'balance',
        'image',
        'image_driver',
        'state',
        'city',
        'zip_code',
        'address_one',
        'address_two',
        'provider',
        'provider_id',
        'status',
        'identity_verify',
        'address_verify',
        'two_fa',
        'two_fa_verify',
        'two_fa_code',
        'email_verification',
        'sms_verification',
        'verify_code',
        'time_zone',
        'sent_at',
        'last_login',
        'last_seen',
        'password',
        'email_verified_at',
        'remember_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $appends = ['last-seen-activity', 'lastSeen', 'fullname'];


    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public $allusers = [];

    protected $dates = ['deleted_at'];

    protected static function boot()
    {
        parent::boot();
        static::saved(function () {
            Cache::forget('userRecord');
        });
    }

    public function getFullnameAttribute()
    {
        return $this->firstname . ' ' . $this->lastname;
    }

    public function referral()
    {
        return $this->belongsTo(User::class, 'referral_id');
    }

    public function referrals()
    {
        return $this->hasMany(User::class, 'referral_id');
    }

    public function deposit()
    {
        return $this->hasMany(Deposit::class)->latest()->where('status', 1);
    }

    public function funds()
    {
        return $this->hasMany(Fund::class)->latest()->where('status', '!=', 0);
    }

    public function referralBonusLog()
    {
        return $this->hasMany(ReferralBonus::class, 'to_user_id', 'id');
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class)->latest();
    }

    public function payout()
    {
        return $this->hasMany(Payout::class, 'user_id');
    }


    public function getLastSeenActivityAttribute()
    {
        if (Cache::has('user-is-online-' . $this->id) == true) {
            return true;
        } else {
            return false;
        }
    }

    public function inAppNotification()
    {
        return $this->morphOne(InAppNotification::class, 'inAppNotificationable', 'in_app_notificationable_type', 'in_app_notificationable_id');
    }

    public function fireBaseToken()
    {
        return $this->morphMany(FireBaseToken::class, 'tokenable');
    }

    public function sendPasswordResetNotification($token)
    {
        $this->mail($this, 'PASSWORD_RESET', $params = [
            'message' => '<a href="' . url('password/reset', $token) . '?email=' . $this->email . '" target="_blank">Click To Reset Password</a>'
        ]);
    }

    public function invests()
    {
        return $this->hasMany(Investment::class)->latest();
    }

    public function countTotalInvestment()
    {
        return Cache::remember("user_{$this->id}_total_investments", now()->addMinutes(10), function () {
            return count($this->invests); // Or $this->invests()->count();
        });
    }

    public function  get_social_links_user(){
        return $this->hasMany(UserSocial::class, 'user_id');
    }

    public function userRank(){
        return $this->belongsTo(Rank::class, 'last_level', 'id');
    }

    public function ranking($id, $type=null){
        if ($type != null){
            $allRanks = json_decode($this->all_ranks);
        }else{
            $allRanks = $this->all_ranks;
        }

        if ($allRanks != null){
            return in_array($id, $allRanks);
        }else{
            return 'false';
        }
    }

    public function getLastSeenAttribute()
    {
        if(\Illuminate\Support\Facades\Cache::has('user-is-online-' . $this->id)){
            return true;
        }else{
            return false;
        }
    }

    public function uplineRefer($id)
    {
        return User::where('id', $id)->first();
    }

    public function referralUsers($id, $currentLevel = 1)
    {
        $users = $this->getUsers($id);
        if ($users['status']) {
            $this->allusers[$currentLevel] = $users['user'];
            $currentLevel++;
            $this->referralUsers($users['ids'], $currentLevel);
        }
        return $this->allusers;
    }

    public function getUsers($id)
    {
        if (isset($id)) {
            $data['user'] = User::whereIn('referral_id', $id)->get(['id', 'firstname', 'lastname', 'username', 'email', 'phone_code', 'phone', 'referral_id', 'created_at']);
            if (count($data['user']) > 0) {
                $data['status'] = true;
                $data['ids'] = $data['user']->pluck('id');
                return $data;
            }
        }
        $data['status'] = false;
        return $data;
    }

    public function profilePicture()
    {
        $activeStatus = $this->LastSeenActivity === false ? 'warning' : 'success';
        $firstName = $this->firstname;
        $firstLetter = $this->firstLetter($firstName);
        if (!$this->image) {
            return $this->getInitialsAvatar($firstLetter, $activeStatus);
        } else {
            $url = getFile($this->image_driver, $this->image);
            return $this->getImageAvatar($url, $activeStatus);
        }
    }

    protected function firstLetter($firstName)
    {
        if (is_string($firstName)) {
            $firstName = mb_convert_encoding($firstName, 'UTF-8', 'auto');
        } else {
            $firstName = '';
        }
        $firstLetter = !empty($firstName) ? substr($firstName, 0, 1) : '';

        if (!mb_check_encoding($firstLetter, 'UTF-8')) {
            $firstLetter = '';
        }
        return $firstLetter;
    }

    private function getInitialsAvatar($initial, $activeStatus)
    {
        return <<<HTML
                <div class="avatar avatar-sm avatar-soft-primary avatar-circle">
                    <span class="avatar-initials">{$initial}</span>
                    <span class="avatar-status avatar-sm-status avatar-status-{$activeStatus}"></span>
                </div>
                HTML;
    }

    private function getImageAvatar($url, $activeStatus)
    {
        return <<<HTML
            <div class="avatar avatar-sm avatar-circle">
                <img class="avatar-img" src="{$url}" alt="Image Description">
                <span class="avatar-status avatar-sm-status avatar-status-{$activeStatus}"></span>
            </div>
            HTML;
    }

    public function getNameAttribute(): string
    {
        return $this->firstname.' '.$this->lastname;
    }


}

