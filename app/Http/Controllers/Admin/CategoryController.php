<?php
/**
 * LaraClassified - Geo Classified Ads CMS
 * Copyright (c) Mayeul Akpovi. All Rights Reserved
 *
 * Email: mayeul.a@larapen.com
 * Website: http://larapen.com
 *
 * LICENSE
 * -------
 * This software is furnished under a license and may be used and copied
 * only in accordance with the terms of such license and with the inclusion
 * of the above copyright notice. If you Purchased from Codecanyon,
 * Please read the full License from here - http://codecanyon.net/licenses/standard
 */

namespace App\Http\Controllers\Admin;

use App\Larapen\Models\Category;
use Illuminate\Support\Facades\Request;
use Larapen\CRUD\app\Http\Controllers\CrudController;
// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\Admin\CategoryRequest as StoreRequest;
use App\Http\Requests\Admin\CategoryRequest as UpdateRequest;

class CategoryController extends CrudController
{
    public $crud = array(
        "model" => "App\Larapen\Models\Category",
        "entity_name" => "category",
        "entity_name_plural" => "categories",
        "route" => "admin/category",
        "reorder" => true,
        "reorder_label" => "name",
        "reorder_max_level" => 2,
        "details_row" => true,
        
        // *****
        // COLUMNS
        // *****
        "columns" => [
            [
                'name' => 'id',
                'label' => "ID"
            ],
            [
                'name' => 'name',
                'label' => "Category Name"
            ],
            [
                'name' => 'active',
                'label' => "Active",
                'type' => "model_function",
                'function_name' => 'getActiveHtml',
            ],
        ],
        
        
        // *****
        // FIELDS ALTERNATIVE
        // *****
        "fields" => [
            [
                'name' => 'name',
                'label' => 'Name',
                'type' => 'text',
                'placeholder' => 'Enter a name'
            ],
            [
                'name' => 'slug',
                'label' => 'Slug (URL)',
                'type' => 'text',
                'placeholder' => 'Will be automatically generated from your name, if left empty.',
                'hint' => 'Will be automatically generated from your name, if left empty.',
            ],
            [
                'name' => 'description',
                'label' => 'Description',
                'type' => 'textarea',
                'placeholder' => 'Enter a description'
            ],
            [
                'name' => 'picture',
                'label' => 'Picture',
                'type' => 'browse'
            ],
            /*[
                'name' 			=> 'css_class',
                'label' 		=> 'CSS Class',
                'type' 			=> 'text',
                'placeholder' 	=> 'CSS Class'
            ],*/
            [
                'name' => 'type',
                'label' => 'Type',
                'type' => 'enum',
            ],
            [
                'name' => 'active',
                'label' => "Active",
                'type' => 'checkbox'
            ],
        ],
    );
    
    public function __construct()
    {
        if (Request::segment(3) == 'create') {
            $parentField = [
                'name' => 'parent_id',
                'label' => 'Parent',
                'type' => 'select_from_array',
                'options' => $this->categories(),
                'allows_null' => false,
            ];
            array_unshift($this->crud['fields'], $parentField);
        }
        
        
        parent::__construct();
    }
    
    public function store(StoreRequest $request)
    {
        return parent::storeCrud();
    }
    
    public function update(UpdateRequest $request)
    {
        return parent::updateCrud();
    }
    
    public function categories()
    {
        $currentId = 0;
        if (Request::segment(4) == 'edit' and is_numeric(Request::segment(3))) {
            $currentId = Request::segment(3);
        }
        
        $entries = Category::where('translation_lang', config('app.locale'))->where('parent_id', 0)->orderBy('lft')->get();
        if (is_null($entries)) {
            return [];
        }
        
        $tab = [];
        $tab[0] = 'Root';
        foreach ($entries as $entry) {
            if ($entry->id != $currentId) {
                $tab[$entry->translation_of] = '- ' . $entry->name;
            }
        }
        
        return $tab;
    }
}
