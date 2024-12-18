<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class OptionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        \App\Models\Option::upsert(
            [
                [
                    'name' => 'global.type',
                    'type' => 'object',
                    'user' => 0,
                    'value' => serialize([
                    ])
                ],
                [
                    'name' => 'global.status',
                    'type' => 'object',
                    'user' => 0,
                    'value' => serialize([
                        'public' => ['value' => 'public', 'name' => 'public', 'nameCn' => '公开',],
                        'protect' => ['value' => 'protect', 'name' => 'protect', 'nameCn' => '受限',],
                        'private' => ['value' => 'private', 'name' => 'private', 'nameCn' => '私有',],
                    ])
                ],
                [
                    'name' => 'meta.type',
                    'type' => 'object',
                    'user' => 0,
                    'value' => serialize([
                        'module' => ['value' => 'module', 'name' => 'Module', 'nameCn' => '模块',],
                        'tag' => ['value' => 'tag', 'name' => 'Tag', 'nameCn' => '标签',],
                        'category' => ['value' => 'category', 'name' => 'Category', 'nameCn' => '分类',],
                        'branch' => ['value' => 'branch', 'name' => 'Branch', 'nameCn' => '分支',],
                        'group' => ['value' => 'group', 'name' => 'Group', 'nameCn' => '分组',],
                        'collection' => ['value' => 'collection', 'name' => 'Collection', 'nameCn' => '',],
                    ])
                ],
                [
                    'name' => 'meta.status',
                    'type' => 'object',
                    'user' => 0,
                    'value' => serialize([
                        'public' => ['value' => 'public', 'name' => 'public', 'nameCn' => '公开',],
                        'protect' => ['value' => 'protect', 'name' => 'protect', 'nameCn' => '受限',],
                        'private' => ['value' => 'private', 'name' => 'private', 'nameCn' => '私有',],
                    ])
                ],
                [
                    'name' => 'content.type',
                    'type' => 'object',
                    'user' => 0,
                    'value' => serialize([
                        'template' => ['value' => 'template', 'name' => 'Template', 'nameCn' => '模板',],
                        'template-draft' => ['value' => 'template-draft', 'name' => 'Template(Draft)', 'nameCn' => '模板（草稿）',],
                        'post' => ['value' => 'post', 'name' => 'Post', 'nameCn' => '文章',],
                        'post-draft' => ['value' => 'post-draft', 'name' => 'Post(Draft)', 'nameCn' => '文章（草稿）',],
                    ])
                ],
                [
                    'name' => 'content.status',
                    'type' => 'object',
                    'user' => 0,
                    'value' => serialize([
                        'public' => ['value' => 'public', 'name' => 'public', 'nameCn' => '公开',],
                        'protect' => ['value' => 'protect', 'name' => 'protect', 'nameCn' => '受限',],
                        'private' => ['value' => 'private', 'name' => 'private', 'nameCn' => '私有',],
                    ])
                ],
                [
                    'name' => 'field.type',
                    'type' => 'object',
                    'user' => 0,
                    'value' => serialize([
                        'text' => ['value' => 'text', 'name' => 'text', 'nameCn' => '文本',],
                        'object' => ['value' => 'object', 'name' => 'object', 'nameCn' => '对象',],
                    ])
                ],
            ],
            ['name', 'user'],
            ['value']
        );
    }
}
