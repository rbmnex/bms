<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Facades\Transaction;

class Task extends Model
{
    //
    protected $table = "task";
    const REGISTER_PROCESS = "register";
    const INSPECT_PROCESS = "inspect";
    const APPROVED_STATUS = "APPROVED";
    const NEW_STATUS = "NEW";
    const PENDING_STATUS = "PENDING";
    const REVERIFY_STATUS = "REVERIFY";
    const ONHOLD_STATUS = "ONHOLD";

    public static function insert($bridgeId, $userId, $yearId, $identifier, $process)
    {
        $id = Transaction::save("public.task", array(
            "bridge_id",
            "process",
            "current_status",
            "user_id",
            "year_id",
            "identifier",
            "created_by",
            "created_at"
        ), array($bridgeId, $process, self::NEW_STATUS, $userId, $yearId, $identifier, Auth::user()->ic_no, Date::now()));

        Transaction::save("public.status_history", array(
            "status",
            "task_id",
            "user_id",
            "updated_at"
        ), array(self::NEW_STATUS, $id, Auth::user()->id, Date::now()));

        return $id;
    }

    public static function add($identifier,$userId)
    {
        $id = Transaction::save("public.task", array(
            "process",
            "current_status",
            "user_id",
            "created_by",
            "created_at"
        ), array(self::INSPECT_PROCESS, self::NEW_STATUS, $userId, Auth::user()->ic_no, Date::now()));

        Transaction::save("public.status_history", array(
            "status",
            "task_id",
            "user_id",
            "updated_at"
        ), array(self::NEW_STATUS, $id, Auth::user()->id, Date::now()));

        return $id;
    }

    public static function edit($id,$status,$userId)
    {
        $data = ['current_status' => $status,
        'user_id' => $userId,
        'updated_by' => Auth::user()->ic_no,
        'updated_at' => Date::now()];
        Transaction::update("bms.public.task",$id,$data);
        Transaction::save("bms.public.status_history", array(
            "status",
            "task_id",
            "user_id",
            "updated_at"
        ), array($status, $id, Auth::user()->id, Date::now()));
    }

    public static function previousOwner($taskId)
    {
        $history = DB::table("bms.public.status_history")
        ->where("task_id","=",$taskId)->orderBy("updated_at","desc")->first();
        return $history;
    }

    public static function removeTask($id) {
        Task::where('id',$id)->delete();
        Transaction::remove('public.status_history',[['task_id','=',$id]]);
    } 
}
