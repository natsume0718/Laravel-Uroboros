<tr>
    <td><img src="{{ $user->avatar }}" alt=""><span>{{ $user->nickname }}</span>
    </td>
    <td>{!! nl2br(e($post->content)) !!}</td>
    <td>{{ $post->created_at }}</td>
    <td>{{ $post->hour }}</td>
    <td>
        <a target="_blank" href="https://twitter.com/{{$user->nickname}}/status/{{$post->tweet_id}}">Twitterで表示</a>
    </td>
    <td>

    </td>
</tr>