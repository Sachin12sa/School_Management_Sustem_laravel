<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    static public function getEmailSingle($email){
        return User::where('email','=',$email)->first();
    }
     static public function getTokenSingle($remember_token){
        return User::where('remember_token','=',$remember_token)->first();
    }
     static public function getAdmin(){
        $return= User::select('users.*')
                            ->where('user_type','=',1)
                            ->where('is_delete','=',0);
                            if (request('name')) {
                                    $return->where('name', 'like', '%' . request('name') . '%');
                                }
                            if (request('email')) {
                                    $return->where('email', 'like', '%' . request('email') . '%');
                                }
                                if (request('date')) {
                                    $return->whereDate('created_at', 'like', '%' . request('date') . '%');
                                }

                           $return = $return->orderBy('id','desc')
                            ->paginate(10);
                        return $return;
    }
    static public function getSingle($id){
        return User::find($id);
    }

    }
