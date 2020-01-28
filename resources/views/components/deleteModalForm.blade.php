<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modal-delete-{{$id}}">
    削除
</button>

<div class="modal fade" id="modal-delete-{{$id}}" tabindex="-1" role="dialog"
    aria-labelledby="modal-delete-{{$id}}-label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><strong>{{ $name }}</strong></p>
                <p>本当に削除しますか</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-blue-grey" data-dismiss="modal">
                    キャンセル
                </button>
                <form action="{{$url}}" method="post">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        削除
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>