<?php
namespace Eddy\Crawlers\PluginBoutique;

use Symfony\Component\DomCrawler\Crawler as DomCrawler;

class GetProductMetadata
{
    private static array $validAttributes = [
        'content' => true,
        'itemprop' => true,
    ];

    private static string $filter = 'body > .js-site > .banner_background > .content > .page-product';

    public function __construct(private DomCrawler $crawler)
    {}

    private function getDescription(\DOMAttr $attribute)
    {
        return trim($attribute->ownerElement->textContent);
    }

    private function getRating(\DOMAttr $attribute)
    {
        $nodes = $attribute->ownerElement->childNodes;
        $result = [];
        foreach ($nodes as $node) {
            if ($node->attributes) {
                $data = [];
                foreach ($node->attributes as $id => $a) {
                    [$key, $val] = $this->extractAttributeData($a);
                    $data[$id] = [$key => $val];
                }
                $result[] = $data;
            }
        }

        return $result;
    }

    private function getPriceFromOffers(\DOMAttr $attribute)
    {
        $el = $attribute->ownerElement;
        $childNodes = $el->childNodes;
        $result = [];
        /** @var ?\DOMNode */
        foreach ($childNodes as $node) {
            $attr = $node->attributes;
            if ($attr) {
                $data = [];
                foreach ($attr as $id => $a) {
                    [$key, $val] = $this->extractAttributeData($a);
                    $data[$id] = $val;
                    // $data[$id] = [$key => $val];
                }
                $result[] = $data;
            }
        }
        return $result;
    }

    private function getItemProp(string $name, \DOMAttr $attribute)
    {
        $data = [
            'itemprop' => $name,
            'content' => $name,
        ];
        switch ($name) {
            case 'aggregateRating':
                $data['content'] = $this->getRating($attribute);
                break;
            case 'description':
                $data['content'] = $this->getDescription($attribute);
                break;
            case 'offers':
                $data['content'] = $this->getPriceFromOffers($attribute);
                break;
            default:
                return $name;
        }

        return $data;
    }

    private function extractAttributeData(\DOMAttr $attribute)
    {
        $key = $attribute->name;
        $val = $attribute->value;
        return [$key, $val];
    }

    private function scanAttributes(\DOMNamedNodeMap $attributes)
    {
        $data = [];
        foreach ($attributes as $val) {
            [$name, $value] = $this->extractAttributeData($val);
            if (isset(static::$validAttributes[$name])) {
                if ($name === 'itemprop') {
                    $d = $this->getItemProp($value, $val);
                    if (is_array($d)) {
                        return $d;
                    } else $data[$name] = $d;
                } else $data[$name] = $value;
            }
        }

        return empty($data) ? null : $data;
    }

    private function getData(\DOMNode $node)
    {
        $data = [];
        /** @var \DOMNode */
        foreach ($node->childNodes as $childNode) {
            if ($childNode->attributes) {
                $d = $this->scanAttributes($childNode->attributes);
                // dump($d);
                if ($d) $data[] = $d;
            }
        }

        return empty($data) ? null : $data;
    }

    private function scanChildNodes(DomCrawler $nodes)
    {
        $data = [];
        foreach ($nodes as $node) {
            $d = $this->getData($node);
            if ($d) $data[] = $d;
        }

        return $data;
    }

    public function scan(?string $html = null)
    {
        if ($html) {
            $this->crawler->clear();
            $this->crawler->addContent($html);
        }

        $data = null;

        $el = $this->crawler->filter(static::$filter)
            ->first();
        if ($el && $el->children()->count() > 0) {
            $data = $this->scanChildNodes($el->children());

            if ($data) {
                $data = array_merge(...array_values($data));
            }
        }

        return $data;
    }
}
