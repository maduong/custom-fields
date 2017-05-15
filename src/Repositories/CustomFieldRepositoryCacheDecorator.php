<?php namespace Edutalk\Base\CustomFields\Repositories;

use Edutalk\Base\Repositories\Eloquent\EloquentBaseRepositoryCacheDecorator;

use Edutalk\Base\CustomFields\Repositories\Contracts\CustomFieldRepositoryContract;
use Edutalk\Base\Models\Contracts\BaseModelContract;

class CustomFieldRepositoryCacheDecorator extends EloquentBaseRepositoryCacheDecorator implements CustomFieldRepositoryContract
{
    /**
     * @param array $data
     * @return int
     */
    public function createCustomField(array $data)
    {
        return $this->afterUpdate(__FUNCTION__, func_get_args());
    }

    /**
     * @param int|null|BaseModelContract $id
     * @param array $data
     * @return int
     */
    public function createOrUpdateCustomField($id, array $data)
    {
        return $this->afterUpdate(__FUNCTION__, func_get_args());
    }

    /**
     * @param int|null|BaseModelContract $id
     * @param array $data
     * @return int
     */
    public function updateCustomField($id, array $data)
    {
        return $this->afterUpdate(__FUNCTION__, func_get_args());
    }

    /**
     * @param int|BaseModelContract|array $id
     * @return bool
     */
    public function deleteCustomField($id)
    {
        return $this->afterUpdate(__FUNCTION__, func_get_args());
    }
}
