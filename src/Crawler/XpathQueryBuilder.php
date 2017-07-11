<?php
/**
 * Created by PhpStorm.
 * User: michelecapicchioni
 * Date: 11/07/17
 * Time: 19:08
 */

namespace Crawler;


class XpathQueryBuilder
{
    /**
     * @var \DOMXPath
     */
    protected $domXpath;

    /**
     * @var string
     */
    protected $query;

    /**
     * XpathQueryBuilder constructor.
     */
    public function __construct()
    {
        $this->query = "";
    }


    /**
     * @param string $content
     *
     * @return XpathQueryBuilder
     */
    public static function createFromContent($content)
    {
        $domDocument = new \DOMDocument();

        @$domDocument->loadHTML($content);

        $xpathQueryBuilder = new XpathQueryBuilder();

        $xpathQueryBuilder->setDomXpath(new \DOMXPath($domDocument));

        return $xpathQueryBuilder;
    }

    /**
     * @param \DOMDocument $domDocument
     *
     * @return XpathQueryBuilder
     */
    public static function createFromDomDocument($domDocument)
    {
        $xpathQueryBuilder = new XpathQueryBuilder();

        $xpathQueryBuilder->setDomXpath(new \DOMXPath($domDocument));

        return $xpathQueryBuilder;
    }

    /**
     * @param \DOMXPath $domXpath
     *
     * @return XpathQueryBuilder
     */
    public static function createFromDomXpath($domXpath)
    {
        $xpathQueryBuilder = new XpathQueryBuilder();

        $xpathQueryBuilder->setDomXpath($domXpath);

        return $xpathQueryBuilder;
    }

    /**
     * @param \DOMXPath $domXpath
     */
    public function setDomXpath($domXpath)
    {
        $this->domXpath = $domXpath;
    }

    /**
     * @return string
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @param $class
     * @param $selector
     */
    public function addQueryByClass($class, $selector = "")
    {
        $this->query .= "//{$selector}[contains(concat(' ', normalize-space(@class), ' '), ' {$class} ')]";
    }

    /**
     * @param $id
     * @param $selector
     */
    public function addQueryById($id, $selector = "")
    {
        $this->query .= "//{$selector}[contains(concat(' ', normalize-space(@id), ' '), ' {$id} ')]";
    }

    /**
     * @param $selector
     */
    public function addQueryBySelector($selector)
    {
        $this->query .= "//{$selector}";
    }

    /**
     * @param $index
     */
    public function addQueryIndex($index)
    {
        $this->query .= "[{$index}]";
    }

    /**
     * @param $attribute
     */
    public function addQueryAttribute($attribute)
    {
        $this->query .= "/@{$attribute}";
    }

    /**
     * @return \DOMNodeList
     */
    public function getResults()
    {
        return $this->domXpath->query($this->query);
    }

    /**
     * @param \DOMNode $child
     * @return \DOMNodeList
     */
    public function getResultsFromChild($child)
    {
        return $this->domXpath->query("." . $this->query, $child);
    }
}