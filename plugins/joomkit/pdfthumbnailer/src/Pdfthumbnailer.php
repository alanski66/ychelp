<?php
/**
 * pdfthumbnailer plugin for Craft CMS 3.x
 *
 * Create thumbnail images from pdf docs
 *
 * @link      https://www.joomkit.com
 * @copyright Copyright (c) 2018 Alan
 */

namespace joomkit\pdfthumbnailer;

use joomkit\pdfthumbnailer\services\SaveEntry as SaveEntryService;
use joomkit\pdfthumbnailer\services\SaveAsset as SaveAssetService;
use joomkit\pdfthumbnailer\models\Settings;

use Craft;
use craft\base\Element;
use craft\base\Plugin;
use craft\elements\Entry;
use craft\events\ModelEvent;
use craft\events\ElementEvent;
use craft\services\Plugins;
use craft\events\PluginEvent;
use craft\services\Elements;


use yii\base\Event;

/**
 * Craft plugins are very much like little applications in and of themselves. We’ve made
 * it as simple as we can, but the training wheels are off. A little prior knowledge is
 * going to be required to write a plugin.
 *
 * For the purposes of the plugin docs, we’re going to assume that you know PHP and SQL,
 * as well as some semi-advanced concepts like object-oriented programming and PHP namespaces.
 *
 * https://craftcms.com/docs/plugins/introduction
 *
 * @author    Alan
 * @package   Pdfthumbnailer
 * @since     1.0.0
 *
 * @property  SaveEntryService $saveEntry
 * @property  SaveAssetService $saveAsset
 * @property  Settings $settings
 * @method    Settings getSettings()
 */
class Pdfthumbnailer extends Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * Static property that is an instance of this plugin class so that it can be accessed via
     * Pdfthumbnailer::$plugin
     *
     * @var Pdfthumbnailer
     */
    public static $plugin;

    public $schemaVersion = '1.0.0';

    // Public Methods
    // =========================================================================

    public function init()
    {
        parent::init();
        self::$plugin = $this;

        // Handler: Elements::EVENT_AFTER_SAVE_ELEMENT or EVENT_BEFORE_SAVE_ELEMENT
        Event::on(
            Elements::class,
            Elements::EVENT_BEFORE_SAVE_ELEMENT,
            function (ElementEvent $event) {
                Craft::debug(
                    'Elements::EVENT_BEFORE_SAVE_ELEMENT',
                    __METHOD__
                );
                /** @var Element $element */
                $entry = $event->element;

                //check for cp and call service save entry
                if (Craft::$app->request->isCpRequest)
                {
                    Pdfthumbnailer::$plugin->saveEntry->setThumb($entry);
                }
            }
        );
        //logging
    
        Craft::info(
            Craft::t(
                'pdfthumbnailer',
                '{name} plugin loaded',
                ['name' => $this->name]
            ),
            __METHOD__
        );
    }

    // Protected Methods
    // =========================================================================

    /**
     * Creates and returns the model used to store the plugin’s settings.
     *
     * @return \craft\base\Model|null
     */
    protected function createSettingsModel()
    {
        return new Settings();
    }

    /**
     * Returns the rendered settings HTML, which will be inserted into the content
     * block on the settings page.
     *
     * @return string The rendered settings HTML
     */
    protected function settingsHtml(): string
    {
        return Craft::$app->view->renderTemplate(
            'pdfthumbnailer/settings',
            [
                'settings' => $this->getSettings()
            ]
        );
    }
}
