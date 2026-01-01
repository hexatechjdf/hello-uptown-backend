<?php

namespace App\Services\Admin\News;

use App\Repositories\Admin\News\NewsRepository;

class NewsService
{
    protected $repo;

    public function __construct(NewsRepository $repo)
    {
        $this->repo = $repo;
    }

    public function all($filters = [], $sort = 'published_at', $order = 'desc', $perPage = 10)
    {
        return $this->repo->all($filters, $sort, $order, $perPage);
    }
    public function find($id)
    {
        return $this->repo->find($id);
    }
    public function create(array $data)
    {
        $data = $this->prepareData($data);
        return $this->repo->create($data);
    }

    public function update($item, array $data)
    {
        $data = $this->prepareData($data);
        return $this->repo->update($item, $data);
    }

    public function delete($item)
    {
        return $this->repo->delete($item);
    }

    private function prepareData(array $data): array
    {
        $fieldMappings = [
            'imageUrl' => 'image',
            'articleUrl' => 'article_url',
            'publishedAt' => 'published_at',
        ];

        foreach ($fieldMappings as $inputKey => $dbKey) {
            if (array_key_exists($inputKey, $data)) {
                $data[$dbKey] = $data[$inputKey];
                unset($data[$inputKey]);
            }
        }

        $data['featured'] = $data['featured'] ?? false;
        unset($data['slug']);
        if (isset($data['published_at']) && is_string($data['published_at'])) {
            $data['published_at'] = \Carbon\Carbon::parse($data['published_at'])->format('Y-m-d H:i:s');
        }

        return $data;
    }
}
