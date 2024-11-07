<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Elasticsearch\ClientBuilder;

class ElasticsearchModel extends Model
{
    protected $index;
    protected $type;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->index = 'historis-bulanan-2023-temperature-maximum-bulanan';

        $this->setConnection('elasticsearch');
    }

    public function getClient()
    {
        return ClientBuilder::fromConfig(config('database.connections.elasticsearch'));
    }

    public function search(array $query)
    {
        $client = $this->getClient();

        $params = [
            'index' => $this->index,
            'body' => $query,
        ];

        $response = $client->search($params);

        // Handle and return the search response as needed
        return $response;
    }

    // Define other Elasticsearch operations as needed, e.g., indexing, deleting, etc.
}
