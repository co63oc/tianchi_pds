<?php

// This file is auto-generated, don't edit it. Thanks.
namespace AlibabaCloud\SDK\Sample;

use AlibabaCloud\SDK\Sample\Common;

class SortFile {
    static $count = 1;
    static $similar_map = [];
    public static function main($args){
        $c = file_get_contents("drive/SearchSimilarImageClusters_.json");
        $similar_list = json_decode($c, true);
        $similar_list = $similar_list['similar_image_clusters'];
        $similar_map = [];
        foreach ($similar_list as $k => $v) {
            $sort_list = [];
            foreach($v['files'] as $k2 => $v2) {
                $sort_list[$k2] = $v2['image_media_metadata']['image_quality']['overall_score'];
            }
            array_multisort($sort_list, SORT_DESC, $v['files']);

            $top = array_values($v['files'])[0];
            foreach($v['files'] as $k2 => $v2) {
                if ($v2['name'] != $top['name']) {
                    $similar_map[$v2['name']] = $top['name'];
                }
            }
        }

        self::$similar_map = $similar_map;


        $map1 = Common::GetMap();
        foreach ($map1 as $dirname => $name2) {
            echo $dirname . "\n";
            $ret = self::create("drive/root_Stories_${dirname}.json", $name2, $dirname);
            self::$count++;
        }
    }

    public static function create($path, $name2="", $dirname="") {
        $data = json_decode(file_get_contents($path), true);

        $similar_map = self::$similar_map;

        $files = [];
        foreach ($data['items'] as $v) {
            $files[$v['name']] = [
                'file_id' => $v['file_id'],
                'revision_id' => $v['revision_id'],
                'name' => $v['name'],
            ];
        }
        $score = [];
        $tags = [];
        foreach ($files as $k => $v) {
            $request = [
                "drive_id" => Common::get_drive_id(),
                "file_id" => $v['file_id']
            ];
            $file1 = "drive/" . $dirname . "/" . $v['name'] . ".json";
            $c = file_get_contents($file1);
            $data = json_decode($c, true);
            $score[$k] = $data['image_media_metadata']['image_quality']['overall_score'];
            $files[$k]['overall_score'] = $data['image_media_metadata']['image_quality']['overall_score'];
            $tags[$k] = $data['image_media_metadata']['image_tags'];
        }
        array_multisort($score, SORT_DESC, $files);

        //过滤相似图片
        $tags_new = [];
        foreach ($tags as $k => $v) {
            if (isset($similar_map[$k])) {
                $similar_target = $similar_map[$k];
                if (!isset($tags_new[$similar_target])) {
                    $tags_new[$similar_target] = $tags[$similar_target];
                    echo 'set similar_target' . "\n";
                }
                echo 'exist similar_target: ' . $k . " " . $similar_target . "\n";
                continue;
            }

            $tags_new[$k] = $v;
        }
        $tags = $tags_new;

        // 按标签值排列，取较前图片
        $tag_name = [];
        $score3 = [];
        foreach ($tags as $k => $v) {
            foreach ($v as $k2 => $v2) {
                //if ($v2['tag_level'] <= 2) {
                //    $tag_name[$v2['name']][$k] = $v2['confidence'] * $v2['centric_score'] * $files[$k]['overall_score']; 
                //}
                $tag_name[$v2['name']][$k] = $v2['confidence'] * $v2['centric_score'] * $files[$k]['overall_score']; 
            }
        }
        // 取标签中第一个元素
        $score_map = [];
        $tag_name_first = [];
        foreach ($tag_name as $name => $v) {
            arsort($v);
            if (!empty($v)) {
                $list1 = array_keys($v);
                $tag_name_first[] = $list1[0];

                $k2 = $list1[0];
                $v2 = $v[$list1[0]];
                if (!isset($score_map[$k2])) {
                    $score_map[$list1[0]] = ['name' => $name, 'score' => $v[$list1[0]]];
                } else {
                    if ($v2 > $score_map[$k2]['score']) {
                        $score_map[$k2]['score'] = $v2;
                    }
                }
            }
        }
        $tag_name_first = array_unique($tag_name_first);


        $files_new = [];
        foreach ($files as $k => $v) {
            if (in_array($k, $tag_name_first)) {
                $v['score2'] = $score_map[$k]['score'];
                $v['tag'] = $score_map[$k]['name'];

                // 设置不同过滤值, 结果可以有部分变化
                $score_limit = 0.40;
                if ($v['overall_score'] > 0.60 && $v['score2'] > $score_limit) {
                    $files_new[$k] = $v;
                }
            }
        }
        echo count($files) . " "  . count($files_new) . " " . $score_limit . "\n";

        // 从 $files_new 获取
        $top = $files_new;

        $out = Common::encode($top);
        file_put_contents("drive/" . $dirname . "/" . "select_" . Common::get_test_count() . ".json", $out);
        file_put_contents("drive/" . $dirname . "/" . "files.json", Common::encode($files));
        file_put_contents("drive/" . $dirname . "/" . "files_new_" . Common::get_test_count() . ".json", Common::encode($files_new));
    }
}
$path = __DIR__ . \DIRECTORY_SEPARATOR . '..' . \DIRECTORY_SEPARATOR . 'vendor' . \DIRECTORY_SEPARATOR . 'autoload.php';
if (file_exists($path)) {
    require_once $path;
}
SortFile::main(array_slice($argv, 1));
