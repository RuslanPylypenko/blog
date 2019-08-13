<?php
/**
 * @var $article \App\Entities\Article
 */
?>
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h1 class="font-weight-bold">{{$article->title}}</h1>
            </div>
            <div class="card-body">

                <img src="{{$article->getImage()}}"
                     class="img-thumbnail float-left"
                     alt="{{$article->title}}">

                <p>{{$article->text}}</p>

                <div class="text-right">
                    <a href="{{route('articles')}}" class="btn btn-info">Назад к списку статтей</a>
                </div>

            </div>
        </div>
        <div class="card-footer">
            <div class="row">
                <div class="col-sm-2">
                    <div class="row">
                        <div class="col-sm-6">
                            <i class="fa fa-eye"></i>
                            {{$article->views}}
                        </div>
                        <div class="col-sm-6">
                            <i class="fa fa-heart"></i>
                            {{$article->likes}}
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

        @if(!empty($article->comments))
            <hr>

            <div class="card">
                <div class="card-header"><h2>Комментарии:</h2></div>
                <div class="card-body">
                    @foreach($article->comments as $comment)
                        <div class="alert alert-secondary">
                            <strong>{{$comment->user_id}}</strong> {{$comment->text}}
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>

@endsection
