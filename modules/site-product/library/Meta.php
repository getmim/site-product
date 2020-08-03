<?php
/**
 * Meta
 * @package site-product
 * @version 0.0.1
 */

namespace SiteProduct\Library;

use ProductStat\Model\ProductStat as PStat;

class Meta
{
    static function single(object $page){
        $result = [
            'head' => [],
            'foot' => []
        ];

        $home_url = \Mim::$app->router->to('siteHome');

        // reset meta
        if(!is_object($page->meta))
            $page->meta = (object)[];

        $def_meta = [
            'title'         => $page->name,
            'description'   => $page->content->chars(160),
            'schema'        => 'Product',
            'keyword'       => ''
        ];

        foreach($def_meta as $key => $value){
            if(!isset($page->meta->$key) || !$page->meta->$key)
                $page->meta->$key = $value;
        }

        $result['head'] = [
            'description'       => $page->meta->description,
            'published_time'    => $page->created,
            'schema.org'        => [],
            'type'              => 'article',
            'title'             => $page->meta->title,
            'updated_time'      => $page->updated,
            'url'               => $page->page,
            'metas'             => []
        ];

        $meta_image = [
            '@type'         => 'ImageObject',
            'url'           => $page->cover->url->_600x400,
            'height'        => 600,
            'width'         => 400
        ];

        // schema breadcrumbList
        $result['head']['schema.org'][] = [
            '@context'  => 'http://schema.org',
            '@type'     => 'BreadcrumbList',
            'itemListElement' => [
                [
                    '@type' => 'ListItem',
                    'position' => 1,
                    'item' => [
                        '@id' => $home_url,
                        'name' => \Mim::$app->config->name
                    ]
                ],
                [
                    '@type' => 'ListItem',
                    'position' => 2,
                    'item' => [
                        '@id' => $home_url . '#product',
                        'name' => 'Products'
                    ]
                ]
            ]
        ];

        $rate = [
            '@type' => 'AggregateRating',
            'ratingValue' => 0,
            'reviewCount' => 0,
            'bestRating'  => 0,
            'worstRating' => 0
        ];

        if(module_exists('product-stat')){
            $rate['ratingValue'] = $page->stat;
            $rate['reviewCount'] = PStat::count(['product'=>$page->id->value]);
            if(!$rate['reviewCount'])
                $rate['reviewCount'] = 1;
        }

        $offers = [
            '@type'           => 'Offer',
            'availability'    => 'http://schema.org/InStock',
            'price'           => 0,
            'priceCurrency'   => 'IDR',
            'url'             => $page->page,
            'priceValidUntil' => date('c', strtotime('+1 month'))
        ];
        foreach($page->price as $prc){
            if($prc){
                $offers['price'] = $prc;
                break;
            }
        }

        // schema page
        $result['head']['schema.org'][] = [
            '@context'      => 'http://schema.org',
            '@type'         => $page->meta->schema,
            'name'          => $page->meta->title,
            'description'   => $page->meta->description,
            'brand'         => \Mim::$app->meta->schemaOrg( \Mim::$app->config->name ),
            'url'           => $page->page,
            'image'         => $meta_image,
            'sku'           => 'mim/product/'.$page->id,
            'mpn'           => 'mim/product/'.$page->id,
            'aggregateRating' => $rate,
            'offers'        => $offers,
            'review'        => []
        ];

        return $result;
    }
}