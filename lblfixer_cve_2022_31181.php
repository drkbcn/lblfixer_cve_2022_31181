<?php

/**
 * 2022 LabelGrup Networks SL
 *
 * NOTICE OF LICENSE
 *
 * READ ATTACHED LICENSE.TXT
 *
 *  @author    Manel Alonso <malonso@labelgrup.com>, <admin@ethicalhackers.es>
 *  @copyright 2022 LabelGrup Networks SL
 *  @license   LICENSE.TXT
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

define('_SMARTY_PATH_16_', '/classes/');
define('_SMARTY_PATH_17_', '/classes/Smarty/');
define('_SMARTY_FILE_', 'SmartyCacheResourceMysql.php');
define('_MATCH_TEXT_', '$this->phpEncryption = new PhpEncryption(');

class Lblfixer_cve_2022_31181 extends Module
{
    public function __construct()
    {
        $this->name = 'lblfixer_cve_2022_31181';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'LabelGrup Networks SL, Manel Alonso';
        $this->need_instance = 0;
        $this->displayName = $this->l('LabelGrup.com FIX CVE-2022-31181 (for PrestaShop 1.6.1.X / 1.7.X)');
        $this->description = $this->l('Fixes CVE-2022-31181 vulnerability.');
        $this->ps_versions_compliancy = array('min' => '1.6.1', 'max' => _PS_VERSION_);
        $this->confirmUninstall = $this->l('Your shop will be vulnerable to CVE-2022-31181.') .
            $this->l('Are you sure you want to uninstall this addon?');

        parent::__construct();
    }

    public function install()
    {
        $this->patchCVE();
        return parent::install();
    }

    public function uninstall()
    {
        $this->unpatchCVE();
        return parent::uninstall();
    }

    /**
     * Get the path to the file to patch
     * @return string
     */
    private function getFilePath() {
        $path = _PS_ROOT_DIR_ . _SMARTY_PATH_17_ . _SMARTY_FILE_;
        if (version_compare(_PS_VERSION_, '1.7', '<')) {
            $path = _PS_ROOT_DIR_ . _SMARTY_PATH_16_ . _SMARTY_FILE_;
        }
        return $path;
    }

    /**
     * Apply patch for CVE-2022-31181
     * @return bool
     */
    private function patchCVE()
    {
        if ($this->detectAlreadyPatched()) {
            return true;
        }       

        return $this->patchFile($this->getFilePath());
    }

    /**
     * Remove patch for CVE-2022-31181
     * @return bool
     */
    private function unpatchCVE()
    {
        if (!$this->detectAlreadyPatched()) {
            return true;
        }

        return $this->unpatchFile($this->getFilePath());
    }

    /**
     * Detect if the patch is already applied. Version 1.7.X
     * @return bool
     */
    private function detectAlreadyPatched()
    {
        $path = $this->getFilePath();

        if (file_exists($path)) {
            $content = Tools::file_get_contents($path);
            if (strpos($content, _MATCH_TEXT_) !== false) {
                return true;
            }
        }
        return false;
    }

    /**
     * Create a backup and replace the original file for the patched one
     * @param string $path Path to the file to patch
     * @return bool
     */
    private function patchFile($path)
    {
        if (!@copy($path, dirname(__FILE__) . '/backup/' . _SMARTY_FILE_)) {
            return false;
        }

        if (version_compare(_PS_VERSION_, '1.7', '<')) {
            $this->copyFolderAndFilesRecursively(
                dirname(__FILE__) . '/_override16/classes/', 
                _PS_ROOT_DIR_ . _SMARTY_PATH_16_
            );
        } else {
            if (!@copy(dirname(__FILE__) . '/_override17/classes/Smarty/' . _SMARTY_FILE_, $path)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Restore the original file
     * @param string $path Path to the file to patch
     * @return bool
     */
    private function unpatchFile($path)
    {
        if (!@copy(dirname(__FILE__) . '/backup/' . _SMARTY_FILE_, $path)) {
            return false;
        }

        if (version_compare(_PS_VERSION_, '1.7', '<')) {
            // Recursive delete for vendor folder on PS16
        }

        if (@unlink(dirname(__FILE__) . '/backup/' . _SMARTY_FILE_)) {
            return true;
        }

        return false;
    }

    /**
     * Copies a folder recursively
     * @param string $source Source folder
     * @param string $dest Destination folder
     * @return void
     */
    private function copyFolderAndFilesRecursively($source, $destination)
    {
        if (is_dir($source)) {
            @mkdir($destination);
            $directory = dir($source);
            while (false !== ($readdirectory = $directory->read())) {
                if ($readdirectory === '.' || $readdirectory === '..') {
                    continue;
                }
                $path = $source . '/' . $readdirectory;
                if (is_dir($path)) {
                    $this->copyFolderAndFilesRecursively($path, $destination . '/' . $readdirectory);
                    continue;
                }
                @copy($path, $destination . '/' . $readdirectory);
            }
            $directory->close();
        } else {
            @copy($source, $destination);
        }
    }
}

?>