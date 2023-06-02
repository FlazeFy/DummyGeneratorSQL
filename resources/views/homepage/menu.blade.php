
<div class="row">
    @foreach($menu as $m)
        <div class="col-lg-4 col-md-6 col-sm-12">
            <form class="d-inline p-0 m-0" action="/open/{{$m->menu_name}}" method="POST">
                @csrf
                <button class="menu-box">
                    <img class="img img-fluid d-block mx-auto" src="<?= asset($m->menu_image_path); ?>">
                    <h5>{{ucfirst($m->menu_name)}}<h5>
                    <p>{{ucfirst($m->menu_desc)}}</p>
                    <div class="stats-box">
                        <h6>Total Dummy Generated</h6>
                        <h2>200</h2>
                    </div>
                </button>
            </form>
        </div>
    @endforeach
</div>