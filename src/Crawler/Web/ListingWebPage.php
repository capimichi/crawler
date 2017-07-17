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
     * @return mixed
     */
    public abstract function getChildren();

    /**
     * @return mixed
     */
    public abstract function getChildUrls();

    /**
     * @return mixed
     */
    public abstract function getNextPageUrl();

    /**
     * @return mixed
     */
    public abstract function getPagesCount();
}