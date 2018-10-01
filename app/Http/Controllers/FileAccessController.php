<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Http\Request;
use Carbon\Carbon;
use GuzzleHttp;
/*use Psr\Http\Message;*/

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
        if (parse_url($uri, PHP_URL_SCHEME) !== 'https') {
            abort(500, 'Only HTTPS-connections allowed for Cloud-Connection');
        }

        if (substr($uri, -1) !== '/') {
            $uri .= '/';
        }


        /*$redirect_verification = function(Message\RequestInterface $request, Message\ResponseInterface $response, Message\UriInterface $new_uri) use ($uri) {
            $original_host = parse_url($uri, PHP_URL_HOST); // Host before any redirect
            $old_host = parse_url($request->getUri(), PHP_URL_HOST); // Host before this redirect
            $new_host = parse_url($new_uri, PHP_URL_HOST); // Host after this redirect

            if ($old_host !== $new_host || $new_host !== $original_host) {
                abort(500, 'Cloud-Connection redirects are only allowed to the same host.');
            }
        };*/

        $this->connection = new GuzzleHttp\Client(
            [
                'base_uri' => $uri . 'v2.php/apps/files_sharing/api/v1/',
                'auth' => [$username, $password],
                'headers' => ['OCS-APIRequest' => 'true', 'Accept' => 'application/json'],
                'allow_redirects' => false
                /*
                 * Just don't allow redirects ever. This causes $this->parseResponse to throw unrelated exceptions in case of redirects, which is not ideal.
                 * Alternatively, we could use 'allow_redirects' => ['protocols' => ['https'], 'on_redirect' => $redirect_verification, 'strict' => true]
                 * However, that could be just as bad. Generally, we don't want any redirects because of conversion of different methods (POST becomes GET) and other inconsistencies.
                 * If the cloud server address changes, it should be changed in .env
                 */
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

    private function generateCloudUrl($type = null, $id = null) {
        $cache_key = 'cloudshare_url_' . $type . '_' . $id;
        $cachetime = 710; //minutes
        $cloudshare_url = \Cache::get($cache_key);
        if (null === $cloudshare_url) {
            $config = \Config::get('cloud');

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

        return $cloudshare_url;
    }

    public function accessFiles($type = null, $id = null, $accepted_warning = false) {
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

        if ($config['shares'][$type]['folders'][$id]['requires_warning']) {
            return view('file_access.warning', ['hide_navbar' => true]);
        } else {
            return redirect($this->generateCloudUrl($type, $id));
        }
    }

    public function accessFilesAccept(Request $request, $type = null, $id = null) {
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

        if (filter_var($request->get('accepted_warning', false), FILTER_VALIDATE_BOOLEAN) === true) {
            return redirect($this->generateCloudUrl($type, $id));
        } else {
            return view('file_access.warning', ['hide_navbar' => true]);
        }
    }
}
