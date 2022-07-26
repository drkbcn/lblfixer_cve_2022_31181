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

const SMARTY_PATH = '/classes/Smarty/';
const SMARTY_FILE = 'SmartyCacheResourceMysql.php';

class Lblfixer_cve_2022_31181 extends Module
{
    public function __construct()
    {
        $this->name = 'lblfixer_cve_2022_31181';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'LabelGrup Networks SL, Manel Alonso';
        $this->need_instance = 0;
        $this->displayName = $this->l('LabelGrup.com FIX CVE-2022-31181 (for PrestaShop 1.7.X)');
        $this->description = $this->l('Fixes CVE-2022-31181 vulnerability.');
        // PS17 Patch, not valid for 1.6 (WIP)
        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
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
     * Apply patch for CVE-2022-31181
     * @return bool
     */
    private function patchCVE()
    {
        $match_text = '';

        if (version_compare(_PS_VERSION_, '1.7', '>=')) {
            $match_text = '$this->phpEncryption->encrypt($content)';
        } else {
            $match_text = '';
        }

        if ($this->detectAlreadyPatched($match_text)) {
            return true;
        }

        return $this->patchFile(_PS_ROOT_DIR_ . SMARTY_PATH . SMARTY_FILE, $match_text);
    }

    /**
     * Remove patch for CVE-2022-31181
     * @return bool
     */
    private function unpatchCVE()
    {
        $match_text = '';

        if (version_compare(_PS_VERSION_, '1.7', '>=')) {
            $match_text = '$this->phpEncryption->encrypt($content)';
        } else {
            $match_text = '';
        }

        if (!$this->detectAlreadyPatched($match_text)) {
            return true;
        }

        return $this->unpatchFile(_PS_ROOT_DIR_ . SMARTY_PATH . SMARTY_FILE, $match_text);
    }

    /**
     * Detect if the patch is already applied. Version 1.7.X
     * @param string $match_text Text to find in the file.
     */
    private function detectAlreadyPatched($match_text)
    {
        if (file_exists(_PS_ROOT_DIR_ . SMARTY_PATH . SMARTY_FILE)) {
            $content = Tools::file_get_contents(_PS_ROOT_DIR_ . SMARTY_PATH . SMARTY_FILE);
            if (strpos($content, $match_text) !== false) {
                return true;
            }
        }
    }

    /**
     * Create a backup and replace the original file for the patched one
     * @param string $path Path to the file to patch
     * @param bool $is_ps17 If the patch is for PrestaShop 1.7.X
     * @return bool
     */
    private function patchFile($path, $is_ps17 = true)
    {
        $version = $is_ps17 ? '_override17' : '_override16';
        if (!copy($path, dirname(__FILE__) . '/backup/' . SMARTY_FILE)) {
            return false;
        }

        if (!copy(dirname(__FILE__) . '/' . $version . '/classes/Smarty/' . SMARTY_FILE, $path)) {
            return false;
        }
    }

    /**
     * Restore the original file
     * @param string $path Path to the file to patch
     * @param bool $is_ps17 If the patch is for PrestaShop 1.7.X
     * @return bool
     */
    private function unpatchFile($path, $is_ps17 = true)
    {
        $version = $is_ps17 ? '_override17' : '_override16';
        if (!copy(dirname(__FILE__) . '/backup/' . SMARTY_FILE, $path)) {
            return false;
        }

        if (unlink(dirname(__FILE__) . '/' . $version . '/classes/Smarty/' . SMARTY_FILE)) {
            return true;
        }

        return false;
    }
}
