<?php
/**
 * Created by PhpStorm.
 * User: michelecapicchioni
 * Date: 17/07/17
 * Time: 21:48
 */

namespace Crawler\Web;


/**
 * Class ListingWebPage
 * @package Crawler\Web
 */
abstract class ListingWebPage extends WebPage
{
    /**
     * @return array
     */
    public abstract function getChildren();

    /**
     * @return string|null
     */
    public function getNextPageUrl()
    {
        return null;
    }

    /**
     * @return int
     */
    public function getPagesCount()
    {
        return 0;
    }
}