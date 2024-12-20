<?php

namespace Modules\WebHunt\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class WebHuntDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // $this->call("OthersTableSeeder");

        \Modules\WebHunt\Models\WebHunt::upsert(
            [
                "title" => "书本网-免费全本小说",
                "slug" => "https://www.cdrcxg.com/",
                "description" => "免费小说推荐,更多好看的小说阅读尽在书本网，书本网是国内全集全本完结TXT电子书免费下载分享平台，大家可以把好看的完结电子书免费上传到这里，也可下载其他书友的全集txt小说，快来书本网分享你的优秀电子书吧！",
                "text" => json_encode([
                    'domains' => ['cdrcxg.com', 'www.cdrcxg.com'],
                    'scan_urls' => ['https://www.cdrcxg.com/'],
                    'content_url_regexes' => ['https://www.cdrcxg.com/tn/\d+/'],
                    'list_url_regexes' => ['https://www.cdrcxg.com/\.+'],
                    'export' => [
                        'type' => 'db',
                        'table' => 'novels',
                    ],

                    "groups" => [
                        ["name" => "玄幻奇幻", "additional_url" => "/class/1_1/",],
                        ["name" => "武侠仙侠", "additional_url" => "/class/2_1/",],
                        ["name" => "女频言情", "additional_url" => "/class/3_1/",],
                        ["name" => "现代都市", "additional_url" => "/class/4_1/",],
                        ["name" => "历史军事", "additional_url" => "/class/5_1/",],
                        ["name" => "游戏竞技", "additional_url" => "/class/6_1/",],
                        ["name" => "科幻灵异", "additional_url" => "/class/7_1/",],
                        ["name" => "美文同人", "additional_url" => "/class/8_1/",],
                        ["name" => "其他类型", "additional_url" => "/class/9_1/",],
                    ],
                    "fields" => [
                        [
                            "name" => "title",
                            "seletor" => '//*[@id="newscontent"]/div/ul/li[1]/span[2]/a',
                            'required' => true,
                        ],
                        [
                            "name" => "latest_chapter",
                            "seletor" => '//*[@id="newscontent"]/div/ul/li[1]/span[3]/a',
                        ],
                        [
                            "name" => "author",
                            "seletor" => '//*[@id="newscontent"]/div/ul/li[1]/span[4]',
                        ],
                        [
                            "name" => "created_at",
                            "seletor" => '//*[@id="newscontent"]/div/ul/li[1]/span[5]',
                        ],
                    ],
                    'content_list_fields' => [],
                    'content_item_fields' => [],
                ], JSON_UNESCAPED_UNICODE),
                "type" => "post",
                "status" => "public"
            ],
            ["slug"],
            ["title", "description", "text", "type", "status"]
        );
    }
}
