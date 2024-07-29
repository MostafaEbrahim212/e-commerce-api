<?php
namespace App\Repositories;

use App\Repositories\Interfaces\CrudRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class CrudRepository implements CrudRepositoryInterface
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }
    public function getAllQuery()
    {
        return $this->model::query();
    }

    public function search($query, $value)
    {
        return $query->where('name', 'like', '%' . $value . '%');
    }

    public function sort_by($query, $column = 'created_at', $order = 'asc')
    {
        return $query->orderBy($column, $order);
    }

    public function filter($query, $column, $value)
    {
        return $query->where($column, $value);
    }

    public function status($query, $status)
    {
        return $query->where('status', $status);
    }
    public function find($id)
    {
        return $this->model::find($id);
    }

    public function create(array $data)
    {
        return $this->model::create($data);
    }

    public function update($id, array $data)
    {
        $record = $this->find($id);
        $record->update($data);
        return $record;
    }

    public function delete($id)
    {
        $record = $this->find($id);
        $record->delete();
        return $record;
    }
    public function CategoryProducts(string $id)
    {
        return $this->model::find($id)->products();
    }
}
