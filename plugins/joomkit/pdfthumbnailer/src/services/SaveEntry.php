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

use craft\elements\Asset;
use joomkit\pdfthumbnailer\Pssdfthumbnailer;
use joomkit\pdfthumbnailer\models\Settings;


use Craft;
use craft\base\Component;
use craft\base\Element;
use craft\helpers\FileHelper;

use yii\base\ErrorException;

/**
 * SaveEntry Service
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
class SaveEntry extends Component
{
    // Public Methods
    // =========================================================================

    /**
     * This function can literally be anything you want, and you can have as many service
     * functions as you want
     *
     * From any other plugin file, call it like this:
     *
     *     Pdfthumbnailer::$plugin->saveEntry->exampleService()
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

    public function setThumb($entry)
    {
//        var_dump($entry->pdfDocument);
//        $this->prettyPrint($entry); die();
        if($entry->pdfDocument ) {
            //check if PDF asset exists for entry
            $asset = $entry->pdfDocument->one();



            if (!is_null($asset)) {
                //craft()->userSession->setNotice('Making pdf thumbnail.....');
                $this->makeThumbMultiple($entry);
            }else{

            }


        }

    }


    public function makeThumbMultiple($entry)
    {
        //if thumb exists dont bother making another one

        //if thumb doesnt exist lets make one

        //str_replace('{basePath}', $basePATH,
        $assets = $entry->pdfDocument;
        $thumbs = $entry->pdfThumbnailImage;
        $assetIds = null;

        // allow manual thumbnail
        if(count($thumbs) > '0'){
            //return;
        }

        $pdfcount = count($assets);

        if (count($assets) == count($thumbs)){
            return;
        }else{
            //remove thumbs and
            // Craft::$app->getSession()->setNotice("deleting thumbs");
            foreach($thumbs->all() as $thumb){
                Craft::$app->elements->deleteElement($thumb);
            }
        }

        // recreate thumbs - yes its expensive but i can't match pdf assets with thumbs
        $i = 0;
        foreach($assets->all() as $asset){
            //$pdfAsset = $this->getPDFAsset($entry);
            $volumePath = $asset->getVolume()->settings['path'];
            $folderPath = $asset->getFolder()->path."/";
            $assetFilePath = Craft::getAlias($volumePath) . $folderPath . $asset->filename;

            //var_dump($assetFilePath); die();

            $im = new \Imagick($assetFilePath);
            $im->setImageFormat('jpg');
            $im->setResolution(150,150); // dubious medium choice
            $im->setCompressionQuality(100);
            $im->readimage($assetFilePath . '[0]'); // reads first page '[0]' of pdf and takes this as thumbnail



            // print_r($im); die();

//            $im->profileImage('icc', file_get_contents(CRAFT_VENDOR_PATH .'/pixelandtonic/imagine/lib/Imagine/resources/Adobe/CMYK/USWebCoatedSWOP.icc')); //USWebUncoated.icc
//            $im->profileImage('icc', $sRGBrofile);

//            if ($im->getImageColorspace() == \Imagick::COLORSPACE_CMYK) {
//
//                $im->profileImage('icc', file_get_contents(CRAFT_VENDOR_PATH .'/pixelandtonic/imagine/lib/Imagine/resources/Adobe/CMYK/USWebCoatedSWOP.icc'));
//            }else {
//                $im->profileImage('icc', $sRGBrofile);
//            }
            if ($im->getImageColorspace() == \Imagick::COLORSPACE_CMYK) {
                $profiles = $im->getImageProfiles('*', false);
                // we're only interested if ICC profile(s) exist
                $has_icc_profile = (array_search('icc', $profiles) !== false);
                // if it doesnt have a CMYK ICC profile, we add one
                //moved to plugon from craft vendor as path kept changing
                if ($has_icc_profile === false) {
                    $icc_cmyk = file_get_contents(CRAFT_VENDOR_PATH .'/joomkit/pdfthumbnailer/resources/colorprofiles/USWebCoatedSWOP.icc'); //USWebUncoated.icc
                    $im->profileImage('icc', $icc_cmyk);
                    unset($icc_cmyk);
                }
                // then we add an RGB profile
                $icc_rgb = file_get_contents(CRAFT_VENDOR_PATH .'/joomkit/pdfthumbnailer/resources/colorprofiles/sRGB_v4_ICC_preference.icc');
                $im->profileImage('icc', $icc_rgb);
                unset($icc_rgb);
            }


            // Image has transparency
            if ($im->getImageAlphaChannel()) {
                // Remove alpha channel
                $im->setImageAlphaChannel(11);
                // Set image background color
                $im->setImageBackgroundColor('white');
                // Merge layers
                $im->mergeImageLayers($im->LAYERMETHOD_FLATTEN);

            }

            //$im->setImageAlphaChannel(11); // Imagick::ALPHACHANNEL_REMOVE
            //$im->mergeImageLayers($im->LAYERMETHOD_FLATTEN);
            //$im->transformImageColorspace($im->COLORSPACE_sRGB);
            //$im->scaleImage(595, 842, true, false);

            //$im->setImageExtent( 720, 1019);
            $im->borderImage("white", 0,0);
            $im->shaveImage(2,2);
            $im->trimImage(0);

            $im->resizeImage( 720, 0, $im->FILTER_LANCZOS, 1 );
            //store image
            //$tmpImagePath =  getenv('CRAFTENV_BASE_PATH').'/storage/runtime/';
            $tmpImagePath = sys_get_temp_dir() . '/';

            $assetfilename = str_replace(".pdf","", $asset->filename);
            $docFileName = $asset->filename;
            $docTitle = $asset->title;
            $outputFilename = strtolower($assetfilename);
            $im->writeImage($tmpImagePath . $outputFilename . '.jpg');

            // for multiple thumbs we only save first asset as thumb
            //if($i < 1){
            $assetId = $this->savePDFThumbImageAsAsset($tmpImagePath,$entry,$outputFilename,$docFileName,$docTitle);
            //}

            $result = $this->SaveAssetsToEntry($assetId, $entry, $pdfcount);
            if ($result === false) {
                throw new Exception('Error');
            }

            Craft::info(
                Craft::t(
                    'pdfthumbnailer',
                    'foome '.$assetId
                ),
                __METHOD__
            );


            $i++;
        }




        // send assets id array to save entry



    }

    public function makeThumb($entry)
    {
        //if thumb exists dont bother making another one

        //if thumb doesnt exist lets make one

        //str_replace('{basePath}', $basePATH,
        $asset = $entry->pdfDocument->one();

        $pdfAsset = $this->getPDFAsset($entry);

        $im = new \Imagick($pdfAsset);


        $im->setResolution(150,150); // dubious medium choice
        $im->readimage($pdfAsset . '[0]'); // reads first page '[0]' of pdf and takes this as thumbnail

        $cropWidth = $im->getImageWidth();
        $cropHeight = $im->getImageHeight();

        $im->setImageFormat('jpg');
        // Remove transparency, fill transparent areas with white rather than black.
        $im->setImageBackgroundColor("white");
        $im->borderImage("white", 0,0);
        // $im->setImageAlphaChannel($im->ALPHACHANNEL_REMOVE); // Imagick::ALPHACHANNEL_REMOVE
        $im->mergeImageLayers($im->LAYERMETHOD_FLATTEN);
        //$im->transformImageColorspace($im->COLORSPACE_sRGB);
        //$im->scaleImage(595, 842, true, false);
        $im->resizeImage( 595, 0, $im->FILTER_LANCZOS, 1 );

        $im->trimImage(0); //oh
        //store image

        //$tmpImagePath =  getenv('CRAFTENV_BASE_PATH').'/storage/runtime/';
        $tmpImagePath = sys_get_temp_dir() . '/';

        $im->writeImage($tmpImagePath . $entry->slug . '.jpg');

//        $tmpAsset =(object) array(
//            "title" => $entry->slug,
//            "tempFilePath" => $tmpImagePath,
//            "volumeId" => "9",
//            "filename" => $entry->slug . '.jpg',
//            "newFolderId" => "0",
//            "avoidFilenameConflicts" => true,
//        );

        $assetId = $this->savePDFThumbImageAsAsset($tmpImagePath,$entry);

        Craft::info(
            Craft::t(
                'pdfthumbnailer',
                'foo '.$assetId
            ),
            __METHOD__
        );

        $result = $this->SaveAssetToEntry($assetId, $entry);
        if ($result === false) {
            throw new Exception('Error');
        }
    }

    public function savePDFThumbImageAsAsset($tmpImagePath, $entry,$outputFilename,$docFileName,$docTitle)
    {
        $assets = Craft::$app->getAssets();
        $folderId = 9; //<- insert your folder id

        /** @var \craft\models\VolumeFolder $folder */
        $folder = $assets->findFolder(['id' => $folderId]);

        $asset = new Asset();
        $asset->tempFilePath = $tmpImagePath . $outputFilename . '.jpg';
        $asset->filename = $outputFilename . '.jpg';
        //$asset->setFieldValues(['documentLink' => $docFileName, 'title' =>$docTitle ]); //new filed added 'pdfTitleForThumbnail'
        //strip imagmagick pdf in filename??

        $asset->newFolderId = $folder->id;
        $asset->volumeId = $folder->volumeId;
        $asset->avoidFilenameConflicts = true;
        $asset->setScenario(Asset::SCENARIO_DEFAULT);

        $result = Craft::$app->getElements()->saveElement($asset);

