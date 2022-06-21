<?php

namespace App\Models;

use Zizaco\Entrust\EntrustRole;
use App\Facades\Transaction;

class Role extends EntrustRole
{
    //
    protected $table = "roles";

    public static function assignUserRole($userId,$roleId)
    {
        Transaction::simpleSave("bms.public.user_roles",array('user_id','role_id'),array($userId,$roleId));
    }

    public static function clearRoles($userId)
    {
        Transaction::remove("bms.public.user_roles",[['user_id','=',$userId]]);
    }
}
