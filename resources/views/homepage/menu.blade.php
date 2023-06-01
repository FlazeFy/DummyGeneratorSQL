
<div class="row">
    @foreach($menu as $m)
        <div class="col-lg-4 col-md-6 col-sm-12">
            <button class="menu-box">
                <img class="img img-fluid d-block mx-auto" src="<?= asset($m->menu_image_path); ?>">
                <h5>{{ucfirst($m->menu_name)}}<h5>
                <p>{{ucfirst($m->menu_desc)}}</p>
            </button>
        </div>
    @endforeach
</div>