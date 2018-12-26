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
		$collection = $this->filterData($collection,$transformer);
		$collection = $this->sortData($collection,$transformer);
		
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

	protected function filterData(Collection $collection,$transformer)
	{
		foreach (request()->query() as $key => $value) 
		{
			$key =  $transformer::getOrignalAttribute($key);
			if(isset($key) && isset($value))
			{
				$collection = $collection->where($key,$value);
			}
		}
		return $collection;
	}

	protected function sortData(Collection $collection,$transformer)
	{
		if(request()->has('sort_by'))
		{
			$attribute = $transformer::getOrignalAttribute(request()->sort_by) ;
			$collection = $collection->sortBy->{$attribute};
		}
		
		return $collection;
	}
}