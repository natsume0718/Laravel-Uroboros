<div class="row justify-content-center">
    <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
        <div class="table-responsive-md mt-2 vh-50">
            <table class="table table-stiped table-bordered bg-white">
                <thead>
                    <tr>
                        <th>ユーザー</th>
                        <th>活動内容</th>
                        <th>投稿時刻</th>
                        <th>活動時間</th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($posts as $post)
                    @include('components.posts',compact('post','activity'))
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>