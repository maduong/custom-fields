<?php namespace Edutalk\Base\CustomFields\Providers;

use Illuminate\Support\ServiceProvider;

class UninstallModuleServiceProvider extends ServiceProvider
{
    protected $moduleAlias = 'edutalk-custom-fields';

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        app()->booted(function () {
            $this->booted();
        });
    }

    protected function booted()
    {
        acl_permission()
        ->unsetPermissionByModule($this->moduleAlias);
    }
}
