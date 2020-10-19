<?php
/**
 * pdfthumbnailer plugin for Craft CMS 3.x
 *
 * Create thumbnail images from pdf docs
 *
 * @link      https://www.joomkit.com
 * @copyright Copyright (c) 2018 Alan
 */

namespace joomkit\pdfthumbnailer\services;

use joomkit\pdfthumbnailer\Pdfthumbnailer;

use Craft;
use craft\base\Component;

/**
 * SaveAsset Service
 *
 * All of your plugin’s business logic should go in services, including saving data,
 * retrieving data, etc. They provide APIs that your controllers, template variables,
 * and other plugins can interact with.
 *
 * https://craftcms.com/docs/plugins/services
 *
 * @author    Alan
 * @package   Pdfthumbnailer
 * @since     1.0.0
 */
class SaveAsset extends Component
{
    // Public Methods
    // =========================================================================

    /**
     * This function can literally be anything you want, and you can have as many service
     * functions as you want
     *
     * From any other plugin file, call it like this:
     *
     *     Pdfthumbnailer::$plugin->saveAsset->exampleService()
     *
     * @return mixed
     */
    public function exampleService()
    {
        $result = 'something';
        // Check our Plugin's settings for `someAttribute`
        if (Pdfthumbnailer::$plugin->getSettings()->someAttribute) {
        }

        return $result;
    }
}
