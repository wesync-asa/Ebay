<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Foundation\Auth\Access\Authorizable;

class User extends AppModel implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
{
    use Notifiable, Authenticatable, Authorizable, CanResetPassword;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $_title = ['Admin', 'Admin'];


    protected $_forms = [
        'id' => [
            'label' => 'ID',
            'type' => 'hide', // for form
            'search' => true
        ],
        'name' => [
            'label' => '氏名',
            'type' => 'text', // for form
            'search' => true
        ],
        'email' => [
            'label' => 'メールアドレス',
            'type' => 'email',
            'search' => true
        ],
        'password' => [
            'label' => 'パスワード',
            'type' => 'password',
            'search' => false
        ],
        'password_confirmation' => [
            'label' => 'パスワード再確認',
            'type' => 'password',
            'search' => false
        ],
    ];

    protected $_tables = [
        'fields' => [
            'id' => [
                'label' => 'ID',
                'value' => 'id',
                'class' => 'col-sm-2',
            ],
            'name' => [
                'label' => '名前',
                'value' => 'name',
                'class' => 'col-sm-3',
            ],
            'email' => [
                'label' => 'Email',
                'value' => 'email',
                'class' => 'col-sm-3',
            ],
        ],
        'actions' => ['content' => [
            0 => ['action' => 'show', 'label' => '詳細'],
            1 => ['action' => 'edit', 'label' => '編集'],
            2 => ['action' => 'destroy', 'label' => '削除'],
        ]
        ]
    ];

    protected $_validates = [
        'name' => 'sometimes|required|max:50',
        'email' => 'sometimes|required|unique:admins,deleted_at,NULL',
        'password' => 'sometimes|required|confirmed',
    ];

}
