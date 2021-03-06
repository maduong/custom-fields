<?php namespace Edutalk\Base\CustomFields\Http\Middleware;

use \Closure;
use Edutalk\Base\CustomFields\Facades\CustomFieldSupportFacade;

class BootstrapModuleMiddleware
{
    public function __construct()
    {

    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /**
         * Register to dashboard menu
         */
        dashboard_menu()->registerItem([
            'id' => 'edutalk-custom-fields',
            'priority' => 999.3,
            'parent_id' => null,
            'heading' => null,
            'title' => trans('edutalk-custom-fields::base.admin_menu.title'),
            'font_icon' => 'icon-briefcase',
            'link' => route('admin::custom-fields.index.get'),
            'css_class' => null,
            'permissions' => ['view-custom-fields'],
        ]);

        $this->registerUsersFields();
        $this->registerPagesFields();

        return $next($request);
    }

    protected function registerUsersFields()
    {
        CustomFieldSupportFacade::registerRule('other', trans('edutalk-custom-fields::rules.logged_in_user'), 'logged_in_user', function () {
            $userRepository = app(\Edutalk\Base\Users\Repositories\Contracts\UserRepositoryContract::class);

            $users = $userRepository->get();

            $userArr = [];
            foreach ($users as $user) {
                $userArr[$user->id] = $user->username . ' - ' . $user->email;
            }

            return $userArr;
        })
            ->registerRule('other', trans('edutalk-custom-fields::rules.logged_in_user_has_role'), 'logged_in_user_has_role', function () {
                $repository = app(\Edutalk\Base\ACL\Repositories\Contracts\RoleRepositoryContract::class);

                $roles = $repository->get();

                $rolesArr = [];
                foreach ($roles as $role) {
                    $rolesArr[$role->id] = $role->name . ' - (' . $role->slug . ')';
                }

                return $rolesArr;
            });
    }

    protected function registerPagesFields()
    {
        CustomFieldSupportFacade::registerRule('basic', trans('edutalk-custom-fields::rules.page_template'), 'page_template', function () {
            return get_templates('page');
        })
            ->registerRule('basic', trans('edutalk-custom-fields::rules.page'), 'page', function () {
                $pages = get_pages([
                    'select' => [
                        'id', 'title'
                    ],
                    /*Ignore the filters*/
                    'condition' => [],
                    'order_by' => [
                        'order' => 'ASC',
                        'created_at' => 'DESC',
                    ],
                ])
                    ->pluck('title', 'id')
                    ->toArray();

                return $pages;
            })
            ->registerRule('other', trans('edutalk-custom-fields::rules.model_name'), 'model_name', function () {
                return [
                    EDUTALK_PAGES => trans('edutalk-custom-fields::rules.model_name_page'),
                ];
            });
    }
}
