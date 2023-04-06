@section('br-defined')

    {{
        Breadcrumbs::for('home', function ($trail) {
                $trail->push('Home', route('home'));
            })
    }}

    {{
        Breadcrumbs::for('user', function ($trail) {
                $trail->parent('home');
                $trail->push('List user master', route('user.index'));
            })
    }}

    {{
        Breadcrumbs::for('add', function ($trail) {
            $trail->parent('user');
            $trail->push('Add', route('user.create'));
            })
    }}

@endsection
