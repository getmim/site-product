<?php
/**
 * ProductController
 * @package site-product
 * @version 0.0.1
 */

namespace SiteProduct\Controller;

use SiteProduct\Library\Meta;
use Product\Model\Product;
use LibFormatter\Library\Formatter;

class ProductController extends \Site\Controller
{
    public function singleAction() {
        $slug = $this->req->param->slug;

        $product = Product::getOne(['slug'=>$slug, 'status'=>2]);
        if(!$product)
            return $this->show404();

        $product = Formatter::format('product', $product, ['user']);

        $params = [
            'product' => $product,
            'meta' => Meta::single($product)
        ];

        $this->res->render('product/single', $params);
        $this->res->setCache(86400);
        $this->res->send();
    }
}