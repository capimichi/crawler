<?php
/**
 * Created by PhpStorm.
 * User: michelecapicchioni
 * Date: 17/07/17
 * Time: 21:48
 */

namespace Crawler\Web;


/**
 * Class CategoriesWebPage
 * @package Crawler\Web
 */
abstract class CategoriesWebPage extends WebPage
{

    /**
     * @return mixed
     */
    public abstract function getCategoryUrls();

}