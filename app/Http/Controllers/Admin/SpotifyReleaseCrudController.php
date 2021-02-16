<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\SpotifyReleaseRequest;
use App\Models\SpotifyRelease;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Http\Controllers\Operations;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanel;
use Exception;

/**
 * Class SpotifyReleaseCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class SpotifyReleaseCrudController extends CrudController
{
    use Operations\ListOperation;
    //use Operations\CreateOperation;
    use Operations\UpdateOperation;
    use Operations\DeleteOperation;
    use Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     * @throws Exception
     */
    public function setup()
    {
        $this->crud->setModel(SpotifyRelease::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/spotify/release');
        $this->crud->setEntityNameStrings('release', 'releases');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->crud->column('name');
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        $this->crud->setValidation(SpotifyReleaseRequest::class);
        $this->crud->field('name');
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