//        // In case of error, let user know about it.
//        if ($result === false) {
//            throw new Exception('Error while upload asset');
//        }



        return $asset->id;
    }

    public function SaveAssetsToEntry($assetId, $entry, $count){

        // Set the custom field values (including Matrix)
        // $fieldValues = ['pdfThumbnailImage' => [$assetId]];

//        $element->setFieldValue('fieldHandle', $ids);

        $ids = $entry->getFieldValue('pdfThumbnailImage')->ids();

        //count current ids so as not to save multiple images for each thumb
        $idcount = count($ids);

        if($count > $idcount ){ //new pdf asset thumb to save
            $ids[] = $assetId;
            $entry->setFieldValue('pdfThumbnailImage', $ids);
        }




        //Craft::$app->getElements()->saveElement($entry);

        //$entry->('pdfThumbnailImage', $assetIds);

        //Craft::$app->elements->saveElement($entry);
        return $assetId;
    }

//    public function savePDFAssetThumb(){
//        $asset = new Asset();
//        $asset->title = $url;
//        $asset->tempFilePath = $tmpPath; // temp path for image in /storage/runtime/temp/
//        $asset->volumeId = $volumeId; // ID of my Asset Volume
//        $asset->filename = $filename; // filename like youtube_<youTubeKey>.jpg
//        $asset->newFolderId = $folder->id; // root folder id of the volume
//        $asset->avoidFilenameConflicts = true;
//        $asset->setScenario(Asset::SCENARIO_CREATE);
//        clearstatcache();
//        list ($w, $h) = Image::imageSize($tmpPath);
//        $asset->setWidth($w);
//        $asset->setHeight($h);
//        $result = Craft::$app->getElements()->saveElement($asset);
//    }

    public function savePDFThumbnailAsCMSAsset($pdfAsset, $entry){

        $filename = $entry->slug. '.jpg' ;
        $localFilePath = $pdfAsset->tempFileLocation . $filename;

        //asset folder $folder->id = '9';

        //get asset folder info - ideally from config
        // Find the target folder
        $folder = craft()->assets->findFolder(array(
            'sourceId' => $this->getSettings()->pdfAssetFolderId  //$this->settings->getAttribute('pdfAssetFolderId')
        ));
        $folder->id = $folder->getAttribute('id');

        //conflict res can be Replace or KeepBoth

        $response = craft()->assets->insertFileByLocalPath(
            $localFilePath,
            $filename,
            $folder->id,
            AssetConflictResolution::Replace
        );

        // Get id of pdf (newly created Asset)
        $pdfId = craft()->assets->findFile(array(
            'filename' => $filename
        ))->id;

        // Add PDF image to entry // field must be called pdfThumbnailImage
        $entry->setContentFromPost(array(
            'pdfThumbnailImage' => array($pdfId)
        ));
        //saves without triggering itself
        craft()->elements->saveElement($entry);
//        if (craft()->entries->saveEntry($entry)) {
//            PdfThumbnailerPlugin::log("saved " . $pdfId);
//        }
        // $this->entryPrettyPrint($response); die();
        return $response;
    }



    public function checkThumbExists($entry)
    {
        //$asset = $entry->pdfDocument->first();
        //var_dump($asset); die();
//        $source = $asset->getSource();
//        return IOHelper::fileExists($path);
    }

    public function prettyPrint($arg){
        echo '<pre>';
        print_r($arg);
        echo '</pre>';

    }

    public function fileExists($entry)
    {
        $asset = $entry->pdfDocument->first();
        //var_dump($asset); die();
//        $source = $asset->getSource();
//        return IOHelper::fileExists($path);
    }



    /*
     *
     */
    public function getPDFAsset($entry)
    {

        $asset = $entry->pdfDocument->one();
        //get basepath env variable from config and replace stirng with real path if setup uses basePath
        // $basePATH = craft()->config->get('environmentVariables')['basePath'];


        $volumePath = $asset->getVolume()->settings['path'];
        $folderPath = $asset->getFolder()->path."/";
        $assetFilePath = Craft::getAlias($volumePath) . $folderPath . $asset->filename;


        return $assetFilePath;
    }

    public function entryPrettyPrint($arg){
        echo '<pre>';
        echo print_r($arg) . '</pre>';
    }

}
