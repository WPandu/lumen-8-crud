<?php

namespace App\Http\Controllers;

use App\Services\ExampleService;
use Illuminate\Http\Request;

class ExampleController extends Controller
{
    private $exampleService;

    /**
     * Controller constructor.
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->exampleService = new ExampleService;
    }

    /**
     * Get all records
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $result = $this->exampleService->listPaginateExample($this->request);

        return $this->respond($result);
    }

    /**
     * Store the record
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store()
    {
        $this->exampleService->validationExample($this->request)->validate();

        $result = $this->exampleService->insertExample($this->request);

        return $this->respondCreated($result);
    }

    /**
     * Update the record
     *
     * @param \Illuminate\Http\Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id)
    {
        $this->exampleService->validationExample($this->request)->validate();

        $result = $this->exampleService->updateExample($id, $this->request);

        return $this->respondUpdated($result);
    }

     /**
     * Show the record
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $result = $this->exampleService->detailExample($id);

        return $this->respond($result);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $result = $this->exampleService->deleteExample($id);

        return $this->respondDeleted($result);
    }
}
