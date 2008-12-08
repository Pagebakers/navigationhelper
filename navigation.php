<?php
/**
 * NavigationHelper
 *
 * This helper helps you build your menu's and adds some extra functionality to links
 *
 * PHP versions 4 and 5
 *
 * Licensed under The LGPL License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright		Copyright 2005-2008, Pagebakers
 * @link			http://www.pagebakers.nl
 * @version         0.1
 * @license			http://www.gnu.org/licenses/lgpl.html GNU LESSER GENERAL PUBLIC LICENSE
 */
class NavigationHelper extends HtmlHelper {

    /**
     * Returns a formatted <ul> with links
     * @param array $items An array containing all children for the list
     * @param array $options The html attributes for the list
     * @return string The formatted <ul> list
     */
    function menu($items, $attributes = array()) {
        if(!is_array($items) || empty($items)) {
            return;
        }

        $links = $class = array();

        foreach($items as $item) {
            if(count($item) == 2) {
                list($text, $url) = $items;
                $itemOptions = array();
            }else{
            	list($text, $url, $itemOptions) = $item;
            }

            if(isset($itemOptions['activeClass']) && !empty($itemOptions['activeClass'])){
            	$class['activeClass'] = $itemOptions['activeClass'];
        	}else{
        		$class['activeClass'] = 'active';
        	}

            if(isset($itemOptions['nonActiveClass']) && !empty($itemOptions['nonActiveClass'])){
            	$class['nonActiveClass'] = $itemOptions['nonActiveClass'];
        	}else{
        		$class['nonActiveClass'] = '';
        	}

        	unset($itemOptions['activeClass'], $itemOptions['nonActiveClass']);

            $links[] = sprintf($this->tags['li'], ' class="'.($this->isActiveController($url) ? $class['activeClass'] : $class['nonActiveClass']).'"', parent::link($text, $url, $itemOptions));
            unset($class, $itemOptions);
        }

        return sprintf($this->tags['ul'], $this->_parseAttributes($attributes, null, ' ', ''), implode("\n", $links));
    }

    /**
     * Returns a link with class="active" if the url is the currently active url
     * @param string $title The content to be wrapped in <a/>
     * @param string $url The url of the link
     * @param array $options Html attributes of the link
     * @return string an <a/> element
     */
    function link($title, $url, $options = array()) {
        if($this->isActive($url)) {
            if(isset($options['class'])) {
                $options['class'] .= ' active';
            } else {
                $options['class'] = 'active';
            }
        }

        $out = parent::link($title, $url, $options);

        return $out;
    }

    /**
     * Checks if a given url is currently active
     * @param mixed $url The url to check, can be and valid router string or array
     * @return boolean Returns true if the passed url is active
     */
    function isActive($url) {
        $currentRoute = Router::currentRoute();

        $url = Router::url($url);

        if($currentRoute[0] == $url) {
            return true;
        }

        return false;
    }

    /**
     * Checks if a given url is currently active controller
     * @param mixed $url The url to check, can be and valid router string or array
     * @return boolean Returns true if the passed url is active
     */
    function isActiveController($url) {
        if(!is_array($url)) {
            $url = Router::parse($url);
        }

        if($url['controller'] == $this->params['controller']) {
            return true;
        }

        return false;
    }
}
?>