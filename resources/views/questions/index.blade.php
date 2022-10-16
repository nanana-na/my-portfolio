@extends('layouts.app')
@section('content')
<div class="container" style="max-width: 850px;">
  <div class=" col-md-8">
    @if ($errors->any())
    <div class="alert alert-danger">
      @foreach ($errors->all() as $error)
      <p>{{ $error }}</p>
      @endforeach
    </div>
    @endif
  </div>

  @if (session('flash_message'))
  <div class="alert alert-danger">
    {{ session('flash_message') }}
  </div>
  @endif
  <h2><span class="badge badge-info text-white">アンケート</span></h2>
  <div class="justify-content-center">
    <div class="question">
      @foreach ($questions as $question)
      <div class="question-content">
        <div class="question-group">
          <h4 class="noto-sans">{{ $question->title}}</h4>
        </div>
        <div class="question-item-box">
          @foreach( $question->question_items as $question_item)
          <div class="question-item">
            <ul style="position: relative;">
              @if($loop->iteration == 1)
              <div class="oukan top-one"></div>
              @endif
              <li style="margin-right:auto;margin-left:auto;">
                <div class="noto-sans question-option">{{ $question_item->option }}</div>
              </li>
              <li style="margin-right:20px;min-width:45px">
                <div class="question-count">{{$question_item->answers->count()}}</div>
              </li>
              @if($question->answers->where('user_id', $user_id)->count() > 0)
              @if($question->answers->where('user_id', $user_id)->first()->question_item_id == $question_item->id)
              <li class="question-last"><i class="bi bi-check-lg" style="color:#54a8ff;font-size: 21px;"></i></li>
              @else
              <li class="question-last"></li>
              @endif
              @else
              <li class="question-last">
                <form action="{{ route('answers.store') }}" method="POST">
                  @csrf
                  <input type="hidden" name="question_id" value="{{ $question->id }}">
                  <input type="hidden" name="question_item_id" value="{{ $question_item->id }}">
                  <button type="submit" class="btn btn-small">選択
                  </button>
                </form>
              </li>
              @endif
            </ul>
          </div>
          @if (isset(Auth::user()->number))
          @if(Auth::user()->number == 20000000)
          <a href="{{ route('questionitems.edit', $question_item->id) }}">修正</a>
          @endif
          @endif
          @endforeach
        </div>
        <div class="question-time">
          <p>{{$question->created_at->format('n月j日')}}</p>
        </div>
        @if (isset(Auth::user()->number))
        @if(Auth::user()->number == 20000000)
        <form action="{{ route('questionitems.store') }}" method="POST">
          @csrf
          <input type="hidden" name="question_id" value="{{$question->id}}">
          <ul>
            <li>
              <div class="form-group">
                <input type="text" class="form-control" placeholder="項目" name="option" value="{{ old('option') }}">
              </div>
            </li>
            <li>
              <button type="submit" class="btn btn-primary">作成する</button>
            </li>
          </ul>
        </form>
        <form style="display: inline-block;" method="POST" action="{{ route('questions.destroy', $question->id) }}">
          {{ csrf_field() }}
          {{ method_field('DELETE') }}
          <button class="btn btn-small" onclick='return confirm("削除しますか？");'>削除する</button>
        </form>
        <a href="{{ route('questions.edit', $question->id) }}">修正する</a>
        @endif
        @endif
      </div>
      @endforeach
    </div>
    @if (isset(Auth::user()->number))
    @if(Auth::user()->number == 20000000)
    <a href="/questions/create">作成</a>
    @endif
    @endif
    <div class="bottom-item">
      <p>※追加してほしい項目がある場合は、<br><a href="https://www.instagram.com/sadaikeiziban/" target=" _blank">Instagram</a>からご連絡ください！</p>
    </div>
  </div>
</div>
@endsection
