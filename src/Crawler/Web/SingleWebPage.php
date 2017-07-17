<?php
/**
 * Created by PhpStorm.
 * User: michelecapicchioni
 * Date: 17/07/17
 * Time: 21:48
 */

namespace Crawler\Web;


/**
 * Class SingleWebPage
 * @package Crawler\Web
 */
abstract class SingleWebPage extends WebPage
{

    /**
     * @return null|string
     */
    public function getTitle()
    {
        $dom = $this->getDomDocument();
        $nodes = $dom->getElementsByTagName('h1');
        $title = null;
        foreach ($nodes as $node) {
            $title = $node->nodeValue;
        }
        return $title;
    }

}