<?php
App::uses('Component', 'Controller');

/**
 * trim request data
 *
 * Settings
 * `encoding` - specify charset encoding. if not specify, use App.encoding.
 *
 * @uses Component
 * @package Plugin.PreFilter.Controller.Component
 */
class TrimComponent extends Component
{

    /**
     * hexadeciaml notation of double-byte space
     *
     * @var array
     * @access protected
     */
    protected $_mb_spaces = array(
        'utf-8' => "\xe3\x80\x80",
        'euc-jp' => "\xA1\xA1",
        'eucjp-win' => "\xA1\xA1",
        'sjis' => "\x81\x40",
        'sjis-win' => "\x81\x40",
    );

    public function initialize(Controller $controller)
    {
        if (!isset($this->settings['encoding']) || is_null($this->settings['encoding'])) {
            $this->settings['encoding'] = Configure::read('App.encoding');
        }

        foreach (array('data', 'query') as $prop) {
            if (empty($controller->request->$prop)) {
                continue;
            }

            $values = $controller->request->$prop;
            array_walk_recursive($values, array($this, '_execute'));
            $controller->request->$prop = $values;
        }
    }

    protected function _execute(&$str)
    {
        $str = $this->_deleteNull($str);
        $str = $this->_trim($str);
    }

    protected function _deleteNull($str)
    {
        return str_replace("\0", '', $str);
    }

    protected function _trim($str)
    {
        $str = trim($str);

        $encoding = $this->settings['encoding'];
        $space = Hash::get($this->_mb_spaces, strtolower($encoding));
        if (is_null($space)) {
            throw new Exception('Invalid encoding ' . $encoding);
        }

        $str = preg_replace("/^(?:$space|\x20)+/", '', $str);
        $str = preg_replace("/(?:$space|\x20)+$/", '', $str);

        return $str;
    }

}
