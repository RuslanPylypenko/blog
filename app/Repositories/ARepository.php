<?php


namespace App\Repositories;


use Illuminate\Database\Eloquent\Model;

abstract class ARepository
{

    /**
     * @var Model
     */
    protected $model;

    /**
     * ARepository constructor.
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }


    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }


    /**
     * @param array $criteria
     * @return mixed
     */
    public function exists(array $criteria)
    {
        return $this->model->where($criteria)->exists();
    }

    /**
     * @param array $criteria
     * @return mixed
     */
    public function findOne(array $criteria)
    {
        return $this->model->where($criteria)->first();
    }


    /**
     * @param $id
     * @param array $params
     * @return mixed
     */
    public function update($id, array $params){
        return $this->model->where('id', $id)->update($params);
    }
}