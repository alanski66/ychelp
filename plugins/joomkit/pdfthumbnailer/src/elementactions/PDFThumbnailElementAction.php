<?php
/**
 * Imager plugin for Craft CMS 3.x
 *
 * Image transforms gone wild
 *
 * @link      https://www.vaersaagod.no
 * @copyright Copyright (c) 2018 André Elvan
 */

namespace aelvan\imager\elementactions;

use Craft;

use craft\elements\db\ElementQueryInterface;
use craft\base\ElementAction;
use yii\base\Exception;

use aelvan\imager\Imager as Plugin;

class PDFThumbnailElementAction extends ElementAction
{
    /**
     * @inheritdoc
     */
    public function getTriggerLabel(): string
    {
        return Craft::t('pdfthumbnailer', 'trigger label');
    }

    /**
     * Clears transforms for selected assets
     * 
     * @param ElementQueryInterface $query
     *
     * @return bool
     */
    public function performAction(ElementQueryInterface $query): bool
    {
        $this->setMessage(Craft::t('pdfthumbnailer', 'saving thumbs'));
        return true;
    }
}