<?php namespace Edutalk\Base\CustomFields\Repositories;

use Edutalk\Base\Models\Contracts\BaseModelContract;
use Edutalk\Base\Repositories\Eloquent\EloquentBaseRepository;
use Edutalk\Base\Caching\Services\Traits\Cacheable;
use Edutalk\Base\Caching\Services\Contracts\CacheableContract;

use Edutalk\Base\CustomFields\Repositories\Contracts\CustomFieldRepositoryContract;

class CustomFieldRepository extends EloquentBaseRepository implements CustomFieldRepositoryContract, CacheableContract
{
    use Cacheable;

    /**
     * @param array $data
     * @return int
     */
    public function createCustomField(array $data)
    {
        return $this->create($data);
    }

    /**
     * @param int|null|BaseModelContract $id
     * @param array $data
     * @return int
     */
    public function createOrUpdateCustomField($id, array $data)
    {
        return $this->createOrUpdate($id, $data);
    }

    /**
     * @param int|null|BaseModelContract $id
     * @param array $data
     * @return int
     */
    public function updateCustomField($id, array $data)
    {
        return $this->update($id, $data);
    }

    /**
     * @param int|BaseModelContract|array $id
     * @return bool
     */
    public function deleteCustomField($id)
    {
        return $this->delete($id);
    }
}
