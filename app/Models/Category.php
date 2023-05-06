<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['title','slug','parent_id','icon_id'];

    public function icon()
    {
       return $this->belongsTo(Icon::class);
    }


//     public function ancestors()
//     {
//         $ancestors = collect([]);
//         $category = $this;

//         while ($category->parent) {
//             $ancestors->push($category->parent);
//             $category = $category->parent;
//         }

//         return $ancestors->reverse();
//     }

//     public function canBeChildOf(Category $parent)
// {
//     // Get the IDs of all ancestors of the parent category
//     $ancestorIds = $parent->ancestors()->pluck('id')->toArray();

//     // Check whether the current category's ID is present in the list of ancestor IDs
//     // If it is, then the current category is an ancestor of the parent category and
//     // cannot be its child
//     return !in_array($this->id, $ancestorIds);
// }
}
