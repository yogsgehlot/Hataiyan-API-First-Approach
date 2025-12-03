<?php

    namespace App\Services;

    use Illuminate\Support\Facades\Http;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Log;
    use Illuminate\Http\UploadedFile;

    class ApiService
    {
        protected $apiUrl;
        protected $headers;
        protected $token = '';

        public function __construct(Request $request)
        {
            $this->apiUrl = rtrim(config('services.api_url'), '/');
            $this->token = session()->get('api_token');
            $this->headers = [
                'Accept' => 'application/json',
            ];

            if ($this->token) {
                $this->headers['Authorization'] = 'Bearer ' . $this->token;
            }
        }

        protected function handleResponse($response)
        {
            $status = $response->status();
            $body = json_decode($response->body(), true);
            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $body['data'] ?? $body,
                    'message' => $body['message'] ?? 'Success',
                ];
            }

            Log::error('API Error:', [
                'status' => $status,
                'response' => $body,
            ]);

            return [
                'success' => false,
                'message' => $body['message'] ?? 'Something went wrong',
                'errors' => $body['errors'] ?? [],
            ];
        }

        protected function hasFiles(array $params): bool
        {
            foreach ($params as $value) {
                if ($value instanceof UploadedFile) {
                    return true;
                }

                if (is_array($value) && $this->hasFiles($value)) {
                    return true;
                }
            }
            return false;
        }

        protected function sendRequest(string $method, string $endpoint, array $params = [])
        {
            $url = $this->apiUrl . '/' . ltrim($endpoint, '/');
            try {
                $httpClient = Http::withHeaders($this->headers);
                $hasFiles = $this->hasFiles($params);

                if ($hasFiles) {

                    $client = $httpClient->asMultipart();
                    $data = [];
                    foreach ($params as $key => $value) {
                        if ($value instanceof UploadedFile) {
                            $client = $client->attach(
                                $key,
                                file_get_contents($value->getRealPath()),
                                $value->getClientOriginalName()
                            );
                        } else {
                            $data[$key] = $value;
                        }
                    }
                    $response = match (strtolower($method)) {
                        'post' => $client->post($url, $data),
                        'put' => $client->put($url, $data),
                        'patch' => $client->patch($url, $data),
                        default => throw new \InvalidArgumentException("File uploads are only supported for POST, PUT, and PATCH methods"),
                    };
                } else {
                    
                    $response = match (strtolower($method)) {
                        'get' => $httpClient->get($url, $params),
                        'post' => $httpClient->post($url, $params),
                        'put' => $httpClient->put($url, $params),
                        'patch' => $httpClient->patch($url, $params),
                        'delete' => $httpClient->delete($url, $params),
                        default => throw new \InvalidArgumentException("Invalid HTTP method: $method"),
                    };
                }

                return $this->handleResponse($response);
            } catch (\Throwable $e) {
                Log::error("API Request Exception: {$endpoint}", [
                    'method' => $method,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);

                return [
                    'success' => false,
                    'message' => 'Unable to connect to API server: ' . $e->getMessage(),
                ];
            }
        }
        public function get(string $endpoint, array $params = [])
        {
            return $this->sendRequest('get', $endpoint, $params);
        }

        public function post(string $endpoint, array $params = [])
        {
            return $this->sendRequest('post', $endpoint, $params);
        }

        public function put(string $endpoint, array $params = [])
        {
            return $this->sendRequest('put', $endpoint, $params);
        }

        public function patch(string $endpoint, array $params = [])
        {
            return $this->sendRequest('patch', $endpoint, $params);
        }

        public function delete(string $endpoint, array $params = [])
        {
            return $this->sendRequest('delete', $endpoint, $params);
        }

        public function updateToken($token)
        {
            $this->token = $token;
            $this->headers['Authorization'] = 'Bearer ' . $token;
            session(['api_token' => $token]);
        }
    }