@extends('layouts.app')

@section('content')
    <div class="row">
        <aside class="col-sm-4">
            @include('users.card', ['user' => $user])
            @include('user_favorite.favorite_button', ['micripost' => $micropost])
        </aside>
        <div class="col-sm-8">
            @include('users.navtabs', ['user' => $user])
            @if (Auth::id() == $user->id)
                {!! Form::open(['route' => 'microposts.store']) !!}
                    <div class="form-group">
                        {!! Form::textarea('content', old('content'), ['class' => 'form-control', 'rows' => '2']) !!}
                        {!! Form::submit('Post', ['class' => 'btn btn-primary btn-block']) !!}
                    </div>
                {!! Form::close() !!}
            @endif
            @if (count($microposts) > 0)
                @include('user_follow.follow_button', ['user' => $user])
                @include('microposts.microposts', ['microposts' => $microposts])
            @endif
        </div>
    </div>
@endsection