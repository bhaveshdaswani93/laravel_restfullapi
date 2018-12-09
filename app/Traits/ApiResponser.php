<?php
namespace App\Traits;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

trait ApiResponser
{
	private function successResponse($data,$code)
	{
		return response()->json($data,$code);
	}

	protected function errorResponse($message,$code)
	{
		return response()->json(['error'=>$message,'code'=>$code],$code);
	}

	protected function showAll(Collection $collection,$code=200)
	{
		if($collection->isEmpty())
		{
			return $this->successResponse(['data'=>$collection],$code);
		}
		$transformer = $collection->first()->transformer;
		// dd($transformer);
		$collection = $this->transformData($collection,$transformer);
		return $this->successResponse($collection,$code);
	}

	protected function showOne(Model $model,$code=200)
	{
		$transformer = $model->transformer;
		$model = $this->transformData($model,$transformer);

		return $this->successResponse($model,$code);
	}

	protected function showMessage($message,$code=200)
	{
		return $this->successResponse(['data'=>$message],$code);
	}

	protected function transformData($data,$transformer)
	{
		$fractal = fractal($data,new $transformer);
		return $fractal->toArray();
	}
}