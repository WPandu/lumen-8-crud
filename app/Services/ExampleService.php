<?php

namespace App\Services;

use App\Models\Example;
use Illuminate\Http\Request;

class ExampleService extends Service
{
    /**
     * Create a new service instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct(new Example);
    }

    /**
     * For Get ALL from DB
     *
     * @return \App\Services\Collection
     */
    public function listPaginateExample(Request $request)
    {
        return $this->listPaginate($request);
    }

    /**
     * For Insert to DB
     *
     * @return bool
     */
    public function insertExample(Request $request)
    {
        return $this->insert($request->all());
    }

    /**
     * For Update to DB
     *
     * @return bool
     */
    public function updateExample($id, Request $request)
    {
        return $this->update($id, $request->all());
    }

    /**
     * For Get Detail from DB
     *
     * @return Object
     */
    public function detailExample($id)
    {
        return $this->detail($id);
    }

    /**
     * For Get Detail from DB
     *
     * @return Object
     */
    public function deleteExample($id)
    {
        $result = $this->detail($id);
        $this->delete($id);

        return $result;
    }

    /**
     * For Filter Data
     *
     * @return Object
     */
    public function filter($model, $request)
    {
        if ($request->input('name')) {
            $model->where('name', 'LIKE', '%' . $request->input('name') . '%');
        }

        return $model;
    }

    /**
     * For Sorting Data
     *
     * @return Object
     */
    //phpcs:ignore
    public function sorting($model, $request)
    {
        return $model->latest();
    }

    /**
     * For Get Detail from DB
     *
     * @return Object
     */
    public function validationExample(Request $request)
    {
        return $this->validation($request->all(), $this->rules());
    }

    /**
     * For Get Rules
     *
     * @return Array
     */
    private function rules()
    {
        return [
            'name' => 'required',
            'description' => 'nullable',
        ];
    }
}
