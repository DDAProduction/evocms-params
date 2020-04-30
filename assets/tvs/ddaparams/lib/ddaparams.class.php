<?php namespace DdaParams;

use EvolutionCMS\Ddafilters\Models\FilterParamValues;

include_once(MODX_BASE_PATH . 'assets/snippets/DocLister/lib/DLTemplate.class.php');
include_once(MODX_BASE_PATH . 'assets/lib/APIHelpers.class.php');
require_once(MODX_BASE_PATH . 'assets/lib/Helpers/FS.php');

class DdaParams
{
    public $modx = null;
    protected $fs = null;
    public $DLTemplate = null;
    public $customTvName = 'DdaParams Custom TV';
    public $tv = array();
    public $documentData = array();
    public $config = array(
        'maxElements'        => 0,
        'nbDropdownElements' => 10,
        'searchMaxLength'    => 30,
        'searchMinLength'    => 0,
        'textField'          => 'text',
        'valueField'         => 'id',
        'htmlField'          => 'html',
        'divider'            => ',',
        'tokenConfig'        => array(
            'tpl' => '@CODE: <option value="[+id+]" selected>[+id+]. [+pagetitle+]</option>'
        )
    );
    public $tpl = 'assets/tvs/ddaparams/tpl/selector.tpl';
    public $jsListDefault = 'assets/tvs/ddaparams/js/scripts.json';
    public $jsListCustom = 'assets/tvs/ddaparams/js/custom.json';
    public $cssListDefault = 'assets/tvs/ddaparams/css/styles.json';
    public $cssListCustom = 'assets/tvs/ddaparams/css/custom.json';

    /**
     * DdaParams constructor.
     * @param \DocumentParser $modx
     * @param array $tv
     * @param array $documentData
     */
    public function __construct(\DocumentParser $modx, array $tv, array $documentData)
    {
        $this->modx = $modx;
        $this->tv = $tv;
        $this->documentData = $documentData;
        $this->DLTemplate = \DLTemplate::getInstance($this->modx);
        $this->fs = \Helpers\FS::getInstance();
        $this->loadConfig($tv['name']);
    }

    /**
     * @return bool|string
     */
    public function prerender()
    {
        $output = '';
        $plugins = $this->modx->pluginEvent;
        if ((array_search('ManagerManager',
                    $plugins['OnDocFormRender']) === false) && !isset($this->modx->loadedjscripts['jQuery'])
        ) {
            $output .= '<script type="text/javascript" src="' . $this->modx->config['site_url'] . 'assets/js/jquery/jquery-1.9.1.min.js"></script>';
            $this->modx->loadedjscripts['jQuery'] = array('version' => '1.9.1');
            $output .= '<script type="text/javascript">var jQuery = jQuery.noConflict(true);</script>';
        }
        $tpl = MODX_BASE_PATH . $this->tpl;
        if ($this->fs->checkFile($tpl)) {
            $output .= '[+js+][+styles+]' . file_get_contents($tpl);
        } else {
            $this->modx->logEvent(0, 3, "Cannot load {$this->tpl} .", $this->customTvName);

            return false;
        }

        return $output;
    }

    /**
     * @param $list
     * @param array $ph
     * @return string
     */
    public function renderJS($list, $ph = array())
    {
        $js = '';
        $scripts = MODX_BASE_PATH . $list;
        if ($this->fs->checkFile($scripts)) {
            $scripts = @file_get_contents($scripts);
            $scripts = $this->DLTemplate->parseChunk('@CODE:' . $scripts, $ph);
            $scripts = json_decode($scripts, true);
            $scripts = isset($scripts['scripts']) ? $scripts['scripts'] : $scripts['styles'];
            foreach ($scripts as $name => $params) {
                if (!isset($this->modx->loadedjscripts[$name])) {
                    if ($this->fs->checkFile($params['src'])) {
                        $this->modx->loadedjscripts[$name] = array('version' => $params['version']);
                        $val = explode('.', $params['src']);
                        if (end($val) == 'js') {
                            $js .= '<script type="text/javascript" src="' . $this->modx->config['site_url'] . $params['src'] . '"></script>';
                        } else {
                            $js .= '<link rel="stylesheet" type="text/css" href="' . $this->modx->config['site_url'] . $params['src'] . '">';
                        }
                    } else {
                        $this->modx->logEvent(0, 3, 'Cannot load ' . $params['src'], $this->customTvName);
                    }
                }
            }
        } else {
            if ($list == $this->jsListDefault) {
                $this->modx->logEvent(0, 3, "Cannot load {$this->jsListDefault} .", $this->customTvName);
            } elseif ($list == $this->cssListDefault) {
                $this->modx->logEvent(0, 3, "Cannot load {$this->cssListDefault} .", $this->customTvName);
            }
        }

        return $js;
    }

    /**
     * @return array
     */
    public function getTplPlaceholders()
    {
        $values_str = '';
        if(!empty($this->tv['value'])) {
            $params = explode(',', $this->tv['value']);
            $values = FilterParamValues::whereIn('id', $params)->get();
            foreach ($values as $value){
                $values_str .= '<option value="'.$value->id.'" selected>'.$value->id.'. '.$value->value.'</option>';
            }
        }
        $ph = array(
            'tv_id'              => $this->tv['id'],
            'tv_value'           => $this->tv['value'],
            'tv_name'            => $this->tv['name'],
            'doc_id'             => $this->documentData['id'],
            'doc_parent'         => $this->documentData['parent'],
            'doc_template'       => $this->documentData['template'],
            'site_url'           => $this->modx->config['site_url'],
            'timestamp'          => $this->getTimestamp(),
            'values'             => $values_str
        );
        unset($this->config['tokenConfig']);

        return array_merge($ph, $this->config);
    }

    /**
     * @return string
     */
    public function render()
    {
        $output = $this->prerender();
        if ($output !== false) {
            $ph = $this->getTplPlaceholders();
            ;
            $ph['js'] = $this->renderJS($this->jsListDefault, $ph) . $this->renderJS($this->jsListCustom, $ph);
            $ph['styles'] = $this->renderJS($this->cssListDefault, $ph) . $this->renderJS($this->cssListCustom, $ph);
            $output = $this->DLTemplate->parseChunk('@CODE:' . $output, $ph);
        }

        return $output;
    }

    /**
     * @param $config
     */
    protected function loadConfig($config)
    {
        if (empty($config)) {
            return;
        }
        $file = MODX_BASE_PATH . "assets/tvs/ddaparams/config/{$config}.php";
        if ($this->fs->checkFile($file)) {
            $_config = include($file);
            if (is_array($_config)) {
                $this->config = array_merge($this->config, $_config);
            }
        }
    }

    protected function getTimestamp()
    {
        $cachePath = defined('EVO_CORE_PATH') ? $this->modx->getSiteCacheFilePath() : MODX_BASE_PATH . $this->modx->getCacheFolder() . 'siteCache.idx.php';

        return filemtime($cachePath);
    }
}
