<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    
       public function project(){

          return $this->belongsToMany('App\Project','user_project','project_id','user_id');
    }
    
      public function user(){

          return $this->belongsToMany('App\User','user_project','project_id','user_id');
    }
}
