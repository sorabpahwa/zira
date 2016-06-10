<?php
/**
 * Zira project.
 * home.php
 * (c)2016 http://dro1d.ru
 */

namespace Dash\Forms;

use Zira;
use Zira\Form;
use Zira\Locale;

class Home extends Form
{
    protected $_id = 'dash-home-form';

    protected $_label_class = 'col-sm-4 control-label';
    protected $_input_wrap_class = 'col-sm-8';
    protected $_input_offset_wrap_class = 'col-sm-offset-4 col-sm-8';
    protected $_select_wrapper_class = 'col-sm-8';

    protected $_checkbox_inline_label = false;

    public function __construct()
    {
        parent::__construct($this->_id);
    }

    protected function _init()
    {
        $this->setRenderPanel(false);
        $this->setFormClass('form-horizontal dash-window-form');
    }

    protected function _render()
    {
        $html = $this->open();
        $html .= $this->select(Locale::t('Layout'),'home_layout',Zira\View::getLayouts());
        $html .= $this->input(Locale::t('Page title'), 'home_title', array('placeholder'=>Locale::t('max. %s characters', 255)));
        $html .= $this->input(Locale::t('Window title'), 'home_window_title', array('placeholder'=>Locale::t('max. %s characters', 255)));
        $html .= $this->input(Locale::t('Keywords'), 'home_keywords', array('placeholder'=>Locale::t('max. %s characters', 255)));
        $html .= $this->input(Locale::t('Description'), 'home_description', array('placeholder'=>Locale::t('max. %s characters', 255)));
        $html .= $this->input(Locale::t('Records limit'), 'home_records_limit');
        $html .= $this->checkbox(Locale::t('Display records'), 'home_records_enabled', null, false);
        $html .= $this->radio(Locale::t('Category sorting'), 'home_categories_order', array('asc' => Locale::t('ascending'), 'desc' => Locale::t('descending'), 'name' => Locale::t('by name')), null, false);
        $html .= $this->input(Locale::t('Link record'), 'home_record_name', array('placeholder'=>Locale::t('Enter system name')));
        $html .= $this->close();
        return $html;
    }

    protected function _validate() {
        $validator = $this->getValidator();

        $validator->registerString('home_title',null,255,false,Locale::t('Invalid value "%s"',Locale::t('Page title')));
        $validator->registerNoTags('home_title',Locale::t('Invalid value "%s"',Locale::t('Page title')));
        $validator->registerUtf8('home_title',Locale::t('Invalid value "%s"',Locale::t('Page title')));
        $validator->registerString('home_window_title',null,255,false,Locale::t('Invalid value "%s"',Locale::t('Window title')));
        $validator->registerNoTags('home_window_title',Locale::t('Invalid value "%s"',Locale::t('Window title')));
        $validator->registerUtf8('home_window_title',Locale::t('Invalid value "%s"',Locale::t('Window title')));
        $validator->registerString('home_keywords',null,255,false,Locale::t('Invalid value "%s"',Locale::t('Keywords')));
        $validator->registerNoTags('home_keywords',Locale::t('Invalid value "%s"',Locale::t('Keywords')));
        $validator->registerUtf8('home_keywords',Locale::t('Invalid value "%s"',Locale::t('Keywords')));
        $validator->registerString('home_description',null,255,false,Locale::t('Invalid value "%s"',Locale::t('Description')));
        $validator->registerNoTags('home_description',Locale::t('Invalid value "%s"',Locale::t('Description')));
        $validator->registerUtf8('home_description',Locale::t('Invalid value "%s"',Locale::t('Description')));
        $validator->registerNumber('home_records_limit',1,null,true,Locale::t('Invalid value "%s"',Locale::t('Records limit')));
        $validator->registerCustom(array(get_class(), 'checkLayout'), 'home_layout', Locale::t('Invalid value "%s"',Locale::t('Layout')));
        $validator->registerCustom(array(get_class(), 'checkSorting'), 'home_categories_order', Locale::t('Invalid value "%s"',Locale::t('Category sorting')));
        $validator->registerString('home_record_name',null,255,false,Locale::t('Invalid value "%s"',Locale::t('Link record')));
        $validator->registerNoTags('home_record_name',Locale::t('Invalid value "%s"',Locale::t('Link record')));
        $validator->registerUtf8('home_record_name',Locale::t('Invalid value "%s"',Locale::t('Link record')));
    }

    public static function checkLayout($layout) {
        $layouts = Zira\View::getLayouts();
        return array_key_exists($layout, $layouts);
    }

    public static function checkSorting($order) {
        return in_array($order, array('asc', 'desc', 'name'));
    }
}