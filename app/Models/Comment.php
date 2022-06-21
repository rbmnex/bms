<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Facades\Transaction;

class Comment extends Model
{
    //
    protected $table = 'comments';

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public static function insert(array $input)
    {
        $id = Transaction::saveToTable("bms.public.comments", $input);
        return $id;
    }

    public static function display($taskId) 
    {
        $results = DB::table('bms.public.comments')
            ->where('task_id','=',$taskId)->orderBy('created_at','desc')->get();
        
        return $results;
    }
}
