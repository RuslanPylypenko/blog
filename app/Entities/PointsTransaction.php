<?php


namespace App\Entities;


use Illuminate\Database\Eloquent\Model;

class PointsTransaction extends Model
{
    protected $table = 'points_transactions';

    protected $fillable = ['amount', 'user_id', 'type_operation', 'message', 'points_after_transaction'];
}