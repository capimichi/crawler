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
     * Currently working only with .class and #id and selector
     *
     * @param $expression
     *
     * @return XpathQueryBuilder
     */
    public function addQueryByCss($expression)
    {
        $pieces = explode(' ', $expression);
        foreach ($pieces as $piece) {
            $isTag = true;
            if (preg_match('/\./is', $piece)) {
                $pieceSplit = explode(".", $piece);
                $selector = empty($pieceSplit[0]) ? "*" : $pieceSplit[0];
                $class = $pieceSplit[1];
                $this->addQueryByClass($class, $selector);
                $isTag = false;
            }
            if (preg_match('/\#/is', $piece)) {
                $pieceSplit = explode("#", $piece);
                $selector = empty($pieceSplit[0]) ? "*" : $pieceSplit[0];
                $id = $pieceSplit[1];
                $this->addQueryById($id, $selector);
                $isTag = false;
            }
            if ($isTag) {
                $this->addQueryBySelector($piece);
            }
        }

        return $this;
    }

    /**
     * @param $class
     * @param $selector
     *
     * @return XpathQueryBuilder
     */
    public function addQueryByClass($class, $selector = "*")
    {
        $this->addQueryByAttribute("class", $class, $selector);
        return $this;
    }

    /**
     * @param $id
     * @param $selector
     *
     * @return XpathQueryBuilder
     */
    public function addQueryById($id, $selector = "*")
    {
        $this->addQueryByAttribute("id", $id, $selector);
        return $this;
    }

    /**
     * @param $attribute
     * @param $value
     * @param $selector
     *
     * @return XpathQueryBuilder
     */
    public function addQueryByAttribute($attribute, $value, $selector = "*")
    {
        $this->query .= "//{$selector}[contains(concat(' ', normalize-space(@{$attribute}), ' '), ' {$value} ')]";
        return $this;
    }

    /**
     * @param $selector
     *
     * @return XpathQueryBuilder
     */
    public function addQueryBySelector($selector)
    {
        $this->query .= "//{$selector}";
        return $this;
    }

    /**
     * @param $index
     *
     * @return XpathQueryBuilder
     */
    public function addQueryIndex($index)
    {
        $this->query .= "[{$index}]";
        return $this;
    }

    /**
     * @param $attribute
     *
     * @return XpathQueryBuilder
     */
    public function setQueryAttribute($attribute)
    {
        $this->query .= "/@{$attribute}";
        return $this;
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

    /**
     * Clear the query
     */
    public function clearQuery()
    {
        $this->query = "";
    }

    /**
     * @return \DOMXPath
     */
    public function getDomXpath()
    {
        return $this->domXpath;
    }


}