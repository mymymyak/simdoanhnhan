<?php

namespace App\Repositories;

/**
 * Interface CrudableInterface.
 *
 * @author Do Duy Duc <ducdd6647@co-well.com.vn>
 */
interface CrudableInterface
{
    /**
     * Get data by id.
     *
     * @param $id
     *
     * @return mixed
     */
    public function find($id);

    /**
     * Create new data.
     *
     * @param $attributes
     *
     * @return mixed
     */
    public function create($attributes);

    /**
     * Update data.
     *
     * @param $id
     * @param $attributes
     *
     * @return mixed
     */
    public function update($id, $attributes);

    /**
     * Delete data by id.
     *
     * @param $id
     *
     * @return mixed
     */
    public function delete($id);
}
