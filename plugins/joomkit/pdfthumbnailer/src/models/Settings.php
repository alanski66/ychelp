<?php
/**
 * pdfthumbnailer plugin for Craft CMS 3.x
 *
 * Create thumbnail images from pdf docs
 *
 * @link      https://www.joomkit.com
 * @copyright Copyright (c) 2018 Alan
 */

namespace joomkit\pdfthumbnailer\models;

use joomkit\pdfthumbnailer\Pdfthumbnailer;

use Craft;
use craft\base\Model;

/**
 * Pdfthumbnailer Settings Model
 *
 * This is a model used to define the plugin's settings.
 *
 * Models are containers for data. Just about every time information is passed
 * between services, controllers, and templates in Craft, it’s passed via a model.
 *
 * https://craftcms.com/docs/plugins/models
 *
 * @author    Alan
 * @package   Pdfthumbnailer
 * @since     1.0.0
 */
class Settings extends Model
{
    // Public Properties
    // =========================================================================

    /**
     * Some field model attribute
     *
     * @var string
     */
    public $pdfDocument = 'pdfDocument';
    public $pdfThumbnailImage = 'pdfThumbnailImage';
    public $pdfThumbnailImageVolume = 'pdfThumbnailImageVolume';

    // Public Methods
    // =========================================================================

    /**
     * Returns the validation rules for attributes.
     *
     * Validation rules are used by [[validate()]] to check if attribute values are valid.
     * Child classes may override this method to declare different validation rules.
     *
     * More info: http://www.yiiframework.com/doc-2.0/guide-input-validation.html
     *
     * @return array
     */
    public function rules()
    {
        return [
            ['pdfDocument', 'string'],
            ['pdfDocument', 'default', 'value' => 'pdfDocument'],
        ];
    }
}
