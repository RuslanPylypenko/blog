<?php
/**
 * @var $article \App\Entities\Article
 */
?>
@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="row">
            <div class="col-sm-9"><h1>Articles</h1></div>
            <div class="col-sm-3">
                <div class="text-right">
                    <div class="dropdown">
                        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                            {{$sort}} {{$dir}}
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="?sort=views&dir=desc&page={{$articles->currentPage()}}">views desc</a>
                            <a class="dropdown-item" href="?sort=views&dir=asc&page={{$articles->currentPage()}}">views asc</a>
                            <a class="dropdown-item" href="?sort=likes&dir=desc&page={{$articles->currentPage()}}">likes desc</a>
                            <a class="dropdown-item" href="?sort=likes&dir=asc&page={{$articles->currentPage()}}">likes asc</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>


        @foreach ($articles as $article)
            <div class="card">
                <div class="card-header">
                    <h4 class="font-weight-bold">{{$article->title}}</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-2">
                            <img src="{{$article->image}}"
                                 class="img-thumbnail"
                                 alt="{{$article->title}}">
                        </div>
                        <div class="col-sm-10">
                            <p>{{$article->getShortText()}}</p>
                            <a href="{{route('article', ['id' => $article->id])}}" class="btn btn-info">Подробнее</a>
                        </div>
                    </div>

                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-sm-2">
                            <div class="row">
                                <div class="col-sm-5">
                                    <i class="fa fa-eye"></i>
                                    {{$article->views}}
                                </div>
                                <div class="col-sm-7">
                                    <form action="{{route('article-like')}}" method="post">
                                        @csrf
                                        <button type="submit"
                                                name="article"
                                                value="{{$article->id}}"
                                                class="btn btn-light no-padding">
                                            <i class="fa fa-heart"></i>
                                            {{$article->likes}}
                                        </button>
                                    </form>

                                </div>
                            </div>

                        </div>
                        <div class="col">
                            <div class="text-right">
                                <i class="fa fa-calendar" aria-hidden="true"></i>
                                {{$article->created_at}}
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <hr>
        @endforeach

        {{ $articles->appends(['sort' => $sort, 'dir' => $dir])->links() }}


    </div>
@endsection
