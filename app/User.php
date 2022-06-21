<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;
use App\Facades\Transaction;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends Authenticatable
{
    //    use Notifiable;
    use EntrustUserTrait;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "users";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'ic_no', 'gender', 'enabled', 'district_id', 'state_id', 'office_id', 'type'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['remember_token'];

    public function office()
    {
        return $this->belongsTo('App\Models\Office','office_id');
    }

    public function district()
    {
        return $this->belongsTo('App\Models\District','district_id');
    }

    public function state()
    {
        return $this->belongsTo('App\Models\State','state_id');
    }

    public static function fetch(array $condition = []) {
        $result = [];

        $query = DB::table('bms.public.users as users')
        ->select('users.id as id', 'users.ic_no as ic','users.name as name', 'users.email as email', 'users.telephone_no as tel_no', 
        'dept.name as department', 'position.name as position', 'office.name as office')
        ->leftjoin('bms.public.office as office', 'users.office_id', 'office.id')
        ->leftJoin('bms.public.department as dept', 'users.department_id', 'dept.id')
        ->leftJoin('bms.public.position as position', 'users.position_id', 'position.id');

        if(empty($condition))
        {
            $result = $query->get();
        } else {
            $result = $query->where($condition)->get();
        }

        return $result;
    }

    public static function search(array $condition = [],array $roles = [])
    {
        $result = [];

        // ->join('bms.public.state as state', 'office.state_id', 'state.id')

        $query = DB::table('bms.public.users as users')
        ->select('users.id as id', 'users.ic_no as ic','users.name as name', 'users.email as email', 'users.telephone_no as tel_no', 
        'dept.name as department', 'position.name as position', 'office.name as office')
        ->join('bms.public.user_roles as user_roles', 'users.id', 'user_roles.user_id')
        ->join('bms.public.roles as roles', 'user_roles.role_id', 'roles.id')
        ->leftjoin('bms.public.office as office', 'users.office_id', 'office.id')
        ->leftJoin('bms.public.department as dept', 'users.department_id', 'dept.id')
        ->leftJoin('bms.public.position as position', 'users.position_id', 'position.id');

        if(!empty($condition) && !empty($roles)) 
        {
            $result = $query->whereIn('roles.name',$roles)->where($condition)->get();
        } 
        elseif(!empty($condition) && empty($roles)) 
        {
            $result = $query->where($condition)->get();
        } 
        elseif(empty($condition) && !empty($roles)) 
        {
            $result =$query->whereIn('roles.name',$roles)->get();
        }
        else 
        {
            $result = $query->get();
        }

        return $result;
    }

    public static function insert(array $input)
    {
        $id = Transaction::saveToTable("bms.public.users", $input);
        return $id;
    }
}
