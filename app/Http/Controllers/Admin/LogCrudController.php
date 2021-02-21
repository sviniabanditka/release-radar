<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LogViewer;
use Illuminate\Container\EntryNotFoundException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class LogCrudController extends Controller
{
    protected $data;

    /**
     * Lists all log files.
     *
     * @return Factory|\Illuminate\View\View
     */
    public function index()
    {
        $this->data['files'] = LogViewer::getFiles(true);
        $this->data['title'] = trans('backpack::logmanager.log_manager');
        return view('logmanager::logs', $this->data);
    }

    /**
     * Previews a log file.
     *
     * @param $file_name
     * @return Application|Factory|View
     * @throws EntryNotFoundException
     */
    public function preview($file_name)
    {
        LogViewer::setFile(decrypt($file_name));

        $logs = LogViewer::all();

        if (count($logs) <= 0) {
            abort(404, trans('backpack::logmanager.log_file_doesnt_exist'));
        }

        $this->data['logs'] = $logs;
        $this->data['title'] = trans('backpack::logmanager.preview').' '.trans('backpack::logmanager.logs');
        $this->data['file_name'] = decrypt($file_name);

        return view('logmanager::log_item', $this->data);
    }

    /**
     * Downloads a log file.
     *
     * @param $file_name
     *
     * @throws \Exception
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function download($file_name)
    {
        return response()->download(LogViewer::pathToLogFile(decrypt($file_name)));
    }

    /**
     * Deletes a log file.
     *
     * @param $file_name
     *
     * @throws \Exception
     *
     * @return string
     */
    public function delete($file_name)
    {
        if (app('files')->delete(LogViewer::pathToLogFile(decrypt($file_name)))) {
            return 'success';
        }

        abort(404, trans('backpack::logmanager.log_file_doesnt_exist'));
    }
}
