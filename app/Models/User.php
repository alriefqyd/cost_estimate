<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'user_name',
        'email',
        'password',
        'role',
        'profile_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function profiles(){
        return $this->hasOne(Profile::class);
    }
    public function projectEngineerMechanical(){
        return $this->hasOne(Project::class,'design_engineer_mechanical');
    }

    public function projectEngineerCivil(){
        return $this->hasOne(Project::class,'design_engineer_civil');
    }

    public function projectEngineerElectrical(){
        return $this->hasOne(Project::class,'design_engineer_electrical');
    }

    public function projectEngineerInstrument(){
        return $this->hasOne(Project::class,'design_engineer_instrument');
    }

    public function roles(){
        return $this->belongsToMany(Role::class,'user_role')->withPivot('created_by','updated_by');
    }

    public function scopeFilter($query, array $filters){
        $query->when($filters['q'] ?? false, fn($query,$q) =>
        $query->where('name','like','%'.$q.'%')
            ->orWhere('email','like','%'.$q.'%')
        );
    }

    public function getDecryptPass(){
        return Crypt::decryptString($this->password);
    }

    public function isReviewer(){
        return (in_array(auth()->user()->profiles?->position, PROFILE::REVIEWER));
    }
}
