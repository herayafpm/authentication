<?php

namespace Raydragneel\Authentication\Entities;
use CodeIgniter\Entity\Entity;

class BaseEntity extends Entity
{
	protected $model = null;
	protected $exceptColumnDatatable = [];
	public function __construct(array $data = null)
	{
		parent::__construct($data);
		$this->datamap = array_merge(['id_encode' => null],$this->datamap);
		if(isset($this->modelName)){
			if(!empty($this->modelName)){
				$this->model = model($this->modelName);
			}
		}
	}

	public function getIdEncode()
	{
		return base64_encode($this->id);
	}

	public function save()
	{
        if($this->model->save($this)){
            return $this->model->getInsertID();
        }else{
            return false;
        }
	}
	public function update()
	{
		try {
			return $this->model->update($this->id,$this);
		} catch (\Exception $th) {
			//throw $th;
			if($th->getMessage() === 'There is no data to update.'){
                return true;
            }
			return false;
		}
	}
	public function delete($purge = false)
	{
		try {
			return $this->model->delete($this->id,$purge);
		} catch (\Exception $th) {
			//throw $th;
			return false;
		}
	}
	public function restore()
	{
		try {
			return $this->model->restore($this->id);
		} catch (\Exception $th) {
			//throw $th;
			return false;
		}
	}

	public function toArrayDatatable(bool $onlyChanged = false, bool $cast = true, bool $recursive = false): array
    {
        $this->_cast = $cast;

        $keys = array_filter(array_keys($this->attributes), 'array_filter_datatable');
		

        if (is_array($this->datamap)) {
			$arrDiff1 = array_diff($keys, $this->datamap);
			$arrKeys1 = array_keys($this->datamap);
            $keys = array_unique(array_merge($arrDiff1,$arrKeys1));
        }
		foreach ($this->exceptColumnDatatable as $key) {
			$index = array_search($key,$keys);
			if($index !== FALSE){
				unset($keys[$index]);
			}
		}
        $return = [];

        // Loop over the properties, to allow magic methods to do their thing.
        foreach ($keys as $key) {
            if ($onlyChanged && ! $this->hasChanged($key)) {
                continue;
            }

            $return[$key] = $this->__get($key);

            if ($recursive) {
                if ($return[$key] instanceof self) {
                    $return[$key] = $return[$key]->toArrayDatatable($onlyChanged, $cast, $recursive);
                } elseif (is_callable([$return[$key], 'toArrayDatatable'])) {
                    $return[$key] = $return[$key]->toArrayDatatable();
                }
            }
        }

        $this->_cast = true;

        return $return;
    }

}
