<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class CateParent extends Model  {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'cate_parent';	

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
                            'name', 
                            'slug', 
                            'alias',           
                            'is_hot', 
                            'status',
                            'image_url',
                            'display_order', 
                            'description',                 
                            'meta_id',                            
                            'is_hover',
                            'created_user',
                            'updated_user',
                            'color_code',
                            'color_active',
                            'icon_url'
                        ];
    public static function getList($params = []){
        $query = self::where('status', 1);
        
        if( isset($params['is_hot']) && $params['is_hot'] ){
            $query->where('is_hot', $params['is_hot']);
        }
        $query->orderBy('is_hot', 'desc')->orderBy('display_order');
        if(isset($params['limit']) && $params['limit']){
            return $query->limit($params['limit'])->get();
        }
        if(isset($params['pagination']) && $params['pagination']){
            return $query->paginate($params['pagination']);
        }                
    }
    public function cates()
    {
        return $this->hasMany('App\Models\Cate', 'parent_id');
    }

    public function banners()
    {
        return $this->hasMany('App\Models\Banner', 'object_id')->where('object_type', 1);
    }

}
