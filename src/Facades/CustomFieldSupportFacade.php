<?php namespace Edutalk\Base\CustomFields\Facades;

use Illuminate\Support\Facades\Facade;
use Edutalk\Base\CustomFields\Support\CustomFieldSupport;

/**
 * @method static CustomFieldSupport addRule($ruleName, $value = null)
 * @method static array exportCustomFieldsData(string $morphClass, string $morphId)
 * @method static string renderCustomFieldBoxes(array $boxes)
 * @method static string renderRules()
 * @method static void renderAssets()
 */
class CustomFieldSupportFacade extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return CustomFieldSupport::class;
    }
}
