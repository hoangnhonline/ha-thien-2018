<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Orders extends Model  {

  /**
   * The database table used by the model.
   *
   * @var string
   */
  protected $table = 'orders';

   /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [            
      'total_bill',      
      'total_product',
      'status',
      'method_id',      
      'discount',
      'total_payment',
      'shipping_fee',      
      'is_other_address',
      'cod_fee',
      'fullname',      
      'is_pay',
      'updated_user',
      'other_fullname',
      'other_address',
      'other_phone',
      'other_email',
      'fullname',
      'address',
      'phone',
      'email',
      'payment_status',
      'customer_id',
      'cap_bac',
      'date_apply',
      'notes'
    ];

    public function order_detail()
    {
        return $this->hasMany('App\Models\OrderDetail', 'order_id');
    }
}
