<?php
namespace App\Repositories\Interfaces;

interface CrudRepositoryInterface
{
    public function getAllQuery();
    public function search($query, $value);
    public function sort_by($query, $column = 'created_at', $order = 'asc');
    public function filter($query, $column, $value);
    public function status($query, $status);
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function CategoryProducts(string $id);
}
