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
        // PS17 Patch, not valid for 1.6
        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);

        parent::__construct();
    }
}
