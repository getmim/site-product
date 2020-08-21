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
use ProductLastSeen\Library\Seen;

class ProductController extends \Site\Controller
{
    public function singleAction() {
        $slug = $this->req->param->slug;

        $product = Product::getOne(['slug'=>$slug, 'status'=>2]);
        if(!$product)
            return $this->show404();

        if(module_exists('product-last-seen') && $this->user->isLogin())
            Seen::add($this->user->id, $product->id);

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