<?php

namespace App\Services;

use App\Http\Resources\ResourceCollection;
use App\Jobs\RecordLog;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

abstract class Service
{
    protected const ROOT_PROPERTY = 'data';

    private const DURATION_CACHE = 60;

    private $model;

    abstract protected function filter($model, $request);

    abstract protected function sorting($model, $request);

    /**
     * Create a new service instance.
     *
     * @return void
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * For Get ALL from DB
     *
     * @return array
     */
    protected function listPaginate(Request $request)
    {
        //Using Cache
        if ($request->get('using_cache') && Cache::has($this->cacheKey($request))) {
            return Cache::get($this->cacheKey($request));
        }

        $perPage = $request->get('perpage') ?: $this->model->perPage;

        $paginator = $this->filter($this->model->query(), $request);

        if ($request->get('filter') === 'active') {
            $paginator = $paginator->active();
        }

        $paginator = $this->sorting($paginator, $request);

        $paginator = $paginator->paginate($perPage);
        $result = new ResourceCollection($paginator);

        //Using Cache
        if ($request->get('using_cache')) {
            Cache::put($this->cacheKey($request), $result, self::DURATION_CACHE);
        }

        return $result;
    }

    /**
     * For Insert to DB
     *
     * @return bool
     */
    protected function insert(array $data)
    {
        if (Schema::hasColumns($this->model->getTable(), ['created_by', 'updated_by'])) {
            $data['created_by'] = get_user_lite();
            $data['updated_by'] = get_user_lite();
        }

        //Insert Log
        $this->insertLog('create', $data);

        //Insert Data
        $obj = $this->model->create($data);

        return $this->detail($obj->id);
    }

    /**
     * For Update to DB
     *
     * @return bool
     */
    protected function update($id, array $data)
    {
        if (Schema::hasColumns($this->model->getTable(), ['updated_by'])) {
            $data['updated_by'] = get_user_lite();
        }

        //Insert Log
        $this->insertLog('update', $data);

        //Update Data
        $this->model->findOrFail($id)->update($data);

        return $this->detail($id);
    }

    /**
     * For Delete from DB
     *
     * @return bool
     */
    protected function delete($id)
    {
        $obj = $this->model->findOrFail($id);

        //Insert Log
        $this->insertLog('delete', $obj->toArray());

        return $obj->delete();
    }

    /**
     * For Get Detail from DB
     *
     * @param $identifier (can id or slug)
     * @return array
     */
    protected function detail($identifier)
    {
        //phpcs:ignore SlevomatCodingStandard.ControlStructures.RequireTernaryOperator.TernaryOperatorNotUsed
        if (is_numeric($identifier)) {
            $obj = $this->model->findOrFail($identifier);
        } else {
            $obj = $this->model->where('slug', $identifier)->firstOrFail();
        }

        //phpcs::ignore
        $transformer = Str::replaceFirst('App\Models', 'App\Http\Resources', get_class($obj));

        return [
            'data' => new $transformer($obj),
        ];
    }

    /**
     * For Validation param
     *
     * @param $rules
     * @return \Illuminate\Validation\Validator
     */
    protected function validation(array $request, array $rules, array $messages = [])
    {
        return Validator::make($request, $rules, $messages);
    }

    /**
     * For Upload File
     *
     * @param $rules
     * @return string | null
     */
    protected function uploadFile(Request $request, $postName, $folderName)
    {
        try {
            if ($request->hasFile($postName)) {
                $path = sprintf(
                    '%s/%s',
                    config('media.path'),
                    $folderName
                );

                $fileName = sprintf(
                    '%s%s.%s',
                    str_random(6),
                    time(),
                    $request->file($postName)->getClientOriginalExtension()
                );
                $request->file($postName)->move($path, $fileName);

                return $fileName;
            }

            return null;
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * For Delete File
     *
     * @param $rules
     * @return boolean
     */
    protected function deleteFile($folderName, $fileName)
    {
        try {
            $target = sprintf(
                '%s/%s/%s',
                config('media.path'),
                $folderName,
                $fileName
            );

            shell_exec('rm ' . $target);
        } catch (Exception $e) {
            return false;
        }

        return true;
    }

    protected function cacheKey(Request $request)
    {
        return sprintf(
            '%s_platform_%s_%s_%s',
            $this->model->getTable(),
            get_platform_id() ?: 0,
            implode('_', array_keys($request->all())),
            implode('_', $request->all())
        );
    }

    protected function insertLog($type, array $data)
    {
        if (env('RECORD_LOG', false)) {
            $param = [
                'type' => $type,
                'title' => $this->getTitle(),
                'table_name' => $this->getTableName(),
                'data' => $data,
            ];

            dispatch(new RecordLog($param));
        }
    }

    private function getTableName()
    {
        return env('DB_DATABASE') . '.' . $this->model->getTable();
    }

    private function getTitle()
    {
        $className = class_basename($this->model);
        $arr = preg_split('/(?=[A-Z])/', $className);
        $result = implode(' ', $arr);

        return trim($result);
    }
}
