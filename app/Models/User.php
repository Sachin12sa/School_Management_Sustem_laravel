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
    'last_name',
    'email',
    'password',
    'user_type',
    'admission_number',
    'roll_number',
    'class_id',
    'gender',
    'date_of_birth',
    'mobile_number',
    'admission_date',
    'profile_pic',
    'blood_group',
    'height',
    'weight',
    'is_delete',
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
    static public function getStudent(){

       $return = User::select('users.*', 'classes.name as class_name')
                            ->leftJoin('classes', 'classes.id', '=', 'users.class_id')
                            ->where('users.user_type','=',3)
                            ->where('users.is_delete','=',0);
                                if (request('name')) {
                                        $return->where('users.name', 'like', '%' . request('name') . '%');
                                    }

                                if (request('email')) {
                                        $return->where('users.email', 'like', '%' . request('email') . '%');
                                    }
                                if (request('admission_number')) {
                                    $return->where('users.admission_number', 'like', '%' . request('admission_number') . '%');
                                }
                             
                             
                           $return = $return->orderBy('id','desc')
                            ->paginate(10);
                        return $return;
    }
    
    static public function getSingle($id){
        return User::find($id);
    }
    public function getProfile()
{
    if (!empty($this->profile_pic) && file_exists(storage_path('app/public/' . $this->profile_pic))) {
        return asset('storage/' . $this->profile_pic);
    } else {
        return ""; // or return a default avatar
    }
}

}
