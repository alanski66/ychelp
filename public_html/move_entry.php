<?php
/**
 * Craft web bootstrap file
 */

// Set path constants
define('CRAFT_BASE_PATH', dirname(__DIR__));
define('CRAFT_VENDOR_PATH', CRAFT_BASE_PATH.'/vendor');

// Load Composer's autoloader
require_once CRAFT_VENDOR_PATH.'/autoload.php';

// Load dotenv?
if (class_exists('Dotenv\Dotenv') && file_exists(CRAFT_BASE_PATH.'/.env')) {
    Dotenv\Dotenv::create(CRAFT_BASE_PATH)->load();
}

// Load and run Craft
define('CRAFT_ENVIRONMENT', getenv('ENVIRONMENT') ?: 'production');
/** @var craft\web\Application $app */
$app = require CRAFT_VENDOR_PATH.'/craftcms/cms/bootstrap/web.php';

//NB from stac=kexchange
//https://craftcms.stackexchange.com/questions/4433/move-entry-from-one-structure-to-another-with-parenting/4467#4467

// Start a transaction in case anything goes south
$transaction = $app->db->beginTransaction();
try {
    // Fetch the entry you need to move
    $entry = \craft\elements\Entry::findOne(108);

    // Remove it from its old structure
    $oldSection = $entry->getSection();

    $record = \craft\records\StructureElement::findOne([
        'structureId' => $oldSection->structureId,
        'elementId' => $entry->id
    ]);

    if (!$record->deleteWithChildren()) {
        throw new \Exception('Could not delete the old structure node');
    }

    // Set the new sectionId and typeId on the entry
    $entry->sectionId = 19;
    $entry->typeId = 26;

    // Append it to the end of the new structure
    $newSection = $entry->getSection();

    if (!$app->structures->appendToRoot($newSection->structureId, $entry, 'insert')) {
        throw new \Exception('Could not insert the new structure node');
    }

    // Save the entry
    if (!$app->elements->saveElement($entry)) {
        throw new \Exception('Could not save the entry');
    }

    // Commit the transaction
    $transaction->commit();
} catch (\Exception $e) {
    // Undo whatever changes we already made
    $transaction->rollBack();
    throw $e;
}

echo 'success!';
