<?php

namespace AlibabaCloud\SDK\Sample;

use AlibabaCloud\SDK\Pds\V20220301\Pds;
use AlibabaCloud\Credentials\Credential;
use \Exception;
use AlibabaCloud\Tea\Exception\TeaError;
use AlibabaCloud\Tea\Utils\Utils;

use AlibabaCloud\Credentials\Credential\Config;
// use AlibabaCloud\SDK\Pds\V20220301\Models\CreateCustomizedStoryRequest;
use AlibabaCloud\Tea\Model;
use AlibabaCloud\Tea\Utils\Utils\RuntimeOptions;

use AlibabaCloud\OpenApiUtil\OpenApiUtilClient;
use Darabonba\OpenApi\Models\OpenApiRequest;
use Darabonba\OpenApi\Models\Params;
use Darabonba\OpenApi\OpenApiClient;

class Common {

    /**
     * 使用BearerToken初始化账号Client
     * @param string $bearerToken
     * @return Pds Client
     */
    public static function createClient($bearerToken){
        $credentialConfig = new Config([
            "type" => "bearer",
            "bearerToken" => $bearerToken
        ]);
        $credential = new Credential($credentialConfig);
        $config = new \Darabonba\OpenApi\Models\Config([
            "credential" => $credential
        ]);
        // 访问的域名
        $config->endpoint = "hz11233.api.aliyunpds.com";
        return new Pds($config);
    }

    public static function create($request, $action, $pathname) {
        // 初始化 Client，采用 OAuth 鉴权访问的方式，更多鉴权访问方式请参见：https://help.aliyun.com/document_detail/311677.html
        $client = self::createClient("<token>");

        $runtime = new RuntimeOptions([]);
        $headers = [];

        try {
            echo $action . "\n";
            $ret_data = self::create_exec($client, $request, $headers, $runtime, $action, $pathname);
            return $ret_data['body'];
        }
        catch (Exception $error) {
            var_dump($error);
            if (!($error instanceof TeaError)) {
                $error = new TeaError([], $error->getMessage(), $error->getCode(), $error);
            }
            // 如有需要，请打印 error
            Utils::assertAsString($error->message);
        }
    }

    public static function create_exec($client, $request, $headers, $runtime, $action, $pathname)
    {
        $body = $request;
        $req = new OpenApiRequest([
            'headers' => $headers,
            'body'    => OpenApiUtilClient::parseToMap($body),
        ]);
        $params = new Params([
            'action'      => $action,
            'version'     => '2022-03-01',
            'protocol'    => 'HTTPS',
            'pathname'    => $pathname,
            'method'      => 'POST',
            'authType'    => 'AK',
            'style'       => 'ROA',
            'reqBodyType' => 'json',
            'bodyType'    => 'json',
        ]);

        return $client->execute($params, $req, $runtime);
    }

	public static function GetFile($request) {
		return self::create($request, "GetFile", "/v2/file/get");
	}

	public static function ListFile($request) {
		return self::create($request, "ListFile", "/v2/file/list");
	}

	public static function CreateCustomizedStory($request) {
		return self::create($request, "CreateCustomizedStory", "/v2/image/create_customized_story");
	}

	public static function CreateSimilarImageClusterTask($request) {
		return self::create($request, "CreateSimilarImageClusterTask", "/v2/image/create_similar_image_cluster_task");
	}

	public static function GetTaskStatus($request) {
		return self::create($request, "GetTaskStatus", "/v2/image/get_task_status");
	}

	public static function SearchSimilarImageClusters($request) {
		return self::create($request, "SearchSimilarImageClusters", "/v2/image/query_similar_image_clusters");
	}

    public static function GetMap() {
		$map1 = [
			"TimeStory1" => "Time_Story_id1",
			"TimeStory2" => "Time_Story_id2",
			"TimeStory3" => "Time_Story_id3",
			"TimeStory4" => "Time_Story_id4",
			"TimeStory5" => "Time_Story_id5",
			"TimeStory6" => "Time_Story_id6",
			"TimeStory7" => "Time_Story_id7",
			"TimeStory8" => "Time_Story_id8",
			"TimeStory9" => "Time_Story_id9",
			"TimeStory10" => "Time_Story_id10",
			"PersonStory1" => "Person_Story_id1",
			"PersonStory2" => "Person_Story_id2"
		];

        return $map1;
    }

	public static function encode($data) {
		return json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
	}

    // 保存每次测试的计数
    public static function get_test_count() {
        return "10";
    }

    public static function get_drive_id() {
        return "<drive_id>";
    }
}

