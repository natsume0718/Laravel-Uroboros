<div class="col-lg-4 col-md-12 col-xs-12">
    <ul class="list-group text-center mt-4">
        <li class="list-group-item">{{ $activity->name }}</li>
        <li class="list-group-item">活動日数：{{ $activity->activity_days ?? 0 }} 日</li>
        <li class="list-group-item">作成日 : {{ $activity->created_at->format('Y年m月d日 H時i分') }}</li>
        <li class="list-group-item">
            <a class="btn btn-info" href="{{ route('activity.show',[$user->nickname ,$activity->name]) }}"
                role="button">記録する</a>
        </li>
        <li class="list-group-item">
            @component('components.deleteModalForm')
            @slot('url', route('activity.destroy',[$user->nickname,$activity->id]))
            @slot('id', $activity->id)
            @slot('name', $activity->name)
            @endcomponent
        </li>
    </ul>
</div>