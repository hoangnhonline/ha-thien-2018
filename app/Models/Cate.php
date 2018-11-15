<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Cate extends Model  {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'cate';	

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
    protected $fillable = ['name', 'slug', 'alias', 'parent_id', 'is_hot', 'status', 'display_order', 'description', 'meta_id', 'created_user', 'updated_user', 'image_url'];

    public static function getList($params = []){
        $query = self::where('status', 1);
        
        if( isset($params['is_hot']) && $params['is_hot'] ){
            $query->where('is_hot', $params['is_hot']);
        }
        if( isset($params['parent_id']) && $params['parent_id'] ){
            $query->where('parent_id', $params['parent_id']);
        }
        $query->orderBy('is_hot', 'desc')->orderBy('id', 'desc');
        if(isset($params['limit']) && $params['limit']){
            return $query->limit($params['limit'])->get();
        }
        if(isset($params['pagination']) && $params['pagination']){
            return $query->paginate($params['pagination']);
        }                
    }
    public function product()
    {
        return $this->hasMany('App\Models\Product', 'cate_id');
    }
    public function cateParent()
    {
        return $this->belongsTo('App\Models\CateParent', 'parent_id');
    }
   
    public function productNew() {
        return $this->product()->where('is_old','=', 0);
    }
    public function banners()
    {
        return $this->hasMany('App\Models\Banner', 'object_id')->where('object_type', 2);
    }
}
