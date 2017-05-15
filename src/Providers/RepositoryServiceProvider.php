<?php namespace Edutalk\Base\CustomFields\Providers;

use Illuminate\Support\ServiceProvider;
use Edutalk\Base\CustomFields\Repositories\Contracts\CustomFieldRepositoryContract;
use Edutalk\Base\CustomFields\Repositories\Contracts\FieldGroupRepositoryContract;
use Edutalk\Base\CustomFields\Repositories\Contracts\FieldItemRepositoryContract;
use Edutalk\Base\CustomFields\Repositories\CustomFieldRepository;
use Edutalk\Base\CustomFields\Repositories\CustomFieldRepositoryCacheDecorator;
use Edutalk\Base\CustomFields\Repositories\FieldGroupRepository;
use Edutalk\Base\CustomFields\Repositories\FieldItemRepository;
use Edutalk\Base\CustomFields\Models\CustomField;
use Edutalk\Base\CustomFields\Models\FieldGroup;
use Edutalk\Base\CustomFields\Models\FieldItem;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(FieldGroupRepositoryContract::class, function () {
            return new FieldGroupRepository(new FieldGroup());
        });
        $this->app->bind(FieldItemRepositoryContract::class, function () {
            return new FieldItemRepository(new FieldItem());
        });
        $this->app->bind(CustomFieldRepositoryContract::class, function () {
            $repository = new CustomFieldRepository(new CustomField());

            if (config('edutalk-caching.repository.enabled')) {
                return new CustomFieldRepositoryCacheDecorator($repository);
            }

            return $repository;
        });
    }
}
