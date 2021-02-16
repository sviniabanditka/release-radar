<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\UserRequest;
use App\Models\User;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Http\Controllers\Operations;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanel;
use Carbon\Carbon;
use Exception;

/**
 * Class UserCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class UserCrudController extends CrudController
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
        $this->crud->setModel(User::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/user');
        $this->crud->setEntityNameStrings('user', 'users');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->crud->addColumns([
            [
                'name'  => 'email',
                'label' => 'Email',
                'type'  => 'email',
            ],
            [
                'name'     => 'is_tg_active',
                'label'    => 'Telegram',
                'type'     => 'closure',
                'function' => function($entry) {
                    return !empty($entry->telegram_chat_id) ? '+' : '-';
                }
            ],
            [
                'name'     => 'is_spotify_active',
                'label'    => 'Spotify',
                'type'     => 'closure',
                'function' => function($entry) {
                    return !empty($entry->spotify_access_token) ? '+' : '-';
                }
            ],
            [
                'name'  => 'last_notified', // The db column name
                'label' => 'Last Notified', // Table column heading
                'type'  => 'datetime',
                'format' => 'DD.MM.YYYY HH:mm', // use something else than the base.default_datetime_format config value
            ],
            [
                'name'     => 'telegram_notifications_period',
                'label'    => 'Period',
                'type'     => 'closure',
                'function' => function($entry) {
                    if ($entry->telegram_notifications_period['type'] == 'day') {
                        $text = 'Daily, '.Carbon::today()->addHours($entry->telegram_notifications_period['time'])->format('H:i');
                    } else {
                        $text = 'Weekly, '.Carbon::now()->startOfWeek()->addDays($entry->telegram_notifications_period['day']-1)->englishDayOfWeek.', '.Carbon::today()->addHours($entry->telegram_notifications_period['time'])->format('H:i');
                    }
                    return $text;
                }
            ],
            [
                'name'     => 'telegram_notifications_types',
                'label'    => 'Types',
                'type'     => 'closure',
                'function' => function($entry) {
                    $text = '';
                    if ($entry->telegram_notifications_types['single'] == 1) {
                        $text .= 'S ';
                    }
                    if ($entry->telegram_notifications_types['album'] == 1) {
                        $text .= 'A ';
                    }
                    if ($entry->telegram_notifications_types['appears_on'] == 1) {
                        $text .= 'O ';
                    }
                    if ($entry->telegram_notifications_types['compilation'] == 1) {
                        $text .= 'C ';
                    }
                    return $text;
                }
            ],
        ]);
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        $this->crud->setValidation(UserRequest::class);
        $this->crud->field('email');
        $this->crud->addField(
            [
                'name'  => 'telegram_notifications_period',
                'type'  => 'json',
                'view_namespace' => 'json-field-for-backpack::fields',
                'modes' => ['form', 'tree', 'code'],
                'default' => [],
            ]);
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
