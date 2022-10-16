<header>

  <nav class="pc-nav">
    <ul>
      <li style="padding-right:20px;"><a class="btn btn-hai" href="/posts"><i class="bi bi-grid"></i><br><span class="header-hide header-long" style="font-size: 8px;letter-spacing:0.1px">サークル</span></a></li>
      <li style="padding-right:20px;"><a class="btn btn-hai" href="/jobs"><i class="bi bi-cash-coin"></i><br><span class="header-hide" style="font-size: 8px;">バイト</span></a></li>
      <li style="padding-right:20px;"><a class="btn btn-hai" style="min-width:53px;" href="/frees"><i class="bi bi-chat-right"></i><br><span class="header-hide" style="font-size: 8px;">チャット</span></a></li>
      <li style="padding-right:20px;"><a class="btn btn-hai" href="/friends"><i class="bi bi-mortarboard"></i><br><span class="header-hide" style="font-size: 8px;">交流</span></a></li>
      <li><a class="btn btn-hai" style="min-width:53px;" href="/questions"><i class="bi bi-star"></i></i><br><span class="header-hide" style="font-size: 8px;margin: 0 -9px 0 -9px;">アンケート</span></a></li>
    </ul>
  </nav>
</header>
@if (isset(Auth::user()->number))
@if(Auth::user()->number == 20000000)
<div class="container">
  <ul>
    <li class="p-1"><a class="btn btn-hai" href="/posts/create">
        <p class="header-hide2">サークル作成</p>
      </a></li>
    <li class="p-1"><a class="btn btn-hai" href="/jobs/create">
        <p class="header-hide2">バイト作成</p>
      </a></li>
    <li class="p-1"><a class="btn btn-hai" href="/frees/create">
        <p class="header-hide2">チャット作成</p>
      </a></li>
    <li class="p-1"><a class="btn btn-hai" href="/yets">
        <p class="header-hide2">未ユーザー確認</p>
      </a></li>
    <li class="p-1"><a class="btn btn-hai" href="{{ route('admins.index') }}">
        <p class="header-hide2">ユーザー一覧</p>
      </a></li>
    <li class="p-1"><a class="btn btn-hai" href="{{ route('admins.create') }}">
        <p class="header-hide2">ユーザー作成</p>
      </a></li>
  </ul>
</div>
<div class="text-center">
  <form style="display: inline-block;" method="POST" action="/yesterday">
    {{ csrf_field() }}
    {{ method_field('DELETE') }}
    <button class="btn btn-hai" onclick='return confirm("削除しますか？");'><span style="font-size: 8px;">昨日の分を消す</span></button>
  </form>
  <form style="display: inline-block;" method="POST" action="/reduce">
    {{ csrf_field() }}
    {{ method_field('DELETE') }}
    <button class="btn btn-hai" onclick='return confirm("削除しますか？");'><span style="font-size: 8px;">掲示板の3日前のコメントを消す</span></button>
  </form>
</div>
@endif
@endif
