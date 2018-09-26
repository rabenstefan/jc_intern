<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\AuthController;
use Carbon\Carbon;
use GuzzleHttp;

class FileAccessController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct() {
        $this->middleware('auth');
    }

    protected const available_params = ['path', 'shareType', 'publicUpload', 'permissions', 'shareWith', 'expireDate'];

    protected $connection = null;

    protected function connectCloud($uri, $username, $password) {
        if (substr($uri, -1) !== '/') {
            $uri .= '/';
        }

        $this->connection = new GuzzleHttp\Client(
            [
                'base_uri' => $uri . 'v2.php/apps/files_sharing/api/v1/',
                'auth' => [$username, $password],
                'headers' => ['OCS-APIRequest' => 'true', 'Accept' => 'application/json'],
            ]
        );
    }

    protected function parseResponse($request_result) {
        return json_decode(
            $request_result->getBody()->getContents()
        )->ocs->data;
    }

    protected function createShare($params) {
        // https://docs.nextcloud.com/server/14/developer_manual/core/ocs-share-api.html#create-a-new-share
        $first_response = $this->parseResponse($this->connection->request('POST', 'shares', ['form_params' => $params]));
        // Due to a bug in NextCloud, we need update everything just to be sure
        return $this->updateShare($first_response->id, $params);
    }

    protected function updateShare($id, $params) {
        // https://docs.nextcloud.com/server/14/developer_manual/core/ocs-share-api.html#update-share
        return $this->parseResponse($this->connection->request('PUT', 'shares/' . $id, ['json' => $params]));
    }

    public function accessFiles($type = null, $id = null) {
        if ($type === null || $id === null) {
            abort(404);
        }

        $config = \Config::get('cloud');
        if (!array_key_exists($type, $config['shares'])) {
            abort(404);
        }
        if ($config['shares'][$type]['requires_admin'] !== false && !\Auth::user()->isAdmin()) {
            abort(403);
        }

        if (!array_key_exists($id, $config['shares'][$type]['folders'])) {
            abort(404);
        }

        $cache_key = 'cloudshare_url_' . $type . '_' . $id;
        $cachetime = 710; //minutes
        $cloudshare_url = \Cache::get($cache_key);
        if (null === $cloudshare_url) {
            $folder_config = $config['shares'][$type]['folders'][$id];

            $this->connectCloud($config['uri'],  $config['shares'][$type]['username'], $config['shares'][$type]['password']);

            $cloudshare_result = $this->createShare([
                'path' => $folder_config['path'],
                'shareType' => 3,
                'publicUpload' => $folder_config['public_upload'],
                'permissions' => $folder_config['permissions'],
                // Shares always expire at midnight. Hence, to make sure that there are no caching problems, we need to keep the share active until midnight after cachetime ends
                'expireDate' => Carbon::now()->addMinutes($cachetime + 10)->addDay()->toDateString()
            ]);

            $cloudshare_url = $cloudshare_result->url;
            \Cache::put($cache_key, $cloudshare_url, $cachetime);
        }

        return redirect($cloudshare_url);
    }
}
