<tr>
    <td>
        <img src="{{ $user->avatar }}" alt=""><span>{{ $user->nickname }}</span>
    </td>
    <td>{!! nl2br(e($post->content)) !!}</td>
    <td>{{ $post->created_at }}</td>
    <td>{{ $post->hour }}</td>
    <td>
        <a target="_blank" href="https://twitter.com/{{$user->nickname}}/status/{{$post->tweet_id}}">Twitterで表示</a>
    </td>
    <td>
        @component('components.deleteModalForm')
        @slot('id',$post->id)
        @slot('name',$post->name)
        @slot('url',route('post.destroy',[$user->nickname,$activity->name,$post->id]))
        @endcomponent
    </td>
</tr>