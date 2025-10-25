<?php

namespace App\ApiResource\v1;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;

#[ApiResource(
    uriTemplate: 'v1',
    shortName: 'books',
    operations: [
        new GetCollection(
            uriTemplate: '/v1/books',
            provider: \App\State\Books\Providers\GetCollection::class,
        ),
        new Get(
            uriTemplate: '/v1/books/{id}',
            provider: \App\State\Books\Providers\Get::class,
        ),
        new Post(
            uriTemplate: '/v1/books',
            processor: \App\State\Books\Processors\Post::class,
        ),
        new Delete(
            uriTemplate: '/v1/books/{id}',
            provider: \App\State\Books\Providers\Delete::class,
            processor: \App\State\Books\Processors\Delete::class
        )
    ],
)]
final class Book
{
    private int $_id;
    public int $id {
        get => $this->_id;
        set => $this->_id = $value;
    }

    private string $_title;
    public string $title {
        get => $this->_title;
        set => $this->_title = $value;
    }

    private string $_author;
    public string $author {
        get => $this->_author;
        set => $this->_author = $value;
    }
}
