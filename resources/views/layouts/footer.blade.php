<div class="bg-aqua-gradiention w-100"></div>
<footer class="page-footer font-small bg-aqua-gradiention pt-4">
    <div class="container-fluid text-center text-md-left">
        <div class="row">
            <div class="col-md-6 mt-md-0 mt-3">
                <img src="{{ asset('img/logo.png', true) }}" alt="">
                <p>継続を記録しよう With Uroboros App</p>
            </div>
            <hr class="clearfix w-100 d-md-none pb-3">
            <div class="col-md-3 mb-md-0 mb-3">
                <h5 class="text-uppercase">Socail</h5>
                <ul class="list-unstyled">
                    <li>
                        <a href="https://twitter.com/natsume_aurlia" class="btn-floating btn-lg btn-tw" type="button"
                            role="button"><i class="fab fa-twitter"></i></a>
                    </li>

                </ul>
            </div>
        </div>
    </div>
    <div class="text-center py-3">© {{today()->year}} Copyright:
        <a href="{{route('top')}}"> Uroboros.site</a>
    </div>
</footer>