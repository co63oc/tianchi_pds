<?php
namespace AlibabaCloud\SDK\Sample;
use AlibabaCloud\SDK\Sample\Common;

class CreateCustomizedStory {

    /**
     * @param string[] $args
     * @return void
     */
	public static function main($args){
		$map1 = Common::GetMap();
		$list1 = ["Tel_Number" => "150xxxxxxxx"];

		foreach ($map1 as $name => $name2) {
			$ret = self::create("drive/${name}/select_" . Common::get_test_count() . ".json", $name2);
			$list1['Stories'][$name2] = $ret['story_id'];
		}
		$out = json_encode($list1, JSON_PRETTY_PRINT);
		file_put_contents("../result/pds_result_" . Common::get_test_count() . ".json", $out);
		file_put_contents("../pds_result.json", $out);
	}

    public static function create($path, $name="") {
        $data = json_decode(file_get_contents($path), true);
        $files = [];
        $cover = [];
		foreach ($data as $v) {
			if (empty($cover)) {
				$cover = $v;
			}
			$files[] = [
				'file_id' => $v['file_id'],
				'revision_id' => $v['revision_id'],
			];
		}
        $storyCover = [
            "file_id" => $cover['file_id'],
            "revision_id" => $cover['revision_id'] 
        ];
        $request = [
            "drive_id" => Common::get_drive_id(),
            "story_type" => "user_created",
            "story_sub_type" => "user_created",
            "story_name" => $name,
            "story_cover" => $storyCover,
            "story_files" => $files
        ];

        return Common::CreateCustomizedStory($request);
    }
}
$path = __DIR__ . \DIRECTORY_SEPARATOR . '..' . \DIRECTORY_SEPARATOR . 'vendor' . \DIRECTORY_SEPARATOR . 'autoload.php';
if (file_exists($path)) {
    require_once $path;
}
CreateCustomizedStory::main(array_slice($argv, 1));
