<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\UserProject;
use App\Models\Transaction;
use App\Models\InvitedUser;
use App\Models\RefTransactionList;
use App\Models\Survey;


// class User
class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'users';

    // Разрешённые для массового заполнения поля
    protected $fillable = [
        'first_name',
        'last_name',
        'role',
        'email',
        'password',
        'access_token',
    ];

    protected $hidden = [
        'password',
        'access_token'
    ];

}

