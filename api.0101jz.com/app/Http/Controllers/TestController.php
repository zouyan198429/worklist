<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use App\Models\SiteNews;
use App\Models\test\Comment;
use App\Models\test\Post;
use Illuminate\Http\Request;

class TestController extends ApiController
{
    /**
     * 首页
     *
     * @param int $id
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function index()
    {

        // 获得指定新闻的相关资源
        // 单条
//        $siteNew = SiteNews::with('siteResources')->find(1);
//        foreach ($siteNew->siteResources as $resource) {
//            echo '<pre>';
//            print_r($resource->resource_name);
//            echo '</pre>';
//        }

        // 主表多条
        // 注意: 一次多个时，需要用with 渴求式加载来减少请求次数
//        $siteNews = SiteNews::with('siteResources')->find([1,2]);
//        foreach($siteNews as $siteNew){
//            foreach ($siteNew->siteResources as $resource) {
//                echo '<pre>';
//                print_r($resource->resource_name);
//                echo '</pre>';
//            }
//        }
        // {"sql":"select `site_news`.*, `resource_module`.`resource_id` as `pivot_resource_id`, `resource_module`.`module_id` as `pivot_module_id`, `resource_module`.`module_type` as `pivot_module_type` from `site_news` inner join `resource_module` on `site_news`.`id` = `resource_module`.`module_id` where `resource_module`.`resource_id` = ? and `resource_module`.`module_type` = ?
        //
        // 获得资料的新闻
//        $resource = Resource::find(1);
//        foreach ($resource->siteNews as $siteNew) {
//            echo '<pre>';
//            print_r($siteNew->new_title);
//            echo '</pre>';
//        }
//        foreach ($resource->siteNews()->select('site_news.id','site_news.new_title')->get() as $siteNew) {
//            echo '<pre>';
//            print_r($siteNew->new_title);
//            echo '</pre>';
//        }
//        $siteResources = Resource::with([
//            'siteNews'=> function ($query) {
//                    $query->select('site_news.id','site_news.new_title');
//            }
//        ])->find([1,2]);
//        foreach ($siteResources as $resource){
//            foreach ($resource->siteNews as $siteNew) {
//                echo '<pre>';
//                print_r($siteNew->new_title);
//                echo '</pre>';
//            }
//        }
//        $resource = Resource::with('getCustom_morphedByMany_SiteNews')->find(1);
//        $resource = Resource::find(1);
//        $resource->load('getCustom_morphedByMany_SiteNews');
//        foreach ($resource->getCustom_morphedByMany_SiteNews as $siteNew) {
//            echo '<pre>';
//            print_r($siteNew->new_title);
//            echo '</pre>';
//        }
//        $siteResources = Resource::with([
//            'getCustom_morphedByMany_SiteNews'=> function ($query) {
//                $query->select('site_news.id','site_news.new_title');
//            }
//        ])->find([1,2]);
//        $siteResources = Resource::with('getCustom_morphedByMany_SiteNews')->find([1,2]);
//        $siteResources = Resource::find([1,2]);
//        foreach ($siteResources as $resource){
//            foreach ($resource->getCustom_morphedByMany_SiteNews as $siteNew) {
//                echo '<pre>';
//                print_r($siteNew->new_title);
//                echo '</pre>';
//            }
//        }
        // 同步修改关系
        // $siteNew = SiteNews::find(1)->updateResourceByResourceIds([1,2,3]);
        // $siteNew->siteResources()->sync([1, 2]);


        // 一对多
//        $Post = Post::find(2);
//        foreach($Post->comments as $Comment){
//            echo $Comment->nr . '<br/>';
//        }

//        $Posts = Post::with('comments')->find([1,2]);
//        $Posts = Post::find([1,2]);
//        $Posts->load('comments');
//        foreach($Posts as $Post){
//            foreach($Post->comments as $Comment){
//                echo $Comment->nr . '<br/>';
//            }
//        }

         // $Comment = Post::find(1)->comments()->where('nr', '评论cccc')->first();
//        $Comment = Comment::find(1);
//        echo $Comment->post->title . '<br/>';
//        $Comments = Comment::find([1,7]);
//        $Comments->load('post');
//        foreach($Comments as $Comment){
//            echo $Comment->post->title . '<br/>';
//        }
        return 'dsfasfs';
    }
}
