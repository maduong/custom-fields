<?php namespace Edutalk\Base\CustomFields\Hook;

use Edutalk\Base\CustomFields\Facades\CustomFieldSupportFacade;
use Edutalk\Base\Models\Contracts\BaseModelContract;
use Edutalk\Base\Models\EloquentBase;
use Edutalk\Base\Users\Models\User;

class RenderCustomFields
{
    public function __construct()
    {
        /**
         * @var User $loggedInUser
         */
        $loggedInUser = auth()->user();

        $roles = $loggedInUser->roles()->pluck('id')->toArray();

        add_custom_fields_rules_to_check([
            'logged_in_user' => $loggedInUser->id,
            'logged_in_user_has_role' => $roles
        ]);
    }

    /**
     * @param string $location
     * @param string $screenName
     * @param BaseModelContract|EloquentBase $object
     */
    public function handle($location, $screenName, $object = null)
    {
        /**
         * If the location is not in main or the current page is not editing page
         */
        if ($location != 'main' || substr($screenName, -6) == '.index') {
            return;
        }

        switch ($screenName) {
            case EDUTALK_PAGES:
                add_custom_fields_rules_to_check([
                    'page_template' => isset($object->page_template) ? $object->page_template : '',
                    'page' => isset($object->id) ? $object->id : '',
                    'model_name' => EDUTALK_PAGES,
                ]);
                break;
        }

        $this->render($screenName, isset($object->id) ? $object->id : null);
    }

    /**
     * @param $screenName
     * @param $id
     */
    protected function render($screenName, $id)
    {
        $customFieldBoxes = get_custom_field_boxes($screenName, $id);

        if (!$customFieldBoxes) {
            return;
        }

        CustomFieldSupportFacade::renderAssets();

        echo CustomFieldSupportFacade::renderCustomFieldBoxes($customFieldBoxes);
    }
}