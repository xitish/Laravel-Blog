<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Post extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    
    protected $fillable = ['title', 'content'];
    
    public function path()
    {
        return url("/post/{$this->id}-" . Str::slug($this->title));   
    }

    public function likes()
    {
        return $this->hasMany('App\Like');
    }

    public function tags()
    {
        return $this->belongsToMany('App\tag')->withTimestamps();     //return $this->belongsToMany('App\tag','table_name'); if different table name is required
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

   /*  public function setTitleAttribute($value)
    {
        $this->attributes['title'] = strtoupper($value);        //Mutator: Used while inserting into the database
    } */

   /*  public function getTitleAttribute($value)
    {
        return strtoupper($value);                               //Accessor: Used while retriving/fetching from the database
    } */
}